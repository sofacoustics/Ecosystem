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

class DatasetUploadInBatchToRadar implements ShouldQueue
{
	use Queueable;
	use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	// Add this property to define how many times the job can attempt execution
	public int $tries = 3; 
	// Optional: You can also define maxExceptions or timeout here if needed
	public int $timeout = 120;
	// Add this property to define the backoff delay in seconds between retries
  public int $backoff = 30;
	public $maxExceptions = 4; // The maximum number of unhandled exceptions to allow before failing.

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
		Log::debug('DatasetUploadBatchToRadar::handle', [
			'feature' => 'database-radar-dataset',
			'target_url' => config('services.radar.baseurl'),
			'queue' => $this->queue,
			'job_id' => $this->job->getJobId(),
			'tries' => $this->tries,
			'backoff' => $this->backoff(),
			'maxExceptions' => $this->maxExceptions,
			'timeout' => $this->timeout
		]);

		Log::debug("Processing job {$this->job->getJobId()} in batch {$this->batchId}");

		if ($this->batch()->cancelled()) {
			return;
		}

		// Fetch the datasets for this specific batch from the database
		$datasets = Dataset::whereIn('id', $this->datasetIds)->get();

		sleep(5);
		

		foreach ($datasets as $dataset) {
			// Perform your file upload logic for each dataset here...
			Log::debug("Processing dataset $dataset->id in job {$this->job->getJobId()} in batch {$this->batchId}");

			$radar = new DatasetRadarFolderBridge($dataset);
			if(!$radar->upload())
			{
				// failed to upload an Ecosystem dataset
				$this->message = $radar->message;
				$this->details = $radar->details;
				Log::error('Failed to upload dataset to radar', [
					'feature' => 'database-radar-dataset',
					'target_url' => config('services.radar.baseurl'),
					'queue' => $this->queue,
					'batch_id' => $this->batchId,
					'job_id' => $this->job->getJobId(),
					'tries' => $this->tries,
					'backoff' => $this->backoff(),
					'maxExceptions' => $this->maxExceptions,
					'timeout' => $this->timeout
				]);
				break;
			}
		}
	}
}
