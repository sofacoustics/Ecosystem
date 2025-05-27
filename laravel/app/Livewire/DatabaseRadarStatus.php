<?php

namespace App\Livewire;

use Livewire\Component;

use App\Http\Controllers\Api\Radar\DatasetController as RadardatasetController;

/*
 * Get RADAR status for a database and add information
 *
 * Sends status messages to Livewire component StatusMessages
 */
class DatabaseRadarStatus extends Component
{
	private $database;

	// RADAR properties
	public $id;
	public $state;
	public $doi;
	public $size;

	public $error;

	public function mount($database)
	{
		$this->database = $database;
		if($database->radar_id)
		{
			$radardataset = new RadardatasetController;
			$response = $radardataset->read($database);
			if($response->status() != 200)
			{
				$this->error = "Failed to retrieve RADAR status: $response->statustext() ($response->status()))";
				$this->dispatch('appendStatusMessage', "Failed to retrieve RADAR status: $response->statustext() ($response->status()))");
			}
			$content = json_decode($response->content(), true);
			if(isset($content['id']))
				$this->id = $content['id'];
			if(isset($content['state']))
				$this->state = $content['state'];
			if(isset($content['descriptiveMetadata']['identifier']['value']))
				$this->doi = $content['descriptiveMetadata']['identifier']['value'];
			if(isset($content['technicalMetadata']['size']))
				$this->size = $content['technicalMetadata']['size'];

			$this->dispatch('appendStatusMessage', 'Successfully retrieved RADAR status from the RADAR server');
		}
		else
		{
			$this->dispatch('appendStatusMessage', 'There is not RADAR dataset associated with this database!');
		}
	}


    public function render()
    {
        return view('livewire.database-radar-status');
    }
}
