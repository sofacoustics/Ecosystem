<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str; // isJson

use App\Http\Resources\RadarToolResource;

use App\Models\Tool;

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
class ToolRadarDatasetBridge extends RadarBridge
{
	public $tool; // the Ecosystem tool
	public $radar_dataset; // the RADAR dataset - some values set sometimes
	public $content; // content sent to RADAR
	public $endpoint; // endpoint sent to RADAR
	public $radar_content; // JSON content from RADAR
	public $doi; // retrieved DOI


	function __construct($tool)
	{
		$this->tool = $tool;
		parent::__construct();
	}

	/*
	 *
	 * Publish a RADAR dataset (no uploading!)
	 *
	 * Returns:
	 *
	 *	true on success
	 *	false on failure
	 *
	 * RADAR Docs:
	 *
	 *	publish	POST	/datasets/{id}/publish		200, 401, 403, 404, 422, 500
	 *
	 *
	 */
	public function publish() : bool
	{
		$this->reset();
		$endpoint = '/datasets/'.$this->tool->radar_id.'/publish';
		$this->content = null; 
		$this->endpoint = $endpoint;
		$response = $this->post($endpoint);
		if($this->status == 200)
		{
			$this->message = "Dataset successfully published";
			return true;
		}
		else
		{
			$this->message = "Publishing of the RADAR dataset failed";
			$this->details = $response->content();
			return false;
		}
	}


	/*
	 *
	 * Start the RADAR review process
	 *
	 * Returns:
	 *
	 *	true on success
	 *	false on failure
	 *
	 * RADAR Docs:
	 *
	 *	Review	POST	/datasets/{id}/startreview		200, 401, 403, 404, 422, 500	 
	 *
	 * Notes:
	 *
	 *  This will fail with exception:null if the metadata is invalid (test via RADAR website 'Validate Metadata' button).
	 *
	 */
	public function startreview() : bool
	{
		$this->reset();
		$endpoint = '/datasets/'.$this->tool->radar_id.'/startreview';
		$this->content = null;
		$this->endpoint = $endpoint;
		$response = $this->post($endpoint);
		if($this->status == 200)
		{
			$this->message = "Dataset review process successfully started";
			return true;
		}
		else
		{
			$this->message = "Starting the RADAR dataset review process failed";
			$this->details = $response->content();
			return false;
		}
	}

	/*
	 *
	 * End the RADAR review process
	 *
	 * Return
	 *
	 *  true on success
	 *  false on failure
	 *
	 * RADAR Docs:
	 *
	 *	Pending	POST	/datasets/{id}/endreview		200, 401, 403, 404, 422, 500
	 *
	 */
	public function endreview() : bool
	{
		$this->reset();
		$endpoint = '/datasets/'.$this->tool->radar_id.'/endreview';
		$this->content = null;
		$this->endpoint = $endpoint;
		$response = $this->post($endpoint);
		if($response->status() == 200)
		{
			$this->message = 'The RADAR dataset review process has been ended';
			return true;
		}
		else
		{
			$this->message = 'Ending the RADAR dataset review process failed';
			$this->details = $response->content();
			return false;
		}
	}

	/*
	 *
	 * Get DOI from RADAR and save in the tool
	 *
	 * Return
	 *
	 *  true on success
	 *  false on failure
	 * RADAR Docs:
	 *
	 *	Retrieve DOI from dataset	GET	/datasets/{id}/doi		200, 401, 403, 404, 422, 500
	 *
	 *  Returns just the DOI as the body
	 *
	 */
	public function getDOI() : bool
	{
		$this->reset();
		$endpoint = '/datasets/'.$this->tool->radar_id.'/doi';
		$this->content = null;
		$this->endpoint = $endpoint;
		$response = $this->get($endpoint);
		if($response->status() == 200)
		{
			$this->message = 'DOI successfully retrieved from RADAR';
			$this->doi = $response->content();
			return true;
		}
		else
		{
			$this->message = 'The DOI could not be retrieved from the RADAR server!';
			$this->details = $response->content();
			return false;
		}
	}

