<?php

namespace App\Actions;

use App\Actions\DatabaseTransitionToReview;
use App\Events\DatabasePublishedAwaitingApproval;
use App\Events\DatabasePublishingFailed;
use App\Exceptions\ReferenceException;
use App\Jobs\DatabaseEmptyRadar;
use App\Jobs\DatasetUploadBatchLoader;
use App\Mail\DatabasePersistentPublicationFailed;
use App\Mail\DatabasePersistentPublicationRequested;
use App\Mail\DatabasePersistentPublicationRequestedStarted;
use App\Models\Database;
use App\Models\User;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Throwable;

use App\Services\DatabaseRadarDatasetBridge;

/*
 * You can include this in a function using:
 *
 * 		app(\App\Actions\DatabaseDispatchDatasetsInBatchToRadar::class)->handle($this->database, $actorId);
 *
 * This means that we can use the same code in, e.g., our Livewire Datathek Info page and in our DatabasePublishToRadar job
 */
class DatabaseDispatchDatasetsInBatchToRadar
{
	// Number of datasets per loader job. Each loader adds one DatasetUploadInBatchToRadar
	// job per dataset to the batch. Dispatching all upload jobs at once took too long for
	// large databases (14 400 datasets exceeded the 3600 s timeout of DatabasePublishInBatchToRadar).
	const JOBS_PER_PIECE = 1000; 

	public static function batchName(Database $database): string
	{
		return 'radar-publish-database-'.$database->id;
	}

	public function handle(Database $database, int $actorId, bool $shouldReview = true): void
	{
		$logContext = [
			'method' => __METHOD__,	
			'feature' => 'database-radar-dataset',
			'database_id' => $database->id,
			'radar_id' => $database->radar_id,
			'target_url' => config('services.radar.baseurl')
		];
		if($database->radar_id === null) {
			// This is picked up by the DatabasePublishInBatchToRadar job,
			// since it is thrown before we start the DatasetUploadInBatchToRadar batch jobs
			throw new \Exception('Trying to upload datasets to RADAR when the radar_id is null.');
		}

		// Strip loaded relations: $database is captured by the batch closures below and passed to the
		// loader jobs. With relations (e.g. all datasets) every restore would reload them (memory exhaustion).
		$database = $database->withoutRelations();

		$actor = User::find($actorId);
		$datasetIds = $database->datasets()->orderBy('id')->pluck('id');
		$loaders = [];
		foreach ($datasetIds->chunk(self::JOBS_PER_PIECE) as $piece) {
			$loaders[] = new DatasetUploadBatchLoader($piece->values()->all(), $database, $actor);
		}

		Log::debug('Batch prepared', [
			...$logContext,
			'jobsPerPiece' => self::JOBS_PER_PIECE,
			'nDatasets' => $datasetIds->count(),
			'nLoaders' => count($loaders)
		]);

		// The upload jobs are added to the batch by the loader jobs (see DatasetUploadBatchLoader)
		$batch = Bus::batch($loaders)
			// the name is used by DatabaseStopPublishingToRadar to find the batch of a database
			->name(self::batchName($database))
			->onQueue('uploads')
			->then(function (Batch $batch) use ($database, $shouldReview, $actorId, $logContext) {
				//
				// SUCCESS: Executed once ALL jobs in the batch complete successfully!
				//
				// The closure's $database was restored from the DB when the batch was loaded,
				// so make sure we work with the current state
				$database->refresh();
				Log::debug('Batch successful (then)', $logContext);
				if($shouldReview) {
					Log::debug('Will now transition to review status', $logContext);
					$actor = User::find($actorId);
					try {
						app(DatabaseTransitionToReview::class)->handle($database, $actor, $batch->id);
					} catch (Throwable $e) {
						// Laravel only reports exceptions thrown in batch callbacks, so handle it here:
						// the data is uploaded, but the review could not be started.
						$referenceException = new ReferenceException("Starting the RADAR review of database '".$database->id."' failed after uploading all datasets", 0, $e);
						Log::error('Starting the RADAR review failed ('.$referenceException->getReference().'): '.$e->getMessage(), [
							...$logContext,
							'batch_id' => $batch->id,
						]);
						// reset radar_status so the user can publish again. Already uploaded datafiles
						// are skipped, so a new attempt will basically only start the review.
						$database->refresh();
						if($database->radar_status == 2) {
							$database->radar_status = 1;
							$database->save();
						}
						Mail::to($database->user->email)->queue(new DatabasePersistentPublicationFailed($database, $referenceException, false, actor: $actor));
						Mail::to(config('mail.to.admins'))->queue(new DatabasePersistentPublicationFailed($database, $referenceException, true, actor: $actor));
						DatabasePublishingFailed::dispatch($database->id);
					}
				}
				else {
					Log::debug('Explicitly *not* reviewing, since probably called from DatabaseRadarActions', $logContext);
				}
			})
			->catch(function (Batch $batch, Throwable $e) use ($database, $actorId, $logContext) {
				//
				// FAILED: Executed if any job in the batch fails permanently
				//
				$actor = User::find($actorId);
				$database->refresh(); // closure's $database may be stale

				// Cancel remaining jobs in this batch
				Log::debug("Cancelling remaining batch jobs (catch)", $logContext);
				$batch->cancel();

				//jw:question: Do we need to send an email here?
				//jw:answer:   No - the email is sent in the DatasetUploadInBatchToRadar job's 'failed()' function

				// Empty RADAR dataset in the background (one RADAR request per dataset, may take hours).
				// It runs on the 'uploads' queue, i.e. after the remaining (cancelled) upload jobs.
				Log::info('Emptying RADAR dataset since batch upload failed (DatabaseEmptyRadar job).', $logContext);
				DatabaseEmptyRadar::dispatch($database, $actor);
			})
			->finally(function (Batch $batch) use ($database, $logContext) {
				// Executed once the batch has finished (success or failure)
				// Good place for cleanup tasks (e.g., deleting temporary records)

				//jw:q Do we need to set the radar_status here?
				//jw:a No. This is also done in the DatasetUploadInBatchToRadar job's 'failed()' function

				// $database was restored from the DB when the batch was loaded, i.e. *before* the
				// 'then' callback (DatabaseTransitionToReview) updated radar_status. Reload it.
				$database->refresh();
				Log::debug('TODO: check there is nothing to clean up (finally)', $logContext);
				Log::debug('radar_id = '.$database->radar_id.' radar_status = '.$database->radar_status, [
					...$logContext,
					'batch_id' => $batch->id,
					'failed_jobs' => $batch->failedJobs,
				]);
				if($batch->hasFailures() && $database->radar_status == 2) {
					Log::error('The radar_status is still 2 after the batch upload failed', [
						...$logContext,
						'batch_id' => $batch->id,
						'failed_jobs' => $batch->failedJobs,
					]);
					//jw:q Do we need to throw and exception here?
					//jw:a No. This is also done in the DatasetUploadInBatchToRadar job's 'failed()' function
				}

			})
			->dispatch();

		// Log the batch ID immediately upon start
		Log::debug('Upload batch '.$batch->id.' dispatched', [
			...$logContext,
			'batch_id' => $batch->id,
			'nDatasets' => $datasetIds->count(),
			'nLoaders' => count($loaders)
		]);
	}
}
