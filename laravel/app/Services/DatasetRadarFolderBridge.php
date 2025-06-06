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
use App\Models\Dataset;

/*
 * Access the RADAR API and update model values if necessary!
 * 
 * All functions return true or false and set the following variables:
 *
 *	$message
 *
 * And if there's an error, then set the following too:
 *
 *	$details
 *
 * Note '$this->status' is set by RadarBridge
 *
 */
class DatasetRadarFolderBridge extends RadarBridge
{
    public Dataset $dataset;	// the Ecosystem dataset
    public $radarfolder;		// the RADAR folder - some values set sometimes

    function __construct($dataset)
    {
        $this->dataset = $dataset;
		parent::__construct();
    }

    /*
     * Create a folder using the dataset name
     *
     * Returns
     *
     *  true on success
     *  false on failure
     *
     * RADAR Docs:
     *
     * Create   POST /datasets/${datasetId}/folders     201, 400, 401, 403, 500
     * Create   POST /folders/${folderId}/folders       201, 400, 401, 403, 500
     *
     */
    public function create() : bool
	{
		if($this->dataset->radar_id != null)
		{
			$this->message = 'No need to create the folder, since it already exists';
			return true; // already exists
		}

        if($this->dataset->database->radar_id == null)
        {
            $this->message = 'Failed to create a folder for this dataset since its database has no RADAR id!';
            return false;
		}
		$folderName = $this->dataset->name;
		$arrayBody = [ 'folderName' => "$folderName" ];//json = '{ "folderName" : "'.$folderName.'" }';
		$endpoint = '/datasets/'.$this->dataset->database->radar_id.'/folders';
		$response = $this->post($endpoint, $arrayBody);
		if($response->status() == 201)
		{
			$this->dataset->radar_id = $this->getJsonValue('id', $response);
			$this->dataset->radar_upload_url = $this->getJsonValue('uploadUrl', $response);
			$this->dataset->save();
			$this->message = 'Successfully created the folder "'.$folderName.'"!';
			return true;
		}
		else
		{
			$this->message = 'Failed to created the folder "'.$folderName.'"!';
			$this->details = $response->content();
			return false;
		}
	}

	/*
	 * Delete a dataset (RADAR folder) from the RADAR backend including it's subfolders and files
	 *
	 * RADAR Docs:
	 *
	 *  Delete	DELETE	/folders/{id}		200, 401, 403, 404, 500
	 */
	public function delete(): bool
	{
		if($this->dataset->radar_id == null)
			return true; // nothing to delete

		$endpoint = '/folders/'.$this->dataset->radar_id;
		$response = $this->httpdelete($endpoint);
		if($response->status() == 204)
		{
			$this->message = 'Successfully deleted the folder '.$this->dataset->radar_id.' from the RADAR server';
			$this->dataset->radar_id = null;
			$this->dataset->radar_upload_url = null;
			$this->dataset->save();
			// reset RADAR ids for datafiles
			foreach($this->dataset->datafiles as $datafile)
			{
				$datafile->radar_id = null;
				$datafile->datasetdef_radar_id = null;
				$datafile->datasetdef_radar_upload_url = null;
				$datafile->save();
			}
			return true;
		}
		else
		{
			$this->message = 'Failed to delete the associated RADAR folder '.$this->dataset->radar_id;
			$this->details = $response->content();
			return false;
		}

		return false;
	}

	/*
	 * Upload the Dataset to RADAR
	 *
	 * - creates the dataset folder
	 * - uploads all datafiles using the uploadUrl endpoint
	 *
	 */
	public function upload()
	{
		// create folder for this dataset
		if($this->create())
		{
			$uploadUrl = $this->dataset->radar_upload_url;
			// upload datafiles
			foreach($this->dataset->datafiles as $datafile)
			{
				if($datafile->datasetdef_radar_id != null && $datafile->radar_id != null)
					continue; // already uploaded

				$radarfile = new DatafileRadarFileBridge($datafile);
				if(!$radarfile->upload())
				{
					$this->message = $radarfile->message;
					$this->details = $radarfile->details;
				}
			}
			$this->message = 'The dataset \''.$this->dataset->name.'\' and its datafiles have been successfully uploaded to the RADAR server.';
			return true;
		}
		return false;
	}
}
