<?php

namespace App\Jobs;

use App\Models\Database;
use App\Models\Dataset;
use App\Models\User;
use App\Services\DatasetRadarFolderBridge;
use App\Services\RadarBridge;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/*
 * Empty the RADAR dataset of a database in the background: delete the RADAR folder of each
 * dataset (DOI and metadata are kept).
 *
 * RADAR has no endpoint to delete all folders at once, so this needs one request per dataset
 * (about 1 s each), i.e. hours for large databases. Therefore:
 * - one RADAR token for all requests (RadarBridge::shareToken), a new one after a 401
 * - after RUN_SECONDS, the job dispatches itself again to continue after the last dataset
 * - 404 (folder already gone) counts as deleted
 * - 422 (RADAR locks the dataset during an ingest/upload): continue later, up to LOCK_WAIT_MAX_SECONDS
 * - other errors are counted and reported at the end, the job continues with the next dataset
 *
 * Runs on the 'uploads' queue, i.e. after the upload jobs queued before it.
 */
class DatabaseEmptyRadar implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	const RUN_SECONDS = 3000;            // continue in a new job after this time (job timeout 3600 s)
	const LOCK_RETRY_SECONDS = 60;       // wait time when RADAR is locked by an ingest/upload
	const LOCK_WAIT_MAX_SECONDS = 21600; // give up after 6 h of waiting for the lock
	const PROGRESS_LOG_EVERY = 100;      // log the progress every n datasets
	const MAX_FAILURES_LISTED = 50;      // number of failures listed in the summary

	public int $timeout = 3600;
	public int $tries = 2;
	public int $backoff = 60;

	/**
	 * @param int   $afterId   continue with datasets with an id > $afterId
	 * @param array $summary   counters carried over from the previous run
	 * @param ?int  $startedAt time when emptying was started (first job)
	 */
	public function __construct(
		public Database $database,
		public ?User $actor = null,
		public int $afterId = 0,
		public array $summary = [],
		public ?int $startedAt = null,
	)
	{
		// Strip loaded relations, otherwise they are serialized with the job and reloaded on every restore
		$this->database = $database->withoutRelations();
		$this->queue = 'uploads';
		$this->startedAt ??= time();
		$this->summary += [
			'deleted' => 0,
			'already_gone' => 0,
			'failed' => 0,
			'lock_waits' => 0,
			'runs' => 0,
			'failures' => [],
		];
	}

	public function handle(): void
	{
		$runStart = time();
		$this->summary['runs']++;
		$logContext = $this->logContext(__METHOD__);

		$remaining = Dataset::where('database_id', $this->database->id)->whereNotNull('radar_id')->where('id', '>', $this->afterId)->count();
		Log::info('Emptying RADAR dataset: '.$remaining.' folder(s) to delete in this run', [...$logContext, 'summary' => $this->summaryCounts()]);

		RadarBridge::shareToken(true);
		try {
			$nProcessed = 0;
			$datasets = Dataset::where('database_id', $this->database->id)
				->whereNotNull('radar_id')
				->where('id', '>', $this->afterId)
				->lazyById(100);
			foreach($datasets as $dataset) {
				if(time() - $runStart > self::RUN_SECONDS) {
					$this->continueLater(0, 'run time limit reached');
					return;
				}

				$radar = new DatasetRadarFolderBridge($dataset);
				$ok = $radar->delete();
				if(!$ok && $radar->status == 401) {
					// token expired: get a new one and try once more
					RadarBridge::forgetSharedToken();
					$radar = new DatasetRadarFolderBridge($dataset);
					$ok = $radar->delete();
				}

				if($ok) {
					if($radar->status == 404)
						$this->summary['already_gone']++;
					else
						$this->summary['deleted']++;
				}
				elseif($radar->status == 422) {
					// RADAR locks the dataset during an ingest/upload: try this dataset again later
					if(time() - $this->startedAt > self::LOCK_WAIT_MAX_SECONDS) {
						$this->recordFailure($dataset, $radar);
						Log::error('Emptying RADAR dataset: giving up, RADAR has been locked for too long', [...$logContext, 'dataset_id' => $dataset->id]);
						$this->finish();
						return;
					}
					$this->summary['lock_waits']++;
					$this->continueLater(self::LOCK_RETRY_SECONDS, 'RADAR dataset locked (422): '.$radar->details);
					return;
				}
				else {
					$this->recordFailure($dataset, $radar);
				}

				$this->afterId = $dataset->id;
				$nProcessed++;
				if($nProcessed % self::PROGRESS_LOG_EVERY == 0) {
					Log::info('Emptying RADAR dataset: progress', [...$logContext, 'after_id' => $this->afterId, 'summary' => $this->summaryCounts()]);
				}
			}
			$this->finish();
		} finally {
			// queue workers are long-running: don't leave token sharing switched on
			RadarBridge::shareToken(false);
		}
	}

	public function failed(Throwable $exception): void
	{
		Log::error('Emptying RADAR dataset failed: '.$exception->getMessage(), [
			...$this->logContext(__METHOD__),
			'after_id' => $this->afterId,
			'summary' => $this->summaryCounts(),
		]);
	}

	/*
	 * Dispatch a new job to continue after the last processed dataset
	 */
	private function continueLater(int $delaySeconds, string $reason): void
	{
		Log::info('Emptying RADAR dataset: continuing in a new job ('.$reason.')', [
			...$this->logContext(__METHOD__),
			'after_id' => $this->afterId,
			'delay' => $delaySeconds,
			'summary' => $this->summaryCounts(),
		]);
		self::dispatch($this->database, $this->actor, $this->afterId, $this->summary, $this->startedAt)
			->delay(now()->addSeconds($delaySeconds));
	}

	private function finish(): void
	{
		$remaining = Dataset::where('database_id', $this->database->id)->whereNotNull('radar_id')->count();
		$context = [
			...$this->logContext(__METHOD__),
			'summary' => $this->summaryCounts(),
			'failures' => $this->summary['failures'],
			'folders_remaining' => $remaining,
			'duration' => time() - $this->startedAt,
		];
		if($this->summary['failed'] > 0 || $remaining > 0)
			Log::error('Emptying RADAR dataset finished with errors', $context);
		else
			Log::info('Emptying RADAR dataset finished', $context);
	}

	private function recordFailure(Dataset $dataset, DatasetRadarFolderBridge $radar): void
	{
		$this->summary['failed']++;
		if(count($this->summary['failures']) < self::MAX_FAILURES_LISTED) {
			$this->summary['failures'][] = [
				'dataset_id' => $dataset->id,
				'status' => $radar->status,
				'details' => mb_substr((string) $radar->details, 0, 500),
			];
		}
	}

	private function summaryCounts(): array
	{
		$counts = $this->summary;
		unset($counts['failures']);
		return $counts;
	}

	private function logContext(string $method): array
	{
		return [
			'method' => $method,
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'radar_id' => $this->database->radar_id,
			'actor_id' => $this->actor?->id,
			'target_url' => config('services.radar.baseurl'),
			'started_at' => $this->startedAt,
		];
	}
}
