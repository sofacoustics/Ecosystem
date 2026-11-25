<?php

namespace App\Actions;

use App\Jobs\DatabaseEmptyRadar;
use App\Jobs\DatabasePublishInBatchToRadar;
use App\Jobs\DatabasePublishToRadar;
use App\Jobs\DatasetUploadBatchLoader;
use App\Jobs\DatasetUploadInBatchToRadar;
use App\Models\Database;
use App\Models\User;
use Illuminate\Bus\BatchRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/*
 * Stop a running "Publish with DOI" of a database (admin function, see DatabaseRadarActions).
 *
 * - deletes queued (not yet running) publish jobs of the database
 * - cancels the open upload batch(es) of the database and deletes their queued jobs.
 *   Deleting the jobs is essential: otherwise the cancelled jobs would complete "successfully",
 *   the batch would finish and its 'then' callback would start the RADAR review.
 * - resets radar_status from 2 to 1
 *
 * The RADAR dataset is not emptied here: RADAR refuses deleting data while an upload is running
 * (422 "Während eines Ingest/Upload können keine Daten gelöscht werden"), and the job running at
 * the moment finishes its current dataset. Use "Empty RADAR dataset" afterwards if needed.
 *
 * Assumes the 'database' queue driver (jobs in the 'jobs' table).
 */
class DatabaseStopPublishingToRadar
{
	public bool $success = false;
	public string $message = '';

	public function handle(Database $database, User $admin): bool
	{
		$logContext = [
			'method' => __METHOD__,
			'feature' => 'database-radar-dataset',
			'database_id' => $database->id,
			'radar_id' => $database->radar_id,
			'admin_id' => $admin->id,
		];
		Log::info('Admin stops publishing to RADAR', $logContext);

		// 1. Publish jobs (parent jobs) of this database
		$publishJobs = $this->findJobsOfDatabase($database, 'uploads', [DatabasePublishInBatchToRadar::class, DatabasePublishToRadar::class]);
		if($publishJobs->whereNotNull('reserved_at')->isNotEmpty()) {
			// The parent job is running, i.e. the batch is being created right now (or
			// DatabasePublishToRadar uploads without batch). Stopping now would miss the batch.
			$this->message = 'Publishing is being prepared right now (the batch is being created). Please try again in a minute.';
			Log::notice($this->message, $logContext);
			return $this->success = false;
		}

		// 2. Batches of this database: by name (new batches) and, as fallback, by the batch IDs
		// of the database's queued upload/loader jobs (batches created without a name)
		$batchIds = DB::table('job_batches')
			->where('name', DatabaseDispatchDatasetsInBatchToRadar::batchName($database))
			->pluck('id')
			->merge($this->findBatchIdsOfDatabase($database))
			->unique()
			->values()
			->all();
		// only the open ones
		$batchIds = DB::table('job_batches')
			->whereIn('id', $batchIds)
			->whereNull('finished_at')
			->whereNull('cancelled_at')
			->pluck('id')
			->all();

		$nPublishJobsDeleted = 0;
		$nBatchJobsDeleted = 0;
		$oldStatus = $database->radar_status;

		DB::transaction(function () use ($database, $publishJobs, $batchIds, &$nPublishJobsDeleted, &$nBatchJobsDeleted) {
			// delete the queued publish jobs
			if($publishJobs->isNotEmpty()) {
				$nPublishJobsDeleted = DB::table('jobs')
					->whereIn('id', $publishJobs->pluck('id'))
					->whereNull('reserved_at')
					->delete();
			}

			// cancel the batches and delete their queued jobs. The repository is used directly
			// instead of Bus::findBatch(), which would unserialize the batch callbacks (and, for
			// old batches, restore the database with all its datasets).
			$repository = app(BatchRepository::class);
			foreach($batchIds as $batchId) {
				$repository->cancel($batchId);
				$nBatchJobsDeleted += DB::table('jobs')
					->where('queue', 'uploads')
					->whereNull('reserved_at')
					->where('payload', 'like', '%'.$batchId.'%')
					->delete();
			}

			// reset the status so that the user can publish again
			$database->refresh();
			if($database->radar_status == 2) {
				$database->radar_status = 1;
				$database->save();
			}
		});

		$this->message = 'Publishing stopped: '
			.$nPublishJobsDeleted.' queued publish job(s) deleted, '
			.count($batchIds).' upload batch(es) cancelled, '
			.$nBatchJobsDeleted.' queued upload job(s) deleted, '
			.'radar_status '.$oldStatus.' -> '.$database->radar_status.'.';
		if(count($batchIds) == 0 && $nPublishJobsDeleted == 0)
			$this->message .= ' No queued publish job and no open upload batch found.';
		else
			$this->message .= ' A job running at the moment finishes its current dataset. When no upload is running any more, use "Empty RADAR dataset" if the uploaded files should be removed.';

		Log::info($this->message, [
			...$logContext,
			'batch_ids' => $batchIds,
			'publish_jobs_deleted' => $nPublishJobsDeleted,
			'batch_jobs_deleted' => $nBatchJobsDeleted,
		]);
		return $this->success = true;
	}

