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

		$delays = [0, 10, 60];
		$success = false;
		$radar = null;

		// attempt to start review. Try a couple of times
		foreach ($delays as $index => $delay) {
			if ($delay > 0) {
				Log::debug("DatabaseTransitionToReview: RADAR not ready on attempt {$index}, waiting {$delay} seconds...", [
					'feature' => 'database-radar-dataset',
					'database_id' => $database->id,
					'target_url' => config('services.radar.baseurl'),
					'batch_id' => $batchId, 
				]);
				sleep($delay);
			}

			$radar = new DatabaseRadarDatasetBridge($database);

			if ($radar->startreview()) {
				$success = true;
				break;
			}
		}

		if (!$success) {
			throw new \RuntimeException(
				'DatabaseTransitionToReview: Failed to start RADAR review: ' . ($radar->message ?? 'Unknown error') . ' (' . ($radar->details ?? '') . ')'
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
