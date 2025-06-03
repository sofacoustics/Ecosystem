<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str; // isJson

use App\Http\Resources\RadarDatabaseResource;

use App\Models\Database;
use App\Models\Datafile;

/*
 * Access the RADAR API and update model values if necessary!
 * 
 * All functions return true or false and set the following variables:
 *
 *	message
 *
 * And if there's an error, then set the following too:
 *
 *	details
 *
 * Note '$this->status' is set by RadarBridge
 *
 */
class FileRadarBridge extends RadarBridge
{
    var $datafile;	// the Ecosystem datafile
    var $file;		// the RADAR file - some values set sometimes

    function __construct($datafile)
    {
        $this->datafile = $datafile;
		parent::__construct();
    }

	/*
	 * Delete a file from RADAR
	 *
	 * NOTE: the API returns '204' if successfully deleted!
	 *
	 * Returns:
	 *
	 *  true on success
	 *  false on failure
	 *
	 * RADAR Docs:
	 *
	 *  Delete	DELETE	/files/{id}		200, 401, 403, 404, 500
	 *
	 */
	public function delete($endpoint = '')
	{
		$endpoint = '/files/'.$this->datafile->radar_id;
		$response = parent::delete($endpoint);
		if($response->status() == 204)
		{
			$this->message = 'Successfully deleted the file '.$this->datafile->radar_id.' from the RADAR server';
			$this->datafile->radar_id = null;
			$this->datafile->save();
			return true;
		}
		else
		{
			$this->message = 'Failed to delete the associated RADAR file '.$this->datafile->radar_id;
			$this->details = $response->content();
			return false;
		}
	}
	////////////////////////////////////////////////////////////////////////////////
	// Private
	////////////////////////////////////////////////////////////////////////////////

	private function getJsonValue(string $key, response $response) : string
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
