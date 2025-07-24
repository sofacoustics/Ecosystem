<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str; // isJson

use App\Http\Resources\RadarDatabaseResource;
use App\Mail\DatabasePersistentPublicationApproved;

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
class DatabaseRadarDatasetBridge extends RadarBridge
{
	public $database; // the Ecosystem database
	public $radar_dataset; // the RADAR dataset - some values set sometimes
	public $content; // save the content sent to RADAR
	public $endpoint; // save the endpoint sent to RADAR
	public $radar_content; // JSON content from RADAR

	function __construct($database)
	{
		$this->database = $database;
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
		$endpoint = '/datasets/'.$this->database->radar_id.'/publish';
		$this->content = null; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
		$response = $this->post($endpoint);
		if($this->status == 200)
		{
			$this->database->radar_status = 3;
			$this->database->publicationyear= $this->getNestedJsonValue('descriptiveMetadata.publicationYear', $response);
			$this->database->save();
			// send user and admin an email
			$adminEmails = config('mail.to.admins');
			$userEmail = $this->database->user->email;
			$recipients = $userEmail . ',' . $adminEmails;
			Mail::to(explode(',',$recipients))->queue(new DatabasePersistentPublicationApproved($this->database));
			app('log')->info("DatabasePersistentPublicationApproved email for database " . $this->database->title . " (" . $this->database->id . ") sent to $recipients");
			$this->message = "RADAR Dataset successfully published";
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
		$start = microtime(true);
		$this->reset();
		$endpoint = '/datasets/'.$this->database->radar_id.'/startreview';
		$this->content = null; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
		$response = $this->post($endpoint);
		if($this->status == 200)
		{
			$this->message = "Review process of the RADAR dataset successfully started";
			app('log')->info('Database review process started', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'target_url' => config('services.radar.baseurl'),
				'duration' => microtime(true) - $start
			]);
			return true;
		}
		else
		{
			$this->message = "Starting the RADAR dataset review process failed";
			$this->details = $response->content();
			app('log')->info('Failed to start database review process', [
				'feature' => 'database-radar-dataset',
				'database_id' => $this->database->id,
				'target_url' => config('services.radar.baseurl'),
				'details' => $this->details,
				'duration' => microtime(true) - $start
			]);
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
		$endpoint = '/datasets/'.$this->database->radar_id.'/endreview';
		$this->content = null; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
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
	 * Get DOI from RADAR and save in the database
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
		$endpoint = '/datasets/'.$this->database->radar_id.'/doi';
		$this->content = null; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
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
		 * Call the 'read' API and set the $this->radar_dataset property.
		 *
		 *
		 * Returns
		 *
		 *  'true' if reading is successful
		 *  'false' otherwise
		 *
		 *		If the RADAR dataset does not exist, then it returns
		 *
		 *		'statusCode' => 404
		 *		'content ' => {"message":"There is no RADAR dataset for this database"}
		 *
		 * RADAR Docs:
		 *
		 *	Read	GET	/datasets/{id}		200, 401, 403, 404, 500
		 *
		 */
	public function read() : bool
	{
		$this->reset();
		if($this?->database?->radar_id == null || "$this->database->radar_id" == "")
		{
			$this->message = 'We can\'t read from the RADAR dataset, since it does not exist yet.';
			return false;
		}
		$endpoint = '/datasets/'.$this->database->radar_id;
		$this->content = null; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
		$response = $this->get($endpoint);
		if($response->status() == 404)
		{
			$this->message = 'The corresponding RADAR dataset is missing! (Error: 404)';
			return false;
		}
		$this->message = 'The RADAR metadata has been successfully read from the RADAR server';
		$this->radar_dataset = json_decode($response->content());
		$this->radar_content = json_encode($this->radar_dataset, JSON_PRETTY_PRINT);
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
		$endpoint = '/datasets/'.$this->database->radar_id.'/metadata/validate';
		$this->content = null; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
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
			$this->message = 'Metadata Validation: There was an error validating the RADAR metadata for this database!';
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
		// get Database in RADAR formatted array
		// eager load relationships so they appear in serializeJson()
		$this->database->load(
			'creators',
			'publishers',
			'rightsholders',
			'keywords',
			'relatedidentifiers',
			'subjectareas',
		);
		$resource = new RadarDatabaseResource($this->database);
		$arrayBody = $resource->toArray(request()); // route called with ?format=radar
		$endpoint = '/datasets/'.$this->database->radar_id;
		$this->content = $arrayBody; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
		$response = $this->put($endpoint, $arrayBody);
		if($response->status() == 200)
		{
			$this->message = 'Successfully updated RADAR metadata for this database';
			return true;
		}
		else
		{
			$this->message = 'Failed to update the RADAR metadata for this database';
			$this->details = $response->content();
			return false;
		}
	}

	/**
	 * Create a RADAR dataset from an Ecosystem Database
	 *
	 *  'database'	The database to use
	 *
	 * Response
	 *
	 *	Success:	status code 201 and 'id' => 'RADAR ID', 'message' => 'Success', 'status' => '201'
	 *				status code 200, if our database already exists (done nothing)
	 *	Failure:	status code !201 and 'message' and 'status'
	 *
	 */
	public function create() : bool
	{
		$this->reset();
		if($this->database->radar_id != null)
			return true; // we already have a RADAR dataset
		// get Database in RADAR formatted array
		// eager load relationships so they appear in serializeJson()
		$this->database->load(
			'creators',
			'publishers',
			'rightsholders',
			'keywords',
			'relatedidentifiers',
			'subjectareas',
		);
		// get database as JSON
		$resource = new RadarDatabaseResource($this->database);
		$arrayBody = $resource->toArray(request()); // alternative would be json_decode($resource->toJson(), true);
		
		$endpoint = "/workspaces/$this->workspace/datasets/";
		$this->content = $arrayBody; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
		$response = $this->post($endpoint, $arrayBody);
		if($response->status() == '201')
		{
			$this->database->radar_id = $this->getJsonValue('id', $response);
			$this->database->save();
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
		$endpoint = '/datasets/'.$this->database->radar_id.'/canupload?filename='.$filename;
		$this->content = null; // save the content sent to RADAR
		$this->endpoint = $endpoint; // save the endpoint sent to RADAR
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
		$start = microtime(true);
		if(!$this->create())
		{
			return false;
		}

		foreach($this->database->datasets as $dataset) // For each Ecosystem(!) dataset
		{
			$radar = new DatasetRadarFolderBridge($dataset);
			if(!$radar->upload())
			{
				// failed to upload an Ecosystem dataset
				$this->message = $radar->message;
				$this->details = $radar->details;
				return false;
			}
		}

		$this->message = 'The database has been successfully uploaded to the RADAR backend!';
		app('log')->info('Database uploaded to RADAR dataset', [
			'feature' => 'database-radar-dataset',
			'database_id' => $this->database->id,
			'target_url' => config('services.radar.baseurl'),
			'duration' => microtime(true) - $start
		]);

		return true;
	}

	/*
	 * Delete RADAR dataset corresponding to this database
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
		$endpoint = '/datasets/'.$this->database->radar_id;
		$response = $this->httpdelete($endpoint);
		if($response->status() == 204)
		{
			$this->message = 'The RADAR dataset has been deleted';
			return true;
		}
		$this->message = 'Failed to delete the RADAR dataset';
		$this->details = $response->content();
		return false;
	}
}
