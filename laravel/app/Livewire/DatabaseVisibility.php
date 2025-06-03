<?php

namespace App\Livewire;

use App\Models\Database;

use App\Http\Resources\DatabaseResource;
use App\Services\DatasetRadarBridge;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

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

		$radar = new DatasetRadarBridge($this->database);
			// create RADAR dataset
		if(!$this->database->radar_id)
		{
			if($radar->create())
			{
				$this->dispatch('radar-status-changed', 'Dataset created'); // let other livewire components know the radar status has changed
				$this->dispatch('status-message', $radar->message);
			}
			else
			{
				$this->error = $radar->details;
				$this->dispatch('status-message', $radar->message);
				return;
			}
		}
		// validate metadata
		else if(!$radar->metadataValidate())
		{
			$this->dispatch('radar-status-changed', 'Validation failed'); 
			$this->error = $radar->message.'('.$radar->details.')';
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
					$this->error = $radar->message.'('.$radar->details.')';
					return;
				}
			}
		}
		else
		{
			$this->error = $radar->message.'('.$radar->details.')';
			return;
		}
		// retrieve doi
		if($radar->doi())
		{
			//$this->status = $radar->message; // use status-message instead
			$this->doi = $this->database->doi; //jw:todo will this really retrieve the correct value!
			$this->dispatch('status-message', $radar->message);
		}
		else
		{
			$this->error = $radar->message.'('.$radar->details.')';
		}
		// stop review process
		if(!$radar->endreview())
			$this->error = $radar->details;
	}

	public function submitToPublish()
	{
		$this->database->radarstatus=2;
		$this->database->save();
		$this->radarstatus = $this->database->radarstatus;
		$this->js('window.location.reload()');
	}

	public function approve() // Emulate the curator approving the publication at the Datathek
	{
		$this->database->radarstatus=3;
		$this->database->save();
		$this->radarstatus = $this->database->radarstatus;
	}

	public function resetDOI()
	{
		$this->database->radarstatus=null;
		$this->database->doi = null;
		$this->database->radar_id = null;
		$this->database->save();
		$this->doi = $this->database->doi;
		$this->radarstatus = $this->database->radarstatus;
		$this->js('window.location.reload()'); 
	}
	public function render()
	{
		return view('livewire.database-visibility');
	}
}
