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
class DatafileRadarFileBridge extends RadarBridge
{
    var $datafile;	// the Ecosystem datafile
    var $file;		// the RADAR file - some values set sometimes

    function __construct($datafile)
    {
        $this->datafile = $datafile;
		parent::__construct();
    }

	/*
	 * Upload this datafile to the RADAR backend.
	 *
	 * This creates a folder in the dataset's folder using the datasetdef->name and uploads the datafile into this folder
	 *
	 * Returns
	 *
	 *  true on success
	 *  false on failure
	 */
	public function upload() : bool
	{
		// create datasetdef->name folder
		/*
		 * RADAR Docs:
		 *
		 * Create   POST /folders/${folderId}/folders       201, 400, 401, 403, 500
		 *
		 */
		$start = microtime(true);
		$folderName = $this->datafile->datasetdef->name;
		if($this->datafile->datasetdef_radar_id == null)
		{
			$arrayBody = [ 'folderName' => "$folderName" ];//json = '{ "folderName" : "'.$folderName.'" }';
			$endpoint = '/folders/'.$this->datafile->dataset->radar_id.'/folders';
			$response = $this->post($endpoint, $arrayBody);
			if($response->status() == 201)
			{
				$this->datafile->datasetdef_radar_id = $this->getJsonValue('id', $response);
				$this->datafile->datasetdef_radar_upload_url = $this->getJsonValue('uploadUrl', $response);
				$this->datafile->save();
				app('log')->info('Created RADAR folder for datafile', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->datafile->dataset->database->id,
					'dataset_id' => $this->datafile->dataset->id,
					'datafile_id' => $this->datafile->id,
					'datasetdef_radar_id' => $this->datafile->datasetdef_radar_id,
					'target_url' => config('services.radar.baseurl'),
					'duration' => microtime(true) - $start
				]);
			}
			else
			{
				$this->message = 'Failed to created the folder "'.$folderName.'"!';
				$this->details = $response->content();
				app('log')->warning('Failed to create RADAR folder for datafile', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->datafile->dataset->database->id,
					'dataset_id' => $this->datafile->dataset->id,
					'datafile_id' => $this->datafile->id,
					'target_url' => config('services.radar.baseurl'),
					'details' => $this->details,
					'status' => $response->status(),
					'duration' => microtime(true) - $start
				]);
				return false;
			}
		}

		// upload file using datasetdef_radar_upload
		if($this->datafile->radar_id == null)
		{
			$start = microtime(true);
			$response = $this->postFile($this->datafile->datasetdef_radar_upload_url, $this->datafile->absolutepath(), $this->datafile->name, $this->datafile->mimetype);
			if($response->status() == 200)
			{
				$this->datafile->radar_id = $response->content();
				$this->datafile->save();
				$this->message = 'Successfully created the datafile '.$this->datafile->name.' and it\s datasetdef->name folder "'.$folderName.'"!';
				app('log')->info('Uploaded datafile to RADAR', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->datafile->dataset->database->id,
					'dataset_id' => $this->datafile->dataset->id,
					'datafile_id' => $this->datafile->id,
					'radar_id' => $this->datafile->radar_id,
					'target_url' => config('services.radar.baseurl'),
					'duration' => microtime(true) - $start
				]);
			}
			else
			{
				$this->message = 'Failed to upload the file '.$this->datafile->name.'!';
				$this->details = $response->content();
				app('log')->warning('Failed to upload datafile to RADAR', [
					'feature' => 'database-radar-dataset',
					'database_id' => $this->datafile->dataset->database->id,
					'dataset_id' => $this->datafile->dataset->id,
					'datafile_id' => $this->datafile->id,
					'target_url' => config('services.radar.baseurl'),
					'details' => $this->details,
					'status' => $response->status(),
					'duration' => microtime(true) - $start
				]);
				return false;
			}
		}
		return true;
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
		//jw:todo delete 'datasetdef' folder too

		$endpoint = '/folders/'.$this->datafile->datasetdef_radar_id;
		$response = $this->httpdelete($endpoint);
		if($response->status() == 204)
		{
			$logDetails = array(
				'feature' => 'database-radar-dataset',
				'database_id' => $this->datafile->dataset->database->id,
				'dataset_id' => $this->datafile->dataset->id,
				'datafile_id' => $this->datafile->id,
				'target_url' => config('services.radar.baseurl'),
				'radar_id' => $this->datafile->radar_id,
				'datasetdef_radar_id' => $this->datafile->datasetdef_radar_id
			);

			$this->message = 'Successfully deleted the file '.$this->datafile->radar_id.' and it\s datasetdef->name folder from the RADAR server';
			$this->datafile->radar_id = null;
			$this->datafile->datasetdef_radar_id = null;
			$this->datafile->datasetdef_radar_upload_url = null;
			$this->datafile->save();
			app('log')->info('Deleted datafile from RADAR', $logDetails);
			return true;
		}
		else
		{
			$this->message = 'Failed to delete the associated RADAR file '.$this->datafile->radar_id.' and it\'s datasetdef->name folder';
			$this->details = $response->content();
			app('log')->warning ('Failed to deleted datafile from RADAR', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->datafile->dataset->database->id,
				'dataset_id' => $this->datafile->dataset->id,
				'datafile_id' => $this->datafile->id,
				'target_url' => config('services.radar.baseurl'),
				'details' => $this->details
			]);
		return false;
		}
	}
}
