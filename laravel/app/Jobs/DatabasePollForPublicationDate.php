<?php

namespace App\Jobs;

use App\Models\Database;
use App\Services\DatabaseRadarDatasetBridge;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue; // for release() function

class DatabasePollForPublicationDate implements ShouldQueue
{
	use Queueable;
	use InteractsWithQueue; // for release() function

	public $tries = 5; // this overrides the supervisorctl queue settings

    /**
     * Create a new job instance.
     */
	public function __construct(
		public Database $database
	) 
	{
		app('log')->debug('DatabasePollForPublicationDate::__construct()', [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'radar_id' => $this->database->radar_id
		]);
	}

    /**
     * Execute the job.
     */
    public function handle(): void
	{
		app('log')->debug('DatabasePollForPublicationDate::handle()', [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'radar_id' => $this->database->radar_id
		]);


		if($this->database->radar_id && $this->database->radar_status == 4)
		{
			$radar = new DatabaseRadarDatasetBridge($this->database);
			if($radar->read())
			{
				$publication_date = $radar->radar_dataset->descriptiveMetadata->publicationYear;
				if(!$publication_date)
				{
					app('log')->info('RADAR publication date not yet available', [
						'feature' => 'database-radar-dataset',
						'database_id' => $this->database->id,
						'radar_id' => $this->database->radar_id
					]);
					// do dispatch job again.
					if ($this->attempts() < $this->tries) {
						// Re-queue the job for later processing
						app('log')->debug('Requeuing DatabasePollForPublication job', [
							'feature' => 'database-radar-dataset',
							'database_id' => $this->database->id,
							'radar_id' => $this->database->radar_id
						]);
						$this->release(1); // delay in seconds
						return;
					}
					// Max attempts reached; handle failure or throw exception
					app('log')->error('Failed to retrieve publication date: max retries reached!', [
						'feature' => 'database-radar-dataset',
						'database_id' => $this->database->id,
						'radar_id' => $this->database->radar_id
					]);
				}
				else
				{
					app('log')->info('Retrieved RADAR publication date', [
						'feature' => 'database-radar-dataset',
						'database_id' => $this->database->id,
						'radar_id' => $this->database->radar_id,
						'publication_date' => $publication_date
					]);
					$this->database->publicationyear = $publication_date;
					$this->database->save();
				}					
			}
			else
			{
				app('log')->error('RADAR read() failed', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->database->id,
					'radar_id' => $this->database->radar_id
				]);
			}
		}
		else
		{
			app('log')->debug('Won\'t retrieve publication date if radar_id is null or radar_status != 4', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'radar_id' => $this->database->radar_id,
				'radar_status' => $this->database->radar_status
			]);
		}
	}
}