		/*
		 *
		 * Call the 'read' API and set the $this->dataset property.
		 *
		 *
		 * Returns
		 *
		 *  'true' if reading is successful
		 *  'false' otherwise
		 *
		 *		If the dataset does not exist, then it returns
		 *
		 *		'statusCode' => 404
		 *		'content ' => {"message":"There is no RADAR dataset for this tool"}
		 *
		 * RADAR Docs:
		 *
		 *	Read	GET	/datasets/{id}		200, 401, 403, 404, 500
		 *
		 */
	public function read() : bool
	{
		$this->reset();
		if($this?->tool?->radar_id == null || "$this->tool->radar_id" == "")
		{
			$this->message = 'We can\'t read from the RADAR dataset, since it does not exist yet.';
			return false;
		}
		$endpoint = '/datasets/'.$this->tool->radar_id;
		$this->content = null;
		$this->endpoint = $endpoint;
		$response = $this->get($endpoint);
		if($response->status() == 404)
		{
			$this->message = 'The corresponding RADAR dataset is missing! (Error: 404)';
			return false;
		}
		$this->message = 'The RADAR metadata has been successfully read from the RADAR server';
		$this->radar_dataset = json_decode($response->content()); 
		$this->radar_content = $response->content();
		return true;
	}

	/**
	 * Validate the current RADAR metadata
	 *
	 * Returns:
	 *
	 *  true on success
	 *  false on failure
	 *
	 * RADAR Docs:
	 *
	 *  Validate XML metadata	GET	/datasets/{id}/metadata/validate		200, 401, 403, 404, 500*
	 *
	 *
	 */
	public function metadataValidate() : bool
	{
		$this->reset();
		$endpoint = '/datasets/'.$this->tool->radar_id.'/metadata/validate';
		$this->content = null;
		$this->endpoint = $endpoint;
		$response = $this->get($endpoint);
		if($response->status() == 200)
		{
			$content = json_decode($response->content());
			if($content->details == "Validation successful")
			{
				$this->message = 'Metadata Validation: The current RADAR metadata is valid';
				return true;
			}
			else
			{
				$this->message = 'Metadata Validation: The metadata is incomplete or incorrect!';
				$this->details = $content->details;
				return false;
			}
		}
		else
		{
			$this->message = 'Metadata Validation: There was an error validating the RADAR metadata for this tool!';
			$this->details = $response->content();
			return false;
		}
	}

	/*
	 * Update a RADAR dataset
	 *
	 * Returns
	 *
	 *  true on success
	 *  false on failure
	 *
	 * RADAR Docs:
	 *
	 *  Set metadata correction	POST	/datasets/{id}/metadata-correction		200, 401, 403, 404, 422, 500
	 *
	 */
	public function update() : bool
	{
		$this->reset();
			// get Tool in RADAR formatted array
			// eager load relationships so they appear in serializeJson()
		$this->tool->load(
			'creators',
			'publishers',
			'rightsholders',
			'keywords',
			'relatedidentifiers',
			'subjectareas',
		);
		$resource = new RadarToolResource($this->tool);
		$arrayBody = $resource->toArray(request()); // route called with ?format=radar
		$endpoint = '/datasets/'.$this->tool->radar_id;
		$this->content = $arrayBody;
		$this->endpoint = $endpoint;
		$response = $this->put($endpoint, $arrayBody);
		if($response->status() == 200)
		{
			$this->message = 'Successfully updated RADAR metadata for this tool';
			return true;
		}
		else
		{
			$this->message = 'Failed to update the RADAR metadata for this tool';
			$this->details = $response->content();
			return false;
		}
	}

