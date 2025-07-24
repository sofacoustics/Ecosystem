<?php

namespace App\Livewire;

use Livewire\Component;

use App\Jobs\DatabasePublishToRadar;
use App\Services\DatabaseRadarDatasetBridge;

class DatabaseRadarActions extends Component
{
	public $database;

	// RADAR properties
	public $id;
	public $state;
	public $doi;
	public $size;
	public $radar_content;
	public $isExpanded = false; // Initial state of the RADAR content: collapsed

	// the RADAR state - used to display/hide buttons
	public $pending = false;
	public $review = false;
	public $radar_status = null; // this will be set to the value of the database field 'radar_status'.
	public $last_retrieved = null;

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

	public function toggleExpand()
	{
		$this->isExpanded = !$this->isExpanded; // Toggle the boolean value
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
		$this->reset('error');
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
		$this->reset('error');
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
		$this->reset('error');
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
		$this->reset('error');
		$this->dispatch('status-message', 'Starting upload.');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->upload())
			$this->error = $radar->message.' ('.$radar->details.')';
		$this->dispatch('status-message', $radar->message);
	}

	public function publishToRadar()
	{
		$this->reset('error');
		$this->dispatch('status-message', 'Starting upload to RADAR via job.');
		DatabasePublishToRadar::dispatch($this->database);
	}

	/*
	* This will delete the Database from the Datathek and remove the DOI in the Ecosystem
	*/
	public function deleteFromRadar()
	{
		$this->reset('error');
		$this->dispatch('status-message', 'Starting to delete the RADAR dataset');
		$radar = new DatabaseRadarDatasetBridge($this->database);

		// if in review, stop review first
		if($this->database->radar_status == 2)
		{
			// persistent publication has already been requested.
			// Therefore we must first end the review (can't delete a dataset in review)
			if(!$radar->endreview())
			{
				$this->error = $radar->message.' ('.$radar->details.')';
				return;
			}
		}

		if($radar->delete())
		{
			$this->id = null;
			$this->dispatch('radar-status-changed', 'The database has been deleted'); // let other livewire components know the radar status has changed
		}
		else
			$this->error = $radar->details;

		$this->database->radar_id = null;
		$this->database->doi = null;
		$this->database->radar_status = null;
		$this->database->save();
		// set dataset and datafile radar_ids back to null
		foreach($this->database->datasets as $dataset) // iterate through all Dataset of the Database
		{
			$dataset->radar_id = null;
			$dataset->radar_upload_url = null;
			$dataset->save();
			foreach($dataset->datafiles as $datafile)
			{
				$datafile->radar_id = null;
				$datafile->datasetdef_radar_id = null;
				$datafile->datasetdef_radar_upload_url = null;
				$datafile->save();
			}
		}

		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	/* 
	* Approve the persistent publication: Set the Status to "Persistently published".
	*
	* Note: nothing happens at the Datathek currently.
	*/
	public function approvePersistentPublication()
	{
		$this->reset('error');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->publish())
			$this->dispatch('radar-status-changed', 'Database published'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details; // output error message
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	/*
	* Reject the persistent publication: End the review at Datathek and set the Status to "DOI assigned"
	*/
	public function rejectPersistentPublication()
	{
		$this->reset('error');
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if(!$radar->endreview())
		{
			$this->error = 'End Review: '.$radar->message . ' ('.$radar->details .')';
		}
		// set status to "DOI assigned"
		$this->database->radar_status = 1;
		$this->database->save();

		$this->refreshStatus();
	}

	/*
	* Remove the DOI from the Ecosystem and all links to the Datathek. But it does nothing in the Datathek.
	*/
	public function resetDOI()
	{	
		$this->reset('error');
		$this->database->radar_id = null;
		$this->database->doi = null;
		$this->database->radar_status = null;
		$this->database->save();
			// set dataset and datafile radar_ids back to null
		foreach($this->database->datasets as $dataset) // iterate through all Dataset of the Database
		{
			$dataset->radar_id = null;
			$dataset->radar_upload_url = null;
			$dataset->save();
			foreach($dataset->datafiles as $datafile)
			{
				$datafile->radar_id = null;
				$datafile->datasetdef_radar_id = null;
				$datafile->datasetdef_radar_upload_url = null;
				$datafile->save();
			}
		}

		$this->refreshStatus();
	}

	public function render()
	{
		return view('livewire.database-radar-actions');
	}

	public function refreshStatus()
    {
		$radar = new DatabaseRadarDatasetBridge($this->database);
		if($radar->read())
		{
			$this->id = $radar?->radar_dataset?->id ?? null;
			$this->state = $radar->radar_dataset->state;
			$this->doi = $radar?->radar_dataset?->descriptiveMetadata?->identifier?->value ?? null;
			$this->size = $radar?->radar_dataset?->technicalMetadata?->size ?? "unknown";
			$this->radar_content = $radar->radar_content;
			$this->canUpload = true;
			$this->setState($radar?->radar_dataset?->state ?? '');
			$this->last_retrieved = now();
		}
		else
		{
			if($this->database->radar_id)
				$this->error = $radar->message;
			$this->id = null;
			$this->state = null;
			$this->doi = null;
			$this->size = null;
			$this->radar_content = null;
			$this->canUpload = false;
			$this->setState('');
		}
		$this->radar_status = $this->database->radar_status;
	}
}


