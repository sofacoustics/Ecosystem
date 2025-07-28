<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

use App\Mail\DatabasePersistentPublicationRequested;
use App\Models\Database;
use App\Services\DatabaseRadarDatasetBridge;

class DatabasePublishToRadar implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
	public function __construct(
		public Database $database
	)
	{
		$this->queue = 'uploads';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
	{
		app('log')->info('Starting publishing database to RADAR dataset', [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl')
		]);
		$start = microtime(true);
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->upload())
		{
			app('log')->warning('Publishing failed due to upload errors', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'target_url' => config('services.radar.baseurl'),
				'details' => $radar->details,
				'duration' => microtime(true) - $start
			]);
			// What happens if the upload went wrong, e.g., one of the RADAR element exists?
			// This is an error which the user can't correct.
			// How can we let the user know there was an error?
			return;
		}
		else
		{
			// wait until we're in 'PENDING' to continue
			$startTime = time();
			while (time() - $startTime < 60)
			{
				app('log')->debug('In "Waiting for \'PENDING\' status to be reached" loop', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'duration' => microtime(true) - $start
				]);
				if($radar->read())
				{
					if($radar->radar_dataset->state == 'REVIEW')
						break;
					if($radar->radar_dataset->state == 'PENDING')
						break;
				}
				app('log')->debug('Waiting for \'PENDING\' status to be reached', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'duration' => microtime(true) - $start
				]);

				sleep(1);
			}
			// after the upload, we can trigger the review
			if(!$radar->startreview())
			{
				$this->error = $radar->message.' RADAR Review Message: '.$radar->details;
				return;
			}
			else
			{
				$this->database->radar_status=2;
				$this->database->save();
				$adminEmails = config('mail.to.admins');
				Mail::to(explode(',',$adminEmails))->send(new DatabasePersistentPublicationRequested($this->database));
				app('log')->info('Persistent publication requested', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'emails' => $adminEmails
				]);
				$this->radar_status = $this->database->radar_status;
				app('log')->info('Database now published to RADAR and awaiting approval', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'duration' => microtime(true) - $start
				]);
			}
		}
 }
}
