<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

use App\Actions\DatabaseDispatchDatasetsInBatchToRadar;
use App\Events\DatabasePublishedAwaitingApproval;
use App\Events\DatabasePublishingFailed;
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

	public $tries = 1; // The number of times the queued listener may be attempted.
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
		app('log')->debug('DatabasePublishInBatchToRadar::__construct', [
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
		$start = microtime(true);
		app('log')->info('Starting publishing database in batch to RADAR dataset', [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl'),
			'job_id' => $this->job->getJobId(),
			'attempt' => $this->attempts()
		]);
		
		Mail::to($this->database->user->email)->queue(new DatabasePersistentPublicationRequestedStarted($this->database, actor: $this->user));
		Mail::to(config('mail.to.admins'))->queue(new DatabasePersistentPublicationRequestedStarted($this->database, true, actor: $this->user));

		// 1 Create the RADAR dataset
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->create()) {
			throw new \RuntimeException('DatabasePublishInBatchToRadar(): failed to create RADAR dataset');
		}

		// 2. Upload the datasets in batches
		app(DatabaseDispatchDatasetsInBatchToRadar::class)->handle($this->database, $this->user->id);
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
