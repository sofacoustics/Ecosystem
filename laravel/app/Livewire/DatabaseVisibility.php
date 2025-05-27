<?php

namespace App\Livewire;

use App\Models\Database;

use App\Http\Controllers\Api\Radar\DatasetController as RadardatasetapiController;
use App\Http\Resources\DatabaseResource;

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
				else
				{
						// throw some error here
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
		$this->status = "Started DOI assignment";
		$this->error = "";
		$this->warning = "";
		$radar = new RadardatasetapiController;
		if($this->database->radar_id == null)
		{
			$this->status = 'Creating RADAR dataset';
			$response = $radar->create($this->database);
			$content = json_decode($response->content());
			if($response->status() != 201)
			{
				$this->error = "Failed to create dataset: ".$content->message;
				return;
			}
			else
			{
				$this->database->radar_id = $content->id;
				$this->database->save();
				$this->status = "RADAR dataset ($content->id) successfully created: ".$content->message;
			}
		}
		else
		{
			$response = $radar->update($this->database);
			$content = json_decode($response->content());
			if($response->status() != 200)
			{
				$this->error = "RADAR metadata update failed: $content->exception";
				return;
			}
			// validate metadata
			$response = $radar->metadataValidate($this->database);
			$content = json_decode($response->content());
			if($response->status() != 200)
			{
				$this->error = "There was an error validating the metadata: $content->exception";
				return;
			}
			if($content->details != "Validation successful")
			{
				$this->error = "The current metadata is incomplete or incorrect: $content->details";
				return;
			}
			$this->status = "The current metadata is valid";
		}
		$response = $radar->read($this->database);
		$content = json_decode($response->content());
		if($response->status() != 200)
		{
			$this->error = "There was an error reading dataset data from RADAR: $content->message";
			return;
		}
		else
		{
			$this->status = "Successfully read dataset data from RADAR";
		}
		if($content->state == "PENDING")
		{
			$this->status = "We need to publish this dataset first";
			$response = $radar->startreview($this->database);
			$content = json_decode($response->content());
			$exception = $content?->exception;
			if($response->status() != 200 || isset($exception))
			{
				$this->error = "Starting review of this dataset failed: $content->exception";
				return;
			}
			$this->status = "Review process started";
		}
		$response = $radar->doi($this->database);
		$content = json_decode($response->content());
		if($response->status() != 200)
		{
			$this->error = "There was an error retrieving the DOI: $content->exception";
			return;
		}
		$doi = $response->content(); // just the DOI (json_decode() returns null)
		$this->status = "Successfully retrieved DOI ($doi).";
		$this->database->doi = $doi;
		$this->database->radarstatus = 1;
		$this->database->save();
		$this->doi = $doi;
		// stop review process
		$response = $radar->endreview($this->database);
		$content = json_decode($response->content());
		if($response->status() != 200)
		{
			$this->error = "Failed to stop review process: $content->exception";
			return;
		}
		$this->status = "Successfully stopped review proceess.";
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
				$this->database->save();
				$this->doi = $this->database->doi;
				$this->radarstatus = $this->database->radarstatus;
				$this->js('window.location.reload()'); 
    }
    public function render()
    {
        return view('livewire.database-visibility');
	}
	
	////////////////////////////////////////////////////////////////////////////////
	// Private
	////////////////////////////////////////////////////////////////////////////////

	private function getJsonValue(string $key, JsonResponse $response) : string
	{
		$array = json_decode($response->content(), true); // 'true' == associative array
		$lastErrorMsg = json_last_error_msg();
		if(is_array($array) && array_key_exists($key, $array))
		{
			return $array[$key];
		}
		return "ERROR: $lastErrorMsg";
	}

}
