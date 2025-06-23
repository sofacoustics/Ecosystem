<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str; // isJson

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
class ToolfileRadarFileBridge extends RadarBridge
{
    var $tool;	

    function __construct($tool)
    {
        $this->tool = $tool;
		parent::__construct();
    }

	/*
	 * Upload the Tool file to the RADAR backend.
	 *
	 * This uploads the file from a Tool to RADAR
	 *
	 * Returns
	 *
	 *  true on success
	 *  false on failure
	 */
	public function upload() : bool
	{

		// upload file using datasetdef_radar_upload
		$response = $this->postFile($this->tool->radar_upload_url, $this->tool->absolutepath(), $this->tool->filename);
		if($response->status() == 200)
		{
			$this->message = 'Successfully created the tool file '.$this->tool->filename.'!';
			return true;
		}
		else
		{
			$this->message = 'Failed to upload the file '.$this->tool->filename.'!';
			$this->details = $response->content();
			return false;
		}
		return false;
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
		$endpoint = '/folders/'.$this->tool->radar_id;
		$response = $this->httpdelete($endpoint);
		if($response->status() == 204)
		{
			$this->message = 'Successfully deleted the file '.$this->tool->radar_id.'!';
			return true;
		}
		else
		{
			$this->message = 'Failed to delete the associated RADAR file '.$this->tool->radar_id.'!';
			$this->details = $response->content();
			return false;
		}
	}
}