	/*
	 * True if this database has an open (named) upload batch or a queued/running publish job.
	 * Used to show the "Stop publishing" button. Batches without a name (created by old code)
	 * are not detected here, since that would require scanning all upload jobs.
	 */
	public function isPublishing(Database $database): bool
	{
		$openBatch = DB::table('job_batches')
			->where('name', DatabaseDispatchDatasetsInBatchToRadar::batchName($database))
			->whereNull('finished_at')
			->whereNull('cancelled_at')
			->exists();
		if($openBatch)
			return true;
		return $this->findJobsOfDatabase($database, 'uploads', [DatabasePublishInBatchToRadar::class, DatabasePublishToRadar::class])->isNotEmpty();
	}

	/*
	 * True if a DatabaseEmptyRadar job of this database is queued, delayed or running
	 */
	public function isEmptying(Database $database): bool
	{
		return $this->findJobsOfDatabase($database, 'uploads', [DatabaseEmptyRadar::class])->isNotEmpty();
	}

	/*
	 * Batch IDs of the queued or running upload/loader jobs of this database
	 */
	private function findBatchIdsOfDatabase(Database $database): array
	{
		$batchIds = [];
		$this->eachJobOfDatabase($database, 'uploads', [DatasetUploadInBatchToRadar::class, DatasetUploadBatchLoader::class],
			function ($job, $command) use (&$batchIds) {
				if(preg_match('/s:7:"batchId";s:\d+:"([^"]+)";/', $command, $matches))
					$batchIds[$matches[1]] = true;
			});
		return array_keys($batchIds);
	}

	/*
	 * Queued or running jobs of the given classes for this database (id, reserved_at)
	 */
	private function findJobsOfDatabase(Database $database, string $queue, array $classes)
	{
		$jobs = collect();
		$this->eachJobOfDatabase($database, $queue, $classes, function ($job) use ($jobs) {
			$jobs->push((object) ['id' => $job->id, 'reserved_at' => $job->reserved_at]);
		});
		return $jobs;
	}

	/*
	 * Call $callback($job, $command) for each job in $queue of one of the $classes, whose
	 * serialized command contains this database. Processed in chunks, since there may be
	 * thousands of upload jobs.
	 */
	private function eachJobOfDatabase(Database $database, string $queue, array $classes, callable $callback): void
	{
		// serialized model identifier of this database within the job's command
		$pattern = '/s:5:"class";s:\d+:"'.preg_quote(Database::class, '/').'";s:2:"id";(i:'.$database->id.'|s:\d+:"'.$database->id.'");/';

		DB::table('jobs')
			->where('queue', $queue)
			// pre-filter by class name in SQL, so that e.g. looking for the publish job does not
			// decode thousands of upload jobs
			->where(function ($query) use ($classes) {
				foreach($classes as $class)
					$query->orWhere('payload', 'like', '%'.class_basename($class).'%');
			})
			->select(['id', 'reserved_at', 'payload'])
			->lazyById(500)
			->each(function ($job) use ($classes, $pattern, $callback) {
				$payload = json_decode($job->payload, true);
				$command = $payload['data']['command'] ?? '';
				if(in_array($payload['displayName'] ?? null, $classes, true) && preg_match($pattern, $command))
					$callback($job, $command);
			});
	}
}
