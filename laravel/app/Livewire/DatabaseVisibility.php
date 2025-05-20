<?php

namespace App\Livewire;

use App\Models\Database;

use App\Http\Controllers\Api\Radar\DatasetController as RadardatasetController;
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
	public $radarstatus;

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
		// create RADAR dataset
		$radardataset = new RadardatasetController;
		$response = $radardataset->create($this->database); // requires an array, not JSON. Expect 201 status on success
		if($response->status() == 201)
		{
			$radar_id = $this->getJsonValue('id', $response);
			$this->database->radar_id = $radar_id;
			$this->database->save();
			$this->status = "RADAR dataset created (id: $radar_id)";
		}
		else if($response->status() == 200)
		{
			$this->status = 'RADAR dataset already exists ('.$this->database->radar_id.')';
		}
		else
		{
			$this->status = "RADAR status code ". $response->status();
		}

		$this->status .= ' TODO: assign DOI';
		//jw:tod get DOI
		/*
		$this->database->doi = "testDOI";
		$this->database->save();
		$this->doi = $this->database->doi;
		*/
    }

    public function submitToPublish()
    {
				$this->database->radarstatus=1;
				$this->database->save();
				$this->radarstatus = $this->database->radarstatus;
				$this->js('window.location.reload()');
    }

    public function approve() // Emulate the curator approving the publication at the Datathek
    {
				$this->database->radarstatus=2;
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
