<?php

namespace App\Actions;

use App\Events\DatabasePublishedAwaitingApproval;
use App\Events\DatabasePublishingFailed;
use App\Mail\DatabasePersistentPublicationFailed;
use App\Mail\DatabasePersistentPublicationRequested;
use App\Mail\DatabasePersistentPublicationRequestedStarted;
use App\Models\Database;
use App\Models\User;
use App\Services\DatabaseRadarDatasetBridge;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/*
 * You can include this in a function using:
 *
 *    app(DatabaseTransitionToReview::class)($database, $actor);.
 *    app(\App\Actions\DatabaseTransitionToReview::class)($database, $actor);.
 *
 * This means that we can use the same code in, e.g., our Livewire Datathek Info page and in our DatabasePublishInBatchToRadar job
 *
 * Will throw an exception on any errors
 *
 */
class DatabaseTransitionToReview
{
	/**
	 * Handle the incoming action logic.
	 */
	public function handle(Database $database, User $actor, ?string $batchId = null): void
	{
		Log::debug('DatabaseTransitionToReview::handle()');

		// RADAR may still be processing freshly uploaded (large) files and then rejects
		// 'startreview' with a 422 ("You cannot perform this action on the dataset.").
		// Wait before the first attempt and retry with increasing delays (~8.5 min in total).
		// Note: RadarBridge::post() throws on 4xx/5xx, so exceptions are treated as "not ready yet".
		$delays = [10, 30, 60, 120, 300];
		$success = false;
		$radar = null;
		$lastException = null;
		$logContext = [
			'feature' => 'database-radar-dataset',
			'database_id' => $database->id,
			'radar_id' => $database->radar_id,
			'target_url' => config('services.radar.baseurl'),
			'batch_id' => $batchId,
		];

		// attempt to start review. Try a couple of times
		foreach ($delays as $index => $delay) {
			$attempt = $index + 1;
			Log::debug("DatabaseTransitionToReview: waiting {$delay} seconds before attempt {$attempt}/".count($delays)." to start the RADAR review", $logContext);
			sleep($delay);

			try {
				$radar = new DatabaseRadarDatasetBridge($database);
				if ($radar->startreview()) {
					$success = true;
					break;
				}
				Log::notice("DatabaseTransitionToReview: starting RADAR review failed on attempt {$attempt}", [
					...$logContext,
					'message' => $radar->message,
					'details' => $radar->details,
				]);
			} catch (\Throwable $e) {
				$lastException = $e;
				Log::notice("DatabaseTransitionToReview: starting RADAR review threw an exception on attempt {$attempt}", [
					...$logContext,
					'exception' => $e->getMessage(),
				]);
			}
		}

		if (!$success) {
			throw new \RuntimeException(
				'DatabaseTransitionToReview: Failed to start RADAR review after '.count($delays).' attempts: '
					.($lastException?->getMessage() ?? (($radar->message ?? 'Unknown error').' ('.($radar->details ?? '').')')),
				0,
				$lastException
			);
		}

		$database->radar_status=3; // publishing finished. Wait for approval
		$database->save();
		app('log')->info('Persistent publication requested', [
			'feature' => 'database-radar-dataset',
			'database_id' => $database->id,
			'target_url' => config('services.radar.baseurl'),
			'batch_id' => $batchId, 
		]);
		// send email to user to say their publication has been successfully uploaded and is awaiting approval and inform admins
		Mail::to($database->user->email)->queue(new DatabasePersistentPublicationRequested($database, actor: $actor));
		Mail::to(config('mail.to.admins'))->queue(new DatabasePersistentPublicationRequested($database, true, actor: $actor));
		app('log')->info('Database now published to RADAR and awaiting approval', [
			'feature' => 'database-radar-dataset',
			'database_id' => $database->id,
			'target_url' => config('services.radar.baseurl'),
			'batch_id' => $batchId, 
		]);

		// fire event saying database has been published!
		app('log')->debug('FIRING DatabasePublishedAwaitingApproval Event!!!');
		DatabasePublishedAwaitingApproval::dispatch($database->id);
	}
}
