<?php

namespace App\Livewire;

use App\Models\Database;

use App\Http\Resources\DatabaseResource;
use App\Services\DatabaseRadarDatasetBridge;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use Livewire\Component;

/*
 * https://neon.tech/guides/laravel-livewire-blog#implementing-the-blog-functionality
 */
class DatabaseVisibility extends Component
{
	public $database;
	public $xx;
	public $visible;
	public $doi;
	public $status; // set messages to be viewed in view
	public $warning;
	public $error; // set error messages to be viewed in view
	public $radarstatus; // null or 0: nothing happened with RADAR yet; 1: DOI assigned; 2: Requested publication, curator notified; 3: Database persistently published.

	protected $rules = [
	];

	public function mount(Database $database)
	{
		if($database) 
		{
			$this->database = $database;
			$this->visible = $database->visible;
			$this->doi = $database->doi;
			if ($database->radarstatus == null)
				$this->radarstatus = 0;
			else
				$this->radarstatus = $database->radarstatus;
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
			return;
		}
		if($radar->read())
		{
			$state = $radar?->dataset?->state ?? 'INVALID RADAR STATE';
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
		if($radar->doi())
		{
			$this->doi = $this->database->doi;
			$this->dispatch('status-message', $radar->message);
		}
		else
		{
			$this->error = $radar->message.' RADAR Message: '.$radar->details;
		}
		// stop review process
		if(!$radar->endreview())
			$this->error = $radar->details;
		$this->radarstatus = $this->database->radarstatus;
	}

	public function submitToPublish()
	{
		//$this->dispatch('status-message', 'Updating metadata');
		$this->error = "";
		$this->warning = "";

		$radar = new DatabaseRadarDatasetBridge($this->database);
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
		// upload database to the Datathek
		if(!$radar->upload())
		{
			$this->error = $radar->message.' ('.$radar->details.')';
			return;
		}
		else
		{
			$this->dispatch('status-message', $radar->message);
			// Starting the 'review' process directly after uploading was failing.
			// sleeping for 5 seconds appears to fix this. Not a pretty solution though!
			// help when starting the review directly after uploading files, which otherwise fails!
			sleep(5);
		}

		// after the upload, we can trigger the review
		if(!$radar->startreview())
		{
			$this->error = $radar->message.' RADAR Review Message: '.$radar->details;
			return;
		}
		else
		{
			$this->dispatch('status-message', $radar->message);
		}

		$this->database->radarstatus=2;
		$this->database->save();
		Log::info('Changing radarstatus for database '.$this->database->id.' to '.$this->database->radarstatus);
		$this->radarstatus = $this->database->radarstatus;
		$this->dispatch('status-message', 'The database has been successfully published!');
		//$this->js('window.location.reload()'); //jw:note I think this refreshes page removing status messages too early and is unnecessary
	}

	public function render()
	{
		return view('livewire.database-visibility');
	}
}
