<?php

namespace App\Livewire;

use App\Jobs\DatabasePublishToRadar;
use App\Mail\DatabaseDOIAssigned;
use App\Models\Database;

use App\Http\Resources\DatabaseResource;
use App\Services\DatabaseRadarDatasetBridge;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Livewire\Component;

/*
 * https://neon.tech/guides/laravel-livewire-blog#implementing-the-blog-functionality
 */
class DatabaseVisibility extends Component
{
	public $database;
	public $visible;
	public $doi;
	public $status; // set messages to be viewed in view
	public $warning;
	public $error; // set error messages to be viewed in view
	public $radar_status; // null or 0: nothing happened with RADAR yet; 1: DOI assigned; 2: Requested publication (started) 3: Requested publication (finished), curator notified; 4: Database persistently published.

	protected $rules = [
	];

	public function mount(Database $database)
	{
		if($database) 
		{
			$this->database = $database;
			$this->visible = $database->visible;
			$this->doi = $database->doi;
			if ($database->radar_status == null)
				$this->radar_status = 0;
			else
				$this->radar_status = $database->radar_status;
		}
	}

	public function expose()
	{
		$this->database->visible = true;
		$this->database->save();
		$this->visible = $this->database->visible;
		$this->js('window.location.reload()'); 
	}

	public function hide()
	{
		$this->database->visible = false;
		$this->database->save();
		$this->visible = $this->database->visible;
		$this->js('window.location.reload()'); 
	}

	public function assignDOI()
	{
		$start = microtime(true);
		$this->dispatch('status-message', 'Starting DOI assignment');
		$this->error = "";
		$this->warning = "";

		$radar = new DatabaseRadarDatasetBridge($this->database);
		// create RADAR dataset
		if(!$this->database->radar_id)
		{
			if($radar->create())
			{
				$this->dispatch('radar-status-changed', 'Dataset created'); // let other livewire components know the radar status has changed
				$this->dispatch('status-message', $radar->message);
				$content_created = $radar->content;
			}
			else
			{
				$this->dispatch('status-message', $radar->message);
				$this->error = $radar->message.' RADAR Message: '.$radar->details.' *** Content created: *** '.json_encode($radar->content);
				return;
			}
		}
		// validate metadata
		else if(!$radar->metadataValidate())
		{
			$this->dispatch('radar-status-changed', 'Validation failed');
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
			// delete RADAR dataset if it has been created
			$radar->delete();
			return;
		}
		if($radar->read())
		{
			$state = $radar?->radar_dataset?->state ?? 'INVALID RADAR STATE';
			if($state  == "PENDING")
			{
				// we need to start the review process in order to get the DOI
				if(!$radar->startreview())
				{
					$this->error = $radar->message.' RADAR Message: '.$radar->details;
					return;
				}
			}
		}
		else
		{
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
			return;
		}
		// retrieve doi
		if($radar->getDOI())
		{
			$this->doi = $radar->doi; 
			$this->dispatch('status-message', $radar->message);
		}
		else
		{
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
			return;
		}
		// stop review process
		if(!$radar->endreview())
		{
			$this->error = $radar->details;
			return;
		}

		$this->database->radar_status = 1;
		$this->database->doi = $this->doi;
		$this->database->save();
		app('log')->info('DOI assigned to database', [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'user_id' => auth()->user()->id,
			'target_url' => config('services.radar.baseurl'),
			'duration' => microtime(true) - $start
		]);
		$adminEmails = config('mail.to.admins');
		Mail::to(explode(',',$adminEmails))->queue(new DatabaseDOIAssigned($this->database));
		app('log')->info("Sending DatabaseDOIAssigned email to $adminEmails", [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'user_id' => auth()->user()->id,
			'target_url' => config('services.radar.baseurl'),
			'duration' => microtime(true) - $start
		]);
		$this->radar_status = $this->database->radar_status;
	}

	public function submitToPublish()
	{
		//$this->dispatch('status-message', 'Updating metadata');
		$this->error = "";
		$this->warning = "";

		// sanity check: does RADAR ddataset exist?

		// check that all datasets have the correct number of datafiles
		if(!$this->database->checkForIncompleteDatasets($message))
		{
			$this->warning = "Your dataset contains datasets with missing datafiles. $message";
		}
		$radar = new DatabaseRadarDatasetBridge($this->database);
		$radar->verifyOrRemove(); // check if RADAR dataset exists or clearn up
		if($radar->update())
		{
			$this->dispatch('radar-status-changed', 'Dataset updated'); // let other livewire components know the radar status has changed
			$this->dispatch('status-message', $radar->message);
			$content_created = $radar->content;
		}
		else
		{
			$this->dispatch('status-message', $radar->message);
			$this->error = $radar->message.' RADAR Message: '.$radar->details.' *** Content updated: *** '.json_encode($radar->content);
			return;
		}
			// validate metadata
		if(!$radar->metadataValidate())
		{
			$this->dispatch('radar-status-changed', 'Validation failed');
			$this->error = $radar->message.' RADAR Message: '.$radar->details.' *** Content created: *** '.json_encode($content_created);
			return;
		}

		// upload and review start now in job
		DatabasePublishToRadar::dispatch($this->database);

		return redirect()->route('databases.show', $this->database)->with('success', 'Your database is being published and you will be informed per email when the task has finished');
	}

	public function render()
	{
		return view('livewire.database-visibility');
	}
}
