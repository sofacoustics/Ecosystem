<?php

namespace App\Livewire;

use Livewire\Component;

use App\Services\DatasetRadarBridge;

class DatabaseRadarActions extends Component
{
	public $database;

	// RADAR properties
	public $id;
	public $state;

	// the RADAR state - used to display/hide buttons
	public $pending = false;
	public $review = false;

	public $error; // any error message to display

	public function mount($database)
	{
		$this->database = $database;
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

	public function createdataset()
	{
		$radar = new DatasetRadarBridge($this->database);
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
		$radar = new DatasetRadarBridge($this->database);
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
		$radar = new DatasetRadarBridge($this->database);
		if($radar->endreview())
			$this->dispatch('radar-status-changed', 'Review process ended'); // let other livewire components know the radar status has changed
		else
			$this->error = $radar->details;
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
	}

	public function delete()
	{
		$this->dispatch('status-message', 'Starting to delete the RADAR dataset');
		$radar = new DatasetRadarBridge($this->database);
		if($radar->delete())
		{
			$this->database->radar_id = null;
			$this->id = null;
			$this->database->save();
			$this->dispatch('radar-status-changed', 'The database has been deleted'); // let other livewire components know the radar status has changed
		}
		else
			$this->error = $radar->details;
		$this->dispatch('status-message', $radar->message);
		$this->refreshStatus();
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
		$radar = new DatasetRadarBridge($this->database);
		if($radar->read())
		{
			$this->id = $radar?->dataset?->id ?? null;
			$this->setState($radar?->dataset?->state ?? '');
		}
		else
		{
			if($this->database->radar_id)
				$this->error = $radar->message;
			$this->id = null;
			$this->setState('');
		}
	}
}
