<?php

namespace App\Actions;

use App\Actions\DatabaseTransitionToReview;
use App\Events\DatabasePublishedAwaitingApproval;
use App\Events\DatabasePublishingFailed;
use App\Jobs\DatasetUploadInBatchToRadar;
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
 *
 */
class DatabaseDispatchDatasetsInBatchToRadar
{
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
			Log::error('Trying to upload datasets to RADAR when the radar_id is null', $logContext);
			//jw:todo commenting out to see if RADAR throws an exception: throw new \RuntimeException('DatabaseDispatchDatasetsInBatchToRadar(): can\'t dispatch datasets in batch to RADAR because the radar_id is null');
		}

		$jobs = [];
		$nChunks = 1; // number of datasets per chunk. Any number which you are sure can upload within the DatasetDispatchDatasetsInBatchToRadar job timeout

		$database->datasets()->chunkById($nChunks, function ($datasets) use (&$jobs) {
			$datasetIds = $datasets->pluck('id')->toArray();
			$jobs[] = new DatasetUploadInBatchToRadar($datasetIds);
		});

		Log::debug('Batch prepared', [
			...$logContext,
			'nChunks' => $nChunks,
			'nJobs' => count($jobs)
		]);

		$batch = Bus::batch($jobs)
			->onQueue('uploads')
			->then(function (Batch $batch) use ($database, $shouldReview, $actorId, $logContext) {
				//
				// SUCCESS: Executed once ALL jobs in the batch complete successfully!
				//
				Log::debug('Batch successful (then)', $logContext);
				if($shouldReview) {
					Log::debug('Will now transition to review status', $logContext);
					app(DatabaseTransitionToReview::class)->handle($database,User::find($actorId));
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

				// Cancel remaining jobs in this batch
				Log::debug("Cancelling remaining batch jobs (catch)", $logContext);
				$batch->cancel();

				//jw:todo Do we need to send an email here?

				// Empty RADAR dataset
				$radar = new DatabaseRadarDatasetBridge($database);
				Log::info('Emptying RADAR dataset since batch upload failed.', $logContext);
				if(!$radar->empty()) {
					Log::error('Failed to empty the RADAR dataset.', $logContext);
					//jw:tmp commenting out to see if RADAR throws an exception: throw new \RuntimeException('DatabaseDispatchDatasetsInBatchToRadar(): Emptying the RADAR dataset due to upload exception failed');
				}
			})
			->finally(function (Batch $batch) use ($database, $logContext) {
				// Executed once the batch has finished (success or failure)
				// Good place for cleanup tasks (e.g., deleting temporary records)

				//jw:todo do we need to set the radar_status here?

				Log::debug('TODO: check there is nothing to clean up (finally)', $logContext);
				Log::debug('radar_id = '.$database->radar_id.' radar_status = '.$database->radar_status, $logContext);
				if($database->radar_status == 2) {
					Log::error('The radar_status of '.$database->radar_status.' is invalid, since dataset uploading failed', $logContext);
					//jw:todo send an exception?
				}

			})
			->dispatch();

		// Log the batch ID immediately upon start
		Log::debug('Upload batch '.$batch->id.' dispatched', [
			...$logContext,
			'batch_id' => $batch->id,
			'nChunks' => $nChunks,
			'nJobs' => count($jobs)
		]);
	}
}
