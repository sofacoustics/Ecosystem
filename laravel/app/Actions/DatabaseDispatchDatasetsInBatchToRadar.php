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
				'feature' => 'database-radar-dataset',
				'database_id' => $database->id,
				'radar_id' => $database->radar_id,
				'target_url' => config('services.radar.baseurl')
		];
		if($database->radar_id === null) {
			Log::error('DatabaseDispatchDatasetsInBatchToRadar(): radar_id is null', $logContext);
			throw new \RuntimeException('DatabaseDispatchDatasetsInBatchToRadar(): can\'t dispatch datasets in batch to RADAR because the radar_id is null');
		}

		$jobs = [];
		$nChunks = 10;

		$database->datasets()->chunkById($nChunks, function ($datasets) use (&$jobs) {
			$datasetIds = $datasets->pluck('id')->toArray();
			$jobs[] = new DatasetUploadInBatchToRadar($datasetIds);
		});

		Log::debug('DatabaseDispatchDatasetsInBatchToRadar(): preparing', [
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
				Log::debug('DatabaseDispatchDatasetsInBatchToRadar "then"', $logContext);
				if($shouldReview) {
					Log::debug('DatabaseDispatchDatasetsInBatchToRadar: should review');
					app(DatabaseTransitionToReview::class)->handle($database,User::find($actorId));
				}
				else {
					Log::debug('DatabaseDispatchDatasetsInBatchToRadar: explicitly *not* reviewing');
				}
			})
			->catch(function (Batch $batch, Throwable $e) use ($database, $actorId, $logContext) {
				//
				// FAILED: Executed if any job in the batch fails permanently
				//
				$actor = User::find($actorId);

				//jw:todo Do we need to send an email here?

				// Empty RADAR dataset
				$radar = new DatabaseRadarDatasetBridge($this->database);
				Log::info('DatabaseDispatchDatasetsInBatchToRadar(): emptying RADAR dataset since batch dataset upload failed!', $logContext);
				if($radar->empty()) {
					Log::error('DatabaseDispatchDatasetsInBatchToRadar(): emptying the RADAR dataset failed!', $logContext);
					throw new \RuntimeException('DatabaseDispatchDatasetsInBatchToRadar(): Emptying the RADAR dataset due to upload exception failed');
				}
			})
			->finally(function (Batch $batch) use ($database, $logContext) {
				// Executed once the batch has finished (success or failure)
				// Good place for cleanup tasks (e.g., deleting temporary records)

				Log::debug('DatabaseDispatchDatasetsInBatchToRadar "finally"', $logContext);
			})
			->dispatch();

		// Log the batch ID immediately upon start
		Log::debug('DatabaseDispatchDatasetsInBatchToRadar(): upload batch dispatched', [
			...$logContext,
			'batch_id' => $batch->id,
			'nChunks' => $nChunks,
			'nJobs' => count($jobs)
		]);
	}
}
