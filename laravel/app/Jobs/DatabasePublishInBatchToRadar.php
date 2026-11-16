<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use App\Actions\DatabaseDispatchDatasetsInBatchToRadar;
use App\Events\DatabasePublishedAwaitingApproval;
use App\Events\DatabasePublishingFailed;
use App\Exceptions\ReferenceException;
use App\Mail\DatabasePersistentPublicationFailed;
use App\Mail\DatabasePersistentPublicationRequested;
use App\Mail\DatabasePersistentPublicationRequestedStarted;
use App\Models\Database;
use App\Models\User;
use App\Services\DatabaseRadarDatasetBridge;

use Throwable;

class DatabasePublishInBatchToRadar implements ShouldQueue
{
	use Queueable;
	use InteractsWithQueue; // for release() function

	public $tries = 2; // The number of times the queued listener may be attempted.
	//public $backoff = 10; // The number of seconds to wait before retrying the queued listener. jw:note This appears to be ignored!
	public $maxExceptions = 3; // The maximum number of unhandled exceptions to allow before failing.

	public function backoff() : array
	{
		return [30, 120, 600];
	}
    /**
     * Create a new job instance.
     */
	public function __construct(
		public Database $database,
		public User $user,
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
		Log::debug('Constructing DatabasePublishInBatchToRadar job', [
			'method' => __METHOD__,
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl'),
			'queue' => $this->queue,
			'tries' => $this->tries,
			'backoff' => $this->backoff(),
			'maxExceptions' => $this->maxExceptions,
			'user_id' => $this->user->id,
			'timeout' => $this->timeout
		]);
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$logContext = [
			'method' => __METHOD__,
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl'),
			'job_id' => $this->job->getJobId(),
			'attempt' => $this->attempts()
		];

		$start = microtime(true);
		Log::info('Starting publishing database in batch to RADAR dataset', $logContext);		
		Mail::to($this->database->user->email)->queue(new DatabasePersistentPublicationRequestedStarted($this->database, actor: $this->user));
		Mail::to(config('mail.to.admins'))->queue(new DatabasePersistentPublicationRequestedStarted($this->database, true, actor: $this->user));

		try {
			// 1 Create the RADAR dataset
			$radar = new DatabaseRadarDatasetBridge($this->database);
			if(!$radar->create()) {
				//jw:tmp commenting out to see if RADAR throws an exception: throw new \RuntimeException('DatabasePublishInBatchToRadar job exception: failed to create RADAR dataset');
			}
			// 2. Upload the datasets in batches
			app(DatabaseDispatchDatasetsInBatchToRadar::class)->handle($this->database, $this->user->id);
		} catch (Throwable $e) {
			$referenceException = new ReferenceException("Publishing database '".$this->database->id."' to RADAR in batch failed due to an exception", 0, $e);
			$reference = $referenceException->getReference();
			// Catches synchronous failures (e.g., database connection down)
			Log::error("Job ". $this->job->getJobId()." failed ($reference)", $logContext);

			// Re-throw the exception so it gets logged correctly.
			// It is then also processed in the failed() function
			throw $referenceException; // Fail the parent job so Laravel registers it as failed
		}
	}

	/**
   * This method is called when the job fails (including timeout failures).
   */
	public function failed(?Throwable $exception) : void
	{
		$logContext = [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl'),
			'job_id' => $this->job->getJobId()
		];
		// Check if the failure is due to a timeout by inspecting the exception message
		if (str_contains($exception->getMessage(), 'timed out')) {
			Log::alert('Job failed due to timeout: ' . get_class($this) . '. Exception: ' . $exception->getMessage(), $logContext);
		} else {
			Log::error('Job failed: ' . get_class($this) . '. Exception: ' . $exception->getMessage(), $logContext);
		}

		Log::debug("radar_status currently ".$this->database->radar_status, $logContext);
		// reset radar_status back to '1' so the user can try again
		if($this->database->radar_status = 2) {
			Log::debug('Resetting radar_status for database ' . $this->database->id . ' from ' . $this->database->radar_status . ' to 1');
			$this->database->radar_status = 1;
			$this->database->save();
		}

	  Mail::to($this->database->user->email)->queue(new DatabasePersistentPublicationFailed($this->database, $exception, false, actor: $this->user));
	  Mail::to(config('mail.to.admins'))->queue(new DatabasePersistentPublicationFailed($this->database, $exception, true, actor: $this->user));

		// fire event saying database has *failed* to published!
		DatabasePublishingFailed::dispatch($this->database->id);
	}
}
