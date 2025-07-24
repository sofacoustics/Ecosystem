<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

use App\Mail\PersistentPublicationRequested;
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
		$feature = 'database-radar-dataset';
		app('log')->debug('DatabasePublishToRadar::handle()', [
			'feature' => $feature,
			'database_id' => $this->database->id
		]);
		app('log')->info('Starting publishing database to RADAR dataset', [
			'feature' => $feature,
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl')
		]);
		$start = microtime(true);
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->upload())
		{
			app('log')->warning('Publishing failed due to upload errors', [
				'feature' => $feature,
				'database_id' => $this->database->id,
				'target_url' => config('services.radar.baseurl'),
				'duration' => microtime(true) - $start
			]);
		}
		else
		{
			sleep(5); // go to sleep for a little until RADAR is ready to start review
			//jw:todo find a better solution than sleep
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
				Mail::to(explode(',',$adminEmails))->send(new PersistentPublicationRequested($this->database));
				app('log')->info('Persistent publication requested', [
					'feature' => $feature,
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'emails' => $adminEmails
				]);
				$this->radar_status = $this->database->radar_status;
				app('log')->info('Database now published to RADAR', [
					'feature' => $feature,
					'database_id' => $this->database->id,
					'target_url' => config('services.radar.baseurl'),
					'duration' => microtime(true) - $start
				]);
			}
		}
 }
}