	/**
	 * Create a RADAR dataset from an Ecosystem Tool
	 *
	 *  'tool'	The tool to use
	 *
	 * Response
	 *
	 *	Success:	status code 201 and 'id' => 'RADAR ID', 'message' => 'Success', 'status' => '201'
	 *				status code 200, if our tool already exists (done nothing)
	 *	Failure:	status code !201 and 'message' and 'status'
	 *
	 */
	public function create() : bool
	{
		$this->reset();
		if($this->tool->radar_id != null)
			return true; // we already have a RADAR dataset
			// get Tool in RADAR formatted array
			// eager load relationships so they appear in serializeJson()
		$this->tool->load(
			'creators',
			'publishers',
			'rightsholders',
			'keywords',
			'relatedidentifiers',
			'subjectareas',
		);
			// get tool as JSON
		$resource = new RadarToolResource($this->tool);
		$arrayBody = $resource->toArray(request()); // alternative would be json_decode($resource->toJson(), true);
		
		$endpoint = "/workspaces/$this->workspace/datasets/";
		$this->content = $arrayBody;
		$this->endpoint = $endpoint;
		$response = $this->post($endpoint, $arrayBody);
		if($response->status() == '201')
		{
			$this->tool->radar_id = $this->getJsonValue('id', $response);
			$this->tool->radar_upload_url = $this->getJsonValue('uploadUrl', $response);
			$this->tool->save();
			return true; // success
		}
		$content = json_decode($response->content());
		$this->message = "Failed to create the RADAR dataset!";
		$this->details = $content?->exception ?? 'Unknown error!' . '(Code: ' . $response->status() .')';
		return false;
	}

	/**
	 * Check if a file with the specified name can be uploaded
	 *
	 * Returns
	 *
	 *  true if yes
	 *  false if no
	 *
	 * RADAR Docs:
	 *
	 *  Can upload file	GET	/datasets/{id}/canupload	filename	200, 401, 403, 404, 422, 500
	 *
	 */
	public function canUpload($filename)
	{
		$this->reset();
		$endpoint = '/datasets/'.$this->tool->radar_id.'/canupload?filename='.$filename;
		$this->content = null; 
		$this->endpoint = $endpoint; 
		$response = $this->get($endpoint);
		if($response->status() == 200)
		{
			$this->message = 'The file \''.$filename.'\' may be uploaded to the RADAR server.';
			return true;
		}
		else
		{
			$this->message = 'The file \''.$filename.'\' may not be uploaded to the RADAR server.';
			$this->details = $response->content();
			return false;
		}
	}

	/*
	 * Upload the datafile to the RADAR server using the upload URL specified
	 * in the RADAR metadata value 'uploadUrl'.
	 *
	 * Sets the 'radar_id' field to the id returned by the RADAR API on success
	 *
	 * Returns
	 *
	 *  true on successs
	 *  false on failure
	 *
	 * RADAR Docs:
	 *
	 *  The /upload endpoint can be used to upload any single files.
	 *  If files in the formats zip/tar/tar.gz/tar.bz are uploaded, these archives will then NOT be extracted.
	 */
	public function upload() : bool
	{
		if(!$this->create())
		{
			return false;
		}

		if($this->tool->filename) // if tool file available
		{
			$radar = new ToolfileRadarFileBridge($this->tool);
			if(!$radar->upload())
			{
				// failed to upload the tool file
				$this->message = $radar->message;
				$this->details = $radar->details;
				return false;
			}
		}
		else
		{
			$this->message = 'The tool file is not available!';
			return false;
		}

		$this->message = 'The tool has been successfully uploaded to the Datathek!';
		return true;
	}

	/*
	 * Delete RADAR dataset corresponding to this tool and reset the DOI
	 *
	 * Returns
	 *
	 *  true on success
	 *  false on failure
	 *
	 * RADAR Docs:
	 *
	 *  Delete	DELETE	/datasets		200, 401, 403, 404, 500
	 *
	 */
	public function delete()
	{
		$endpoint = '/datasets/'.$this->tool->radar_id;
		$response = $this->httpdelete($endpoint);
		if($response->status() == 204)
		{
			$this->message = 'The RADAR dataset has been deleted and DOI assignment removed';
			return true;
		}
		$this->message = 'Failed to delete the RADAR dataset';
		$this->details = $response->content();
		return false;
	}

}
