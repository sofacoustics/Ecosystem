<?php

namespace App\Jobs;

use App\Mail\DatabasePersistentPublicationFailed;
use App\Models\Database;
use App\Models\Dataset;
use App\Models\User;

use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

/*
 * "Loader" job of the RADAR upload batch (see DatabaseDispatchDatasetsInBatchToRadar).
 *
 * Dispatching thousands of DatasetUploadInBatchToRadar jobs at once (one Bus::batch() call)
 * takes too long for large databases (e.g. 14 400 datasets exceeded the 3600 s timeout of
 * DatabasePublishInBatchToRadar). Instead, the batch is created with one loader job per piece
 * of datasets, and each loader adds the upload jobs for its piece to the batch.
 *
 * The jobs must be added from within a job of the same batch: while this loader is running,
 * the batch cannot finish, so the 'then' callback (start of the RADAR review) cannot fire
 * before all upload jobs have been added.
 */
class DatasetUploadBatchLoader implements ShouldQueue
{
	use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	public array $datasetIds;
	public Database $database;
	public User $user;

	// Batch::add() runs in a transaction, so a retry does not create duplicate jobs
	public int $tries = 2;
	// Adding 1000 jobs takes about 30 s
	public int $timeout = 900;
	public int $backoff = 30;

	public $logContext;

	/**
	 * @param array $datasetIds IDs of the datasets for which upload jobs will be added to the batch
	 */
	public function __construct(array $datasetIds, Database $database, User $user)
	{
		$this->datasetIds = $datasetIds;
		$this->database = $database;
		$this->user = $user;

		// $this->queue is set in the Bus::batch call
		$this->logContext = [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl'),
			'nDatasets' => count($this->datasetIds),
			'tries' => $this->tries,
			'timeout' => $this->timeout,
		];
	}

	public function handle(): void
	{
		$logContext = [
			...$this->logContext,
			'queue' => $this->queue,
			'batch_id' => $this->batchId,
			'job_id' => $this->job?->getJobId(),
			'method' => __METHOD__,
		];

		if ($this->batch()->cancelled()) {
			Log::debug('Batch cancelled: not adding upload jobs', $logContext);
			return;
		}

		$jobs = [];
		$datasets = Dataset::whereIn('id', $this->datasetIds)->orderBy('id')->get();
		foreach ($datasets as $dataset) {
			// one dataset per upload job (as before)
			$jobs[] = new DatasetUploadInBatchToRadar(new Collection([$dataset]), $this->database, $this->user);
		}

		$this->batch()->add($jobs);

		Log::debug('Added '.count($jobs).' upload jobs to batch '.$this->batchId, [
			...$logContext,
			'first_dataset_id' => $datasets->first()?->id,
			'last_dataset_id' => $datasets->last()?->id,
		]);
	}

	/**
	 * Runs if this loader fails after all tries. The batch's 'catch' callback cancels
	 * the batch and empties the RADAR dataset; here we reset the status and notify.
	 */
	public function failed(Throwable $exception)
	{
		$logContext = [
			...$this->logContext,
			'queue' => $this->queue,
			'batch_id' => $this->batchId,
			'method' => __METHOD__,
		];
		Log::error('DatasetUploadBatchLoader job failed: '.$exception->getMessage(), $logContext);

		$this->database->refresh();
		// only reset if publishing is still in progress (2), never in review (3) or published (4)
		if($this->database->radar_status == 2) {
			Log::debug('Resetting radar_status for database '.$this->database->id.' from 2 to 1', $logContext);
			$this->database->radar_status = 1;
			$this->database->save();
		}
		else {
			Log::info('radar_status for database '.$this->database->id.' is '.$this->database->radar_status.', not 2: not resetting', $logContext);
		}

		Mail::to($this->database->user->email)->queue(new DatabasePersistentPublicationFailed($this->database, $exception, false, actor: $this->user));
		Mail::to(config('mail.to.admins'))->queue(new DatabasePersistentPublicationFailed($this->database, $exception, true, actor: $this->user));
	}
}
