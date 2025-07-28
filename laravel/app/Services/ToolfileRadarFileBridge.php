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
		$start = microtime(true);
		// upload file using datasetdef_radar_upload
		$response = $this->postFile($this->tool->radar_upload_url, $this->tool->absolutepath(), $this->tool->filename);
		if($response->status() == 200)
		{
			// save RADAR id
			$this->tool->file_radar_id = $response->content();
			$this->tool->save();
			$this->message = 'Successfully created the tool file '.$this->tool->filename.'!';
			app('log')->info('Tool file upload to RADAR', [
				'feature' => 'tool-radar-dataset',
				'tool_id' => $this->tool->id,
				'target_url' => config('services.radar.baseurl'),
				'details' => $this->details,
				'radar_id' => $this->tool->file_radar_id,
				'status' => $response->status(),
				'duration' => microtime(true) - $start
			]);
			return true;
		}
		else
		{
			$this->message = 'Failed to upload the file '.$this->tool->filename.'!';
			$this->details = $response->content();
			app('log')->warning('Failed to upload tool file to RADAR', [
				'feature' => 'tool-radar-dataset',
				'tool_id' => $this->tool->id,
				'target_url' => config('services.radar.baseurl'),
				'details' => $this->details,
				'status' => $response->status(),
				'duration' => microtime(true) - $start
			]);
			return false;
		}
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
		$start = microtime(true);
		$endpoint = '/folders/'.$this->tool->file_radar_id;
		$response = $this->httpdelete($endpoint);
		if($response->status() == 204)
		{
			// save RADAR id
			$this->tool->file_radar_id = null;
			$this->tool->save();
			$this->message = 'Successfully deleted the file '.$this->tool->radar_id.'!';
			app('log')->info('Deleted tool file from RADAR', [
				'feature' => 'tool-radar-dataset',
				'tool_id' => $this->tool->id,
				'target_url' => config('services.radar.baseurl'),
				'duration' => microtime(true) - $start
			]);
			return true;
		}
		else
		{
			$this->message = 'Failed to delete the associated RADAR file '.$this->tool->radar_id.'!';
			$this->details = $response->content();
			app('log')->warning('Failed to delete tool file from RADAR', [
				'feature' => 'tool-radar-dataset',
				'tool_id' => $this->tool->id,
				'radar_id' => $this->tool->radar_id,
				'file_radar_id' => $this->tool->file_radar_id,
				'status' => $response->status(),
				'details' => $this->details,
				'target_url' => config('services.radar.baseurl'),
				'duration' => microtime(true) - $start
			]);
			return false;
		}
	}
}
