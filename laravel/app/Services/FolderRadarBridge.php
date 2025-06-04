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
class FolderRadarBridge extends RadarBridge
{
    public Dataset $dataset;	// the Ecosystem dataset
    public $folder;		// the RADAR file - some values set sometimes

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
				if($datafile->radar_id != null)
					continue; // already uploaded

				$response = $this->postFile($uploadUrl, $datafile->absolutepath(), $datafile->name, $datafile->mimetype);
				if($response->status() != 200)
				{
					$this->message = 'Failed to upload the file '.$datafile->name.'!';
					$this->details = $response->content();
					return false;
				}
				else
				{
					$datafile->radar_id = $response->content();
					$datafile->save();
				}
			}
			$this->message = 'The dataset \''.$this->dataset->name.'\' and its datafiles have been successfully uploaded to the RADAR server.';
			return true;
		}
		return false;
	}
}
