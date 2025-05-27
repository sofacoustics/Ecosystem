<?php

namespace App\Livewire;

use Livewire\Component;

use App\Http\Controllers\Api\Radar\DatasetController as RadardatasetController;

class DatabaseRadarActions extends Component
{
	public $database;

	// RADAR properties
	public $id;
	public $state;

	public $pending = false;
	public $review = false;


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
			$this->dispatch('appendStatusMessage', 'There is no RADAR dataset associated with this database!');
		}
	}

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
		$this->dispatch('appendStatusMessage', 'Starting RADAR dataset creation process.');
		$radardataset = new RadardatasetController;
		$response = $radardataset->create($this->database);
		if($response->status() != 201)
		{
			$this->dispatch('appendStatusMessage', 'The RADAR dataset could not be created! Error: '.$response->status());
		}
		// get radar_id
		$content = json_decode($response->content(), true);
		$id = $content['id'];
		// save radar_id
		$this->database->radar_id = $id;
		$this->database->save();
		$this->dispatch('appendStatusMessage', 'RADAR dataset creation successful.');
		$this->refreshStatus();
	}

	public function startReview()
	{
		$this->dispatch('appendStatusMessage', 'Starting review process.');
		$radardataset = new RadardatasetController;
		$response = $radardataset->startreview($this->database);
		$this->database->radarstatus = 1;
		$this->dispatch('appendStatusMessage', 'Review process successfully started.');
		$this->refreshStatus();
	}

	public function endReview()
	{
		$this->dispatch('appendStatusMessage', 'Ending review process.');
		$radardataset = new RadardatasetController;
		$response = $radardataset->endreview($this->database);
		$this->dispatch('appendStatusMessage', 'Review process successfully ended.');
		$this->refreshStatus();
	}

	private function refreshStatus()
	{
		$radardataset = new RadardatasetController;
		$response = $radardataset->read($this->database);
		if($response->status() != 200)
		{
			$this->error = "Failed to retrieve RADAR status: $response->statustext() ($response->status()))";
			$this->dispatch('appendStatusMessage', "Failed to retrieve RADAR status: $response->statustext() ($response->status()))");
		}
		$content = json_decode($response->content(), true);
		if(isset($content['id']))
			$this->id = $content['id'];
		if(isset($content['state']))
			$this->setState($content['state']);
	}

    public function render()
    {
        return view('livewire.database-radar-actions');
    }
}
