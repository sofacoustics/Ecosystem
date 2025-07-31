<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Events\DatabasePublishedAwaitingApproval;
use App\Events\DatabasePublishingFailed;
use App\Mail\DatabasePersistentPublicationRequested;
use App\Models\Database;
use App\Services\DatabaseRadarDatasetBridge;

use Throwable;

class DatabasePublishToRadar implements ShouldQueue
{
	use Queueable;
	use InteractsWithQueue; // for release() function

	public $tries = 10; // The number of times the queued listener may be attempted.
	//public $backoff = 10; // The number of seconds to wait before retrying the queued listener. jw:note This appears to be ignored!
	public $maxExceptions = 9; // The maximum number of unhandled exceptions to allow before failing.


	public function backoff() : int
	{
		return 2; //jw:note appears to be ignored :-( retry_after respected
	}
    /**
     * Create a new job instance.
     */
	public function __construct(
		public Database $database
	)
	{
		$this->queue = 'uploads';
		// 1 hour
		// Note that the
		//  php artisan queue:work --timeout should be greater than this
		//  php artisan queue:work --max-time should be greater than --timeout
		//  supervisor stopwaitsecs should be greater than $timeout
		//  The config/queue.php retry_after should be greater than all of them (although my tests indicate that this isn't correct! It can be much shorter)
		$this->timeout = 3600;
		app('log')->debug('DatabasePublishToRadar::__construct', [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl'),
			'queue' => $this->queue,
			'tries' => $this->tries,
			'backoff' => $this->backoff(),
			'maxExceptions' => $this->maxExceptions,
			'timeout' => $this->timeout
		]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
	{
		$start = microtime(true);
		app('log')->info('Starting publishing database to RADAR dataset', [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl'),
			'job_id' => $this->job->getJobId(),
			'attempt' => $this->attempts()
		]);
		
		$this->database->radar_status = 2; // started publishing. Will set to '3' when finished.
		$this->database->save();

		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->upload())
		{
			app('log')->warning('Publishing failed due to upload errors', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'target_url' => config('services.radar.baseurl'),
				'details' => $radar->details,
				'job_id' => $this->job->getJobId(),
				'duration' => microtime(true) - $start
			]);
			// What happens if the upload went wrong, e.g., one of the RADAR element exists?
			// This is an error which the user can't correct.
			// How can we let the user know there was an error?

			// ok - we're going to delete the radar dataset and start again for 'tries'
			app('log')->debug('Deleting database from RADAR so we can recover from a failed upload', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'radar_id' => $this->database->radar_id,
				'target_url' => config('services.radar.baseurl'),
				'details' => $radar->details,
				'job_id' => $this->job->getJobId(),
				'duration' => microtime(true) - $start
			]);
			if($radar->delete())
			{
				if ($this->attempts() < $this->tries) {
					// Re-queue the job for later processing
					app('log')->debug('Releasing job so we can retry', [
						'feature' => 'database-radar-dataset',
						'database_id' => $this->database->id,
						'target_url' => config('services.radar.baseurl'),
						'job_id' => $this->job->getJobId(),
						'tries' => $this->tries,
						'attempts' => $this->attempts(),
						'duration' => microtime(true) - $start
					]);
					$this->release(10); // delay in seconds
					return;
				}
			}

			return;
		}
		else
		{
			// since it has been necessary sometimes, sleeping to give RADAR time to
			// accept review start!!
			sleep(10);
			// after the upload, we can trigger the review
			if(!$radar->startreview())
			{
				$this->error = $radar->message.' RADAR Review Message: '.$radar->details;
				return;
			}
			else
			{
				$this->database->radar_status=3; // publishing finished. Wait for approval
				$this->database->save();
				$adminEmails = config('mail.to.admins');
				// inform admins that they should review the request
				Mail::raw("Dear Ecosystem Admins!\n\nThe persistent publication of the database " . $this->database->id . " has been requested. Please review it and accept or approve.\n\n	" . route('databases.show', $this->database->id), function ($message) {
					$adminEmails = config('mail.to.admins');
					$message->to(explode(',',$adminEmails))
						->subject(config('app.name') . ' Admin: Persistent publication requested');
				});
				app('log')->info('Persistent publication requested', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'job_id' => $this->job->getJobId(),
					'emails' => $adminEmails
				]);
				$this->radar_status = $this->database->radar_status;
				// send email to user to say their publication has been successfully uploaded and is awaiting approval.
				$userEmail = $this->database->user->email;
				Mail::to($userEmail)->queue(new DatabasePersistentPublicationRequested($this->database));
				app('log')->info('User informed of persistent publication request per email', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'email' => $userEmail,
					'job_id' => $this->job->getJobId(),
					'duration' => microtime(true) - $start
				]);
				app('log')->info('Database now published to RADAR and awaiting approval', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'job_id' => $this->job->getJobId(),
					'duration' => microtime(true) - $start
				]);

				// fire event saying database has been published!
				app('log')->debug('FIRING DatabasePublishedAwaitingApproval Event!!!');
				DatabasePublishedAwaitingApproval::dispatch($this->database->id);
			}
		}
	}

	/**
	 *      * This method is called when the job fails (including timeout failures).
	 *           */
	public function failed(?Throwable $exception) : void
	{
        // Check if the failure is due to a timeout by inspecting the exception message
        if (str_contains($exception->getMessage(), 'timed out')) {
			app('log')->alert('Job failed due to timeout: ' . get_class($this) . '. Exception: ' . $exception->getMessage(), [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'target_url' => config('services.radar.baseurl'),
				'job_id' => $this->job->getJobId()
			]);
        } else {
			app('log')->error('Job failed: ' . get_class($this) . '. Exception: ' . $exception->getMessage(), [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'target_url' => config('services.radar.baseurl'),
				'job_id' => $this->job->getJobId()
			]);
		}

		// fire event saying database has *failed* to published!
		DatabasePublishingFailed::dispatch($this->database->id);
	}
}
