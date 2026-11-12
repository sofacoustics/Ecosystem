<?php

namespace App\Jobs;

use App\Models\Dataset;
use App\Services\DatasetRadarFolderBridge;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class DatasetUploadInBatchToRadar implements ShouldQueue
{
	use Queueable;
	use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

  //jw:todo set timeout to something higher and tries and maxexceptions back to 3 and 4 respectively??

	// Add this property to define how many times the job can attempt execution
	public int $tries = 2; 
	// Optional: You can also define maxExceptions or timeout here if needed
	public int $timeout = 3600;
	// Add this property to define the backoff delay in seconds between retries
  public int $backoff = 30;
	public $maxExceptions = 2; // The maximum number of unhandled exceptions to allow before failing.

	/**
	 * Create a new job instance.
	 */
	public function __construct(public array $datasetIds)
	{
		// $this->queue = 'uploads'; // this is set in the Bus::batch call
	}

  public function backoff(): array
	{
		return [30, 60, 120]; // 30s, then 60s, then 120s
	}

	/**
	 * Execute the job.
	 */
	public function handle(): void
	{
		$logContext = [
			'feature' => 'database-radar-dataset',
			'target_url' => config('services.radar.baseurl'),
			'queue' => $this->queue,
			'batch_id' => $this->batchId,
			'job_id' => $this->job->getJobId(),
			'tries' => $this->tries,
			'backoff' => $this->backoff(),
			'maxExceptions' => $this->maxExceptions,
			'timeout' => $this->timeout
		];

		Log::debug("DatasetUploadInBatchToRadar::handle(): Processing job {$this->job->getJobId()} in batch {$this->batchId}", $logContext);

		if ($this->batch()->cancelled()) {
			return;
		}

		// Fetch the datasets for this specific batch from the database
		$datasets = Dataset::whereIn('id', $this->datasetIds)->get();

		sleep(5);

		try {
			foreach ($datasets as $dataset) {
				// Perform your file upload logic for each dataset here...
				Log::debug("DatasetUploadInBatchToRadar::try(): Processing dataset $dataset->id in job {$this->job->getJobId()} in batch {$this->batchId}", $logContext);

				$radar = new DatasetRadarFolderBridge($dataset);
				if(!$radar->upload()) {
					// failed to upload an Ecosystem dataset
					$this->message = $radar->message;
					$this->details = $radar->details;
					Log::error('Failed to upload dataset to radar', $logContext);
					//jw:tmp commenting out to see if RADAR throws an exception: throw new \Exception("DatasetUploadInBatchToRadar::try(): uploading the dataset $dataset->id failed ($radar->message ($radar->details))");
				}
			}
    } catch (\Throwable $e) {
			Log::notice("DatasetUploadInBatchToRadar::catch(): Upload failed (".$this->attempts()."/".$this->tries." attempts) ", $logContext);

			// Re-throw so Laravel records the job failure
			throw $e;
    }
	}

	/**
	 * 2. HANDLE HERE: This runs if this specific job fails after max tries
	 */
	public function failed(Throwable $exception)
	{
			Log::error("DatasetUploadInBatchToRadar::failed(): Job failed due to unexpected error. See exception log");
			// Log the failure and perform any database status updates for this specific item
			//jw:note We don't need this exception logging here, since the exception is also logged for app/Exceptions/Handler.php
			//Log::error("DatasetUploadInBatchToRadar::failed(): Job failed due to unexpected error: " . $exception->getMessage());

			//jw:todo do we need to set radar_status here or do we do this in the batch job 'catch' or 'finally' section?
	}
}
