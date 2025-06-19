<?php

namespace App\Livewire;

use Livewire\Component;

use App\Services\DatabaseRadarDatasetBridge;

class DatabaseRadarActions extends Component
{
	public $database;

	// RADAR properties
	public $id;
	public $state;

	// the RADAR state - used to display/hide buttons
	public $pending = false;
	public $review = false;
	public $radar_status = 0; // this will be set to the value of the database field 'radar_status'.

	// true if we haven't uploaded yet
	public $canUpload = false;

	public $error; // any error message to display

	public function mount($database)
	{
		$this->database = $database;
		$this->radar_status = $database->radar_status;
		if($database->radar_id)
		{
			$this->id = $database->radar_id;

			$this->refreshStatus();
		}
		else
		{
			$this->dispatch('status-message', 'There is no RADAR dataset associated with this database!');
		}
	}

	// set RADAR state (pending, review, published)
	public function setState($state)
	{
		$this->state = $state;
		if($state == 'PENDING')
			$this->pending = true;
		else
			$this->pending = false;
		if($state == 'REVIEW')
			$this->review = true;
		else
			$this->review = false;
	}

	public function createDataset()
	{
		$radar = new DatabaseRadarDatasetBridge($this->database);
		$this->dispatch('status-message', 'Starting RADAR dataset creation process.');
        if($radar->create())
            $this->dispatch('radar-status-changed', 'Dataset created'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details;
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function startReview()
	{
		$this->dispatch('status-message', 'Starting review process.');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->startreview())
			$this->dispatch('radar-status-changed', 'Review process started'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details; // output error message
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function endReview()
	{
		$this->dispatch('status-message', 'Ending review process.');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->endreview())
			$this->dispatch('radar-status-changed', 'Review process ended'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details;
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function uploadToRadar() // note that the 'upload()' function is reserved in Livewire
	{
		$this->error = "";
		$this->dispatch('status-message', 'Starting upload.');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->upload())
			$this->error = $radar->message.' ('.$radar->details.')';
		$this->dispatch('status-message', $radar->message);
	}

	public function deleteFromRadar()
	{
		$this->dispatch('status-message', 'Starting to delete the RADAR dataset');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->delete())
		{
			$this->id = null;
			$this->dispatch('radar-status-changed', 'The database has been deleted'); // let other livewire components know the radar status has changed
		}
		else
			$this->error = $radar->details;
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function resetDOI()
	{
		$radar = new DatabaseRadarDatasetBridge($this->database);
		// do we have a link to RADAR?
		if(!$radar->resetPersistentPublication())
		{
			$this->error = $radar->message . ' ('.$radar->details;
		}
		else
			$this->dispatch('status-message', $radar->message);
		
		$this->refreshStatus();
		$this->js('window.location.reload()'); 

	}

	public function approvePublication() // Emulate the curator approving the publication at the Datathek
	{
		$radar = new DatabaseRadarDatasetBridge($this->database);
		// do we have a link to RADAR?
		if(!$radar->approvePersistentPublication())
		{
			$this->error = $radar->message . ' ('.$radar->details;
		}
		else
			$this->dispatch('status-message', $radar->message);
		$this->js('window.location.reload()'); 
	}

	public function render()
	{
		return view('livewire.database-radar-actions');
	}

	////////////////////////////////////////////////////////////////////////////////
	// Private
	////////////////////////////////////////////////////////////////////////////////

	private function refreshStatus()
    {
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->read())
		{
			$this->id = $radar?->radar_dataset?->id ?? null;
			$this->canUpload = true;
			$this->setState($radar?->radar_dataset?->state ?? '');
		}
		else
		{
			if($this->database->radar_id)
				$this->error = $radar->message;
			$this->id = null;
			$this->canUpload = false;
			$this->setState('');
		}
		$this->radar_status = $this->database->radar_status;
	}
}
