<?php

namespace App\Http\Controllers\Api\Radar;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Str; // isJson

use App\Http\Resources\RadarDatabaseResource;

use App\Models\Database;

/*
 * Access the RADAR API and return JsonResponse
 */
class DatasetController extends RadarController
{
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
	{
		$response = $this->get("/datasets");
		return JsonResponse::fromJsonString($response->content());
    }


	/*
	 *
	 * RADAR Docs:
	 *
	 *	Review	POST	/datasets/{id}/startreview		200, 401, 403, 404, 422, 500
	 *
	 *	Returns:
	 *
	 *
	 *
	 */
	public function startreview(Database $database) : JsonResponse
	{
		$endpoint = "/datasets/$database->radar_id/startreview";
		$response = $this->post($endpoint);
		return JsonResponse::fromJsonString($response->content());
	}

	/*
	 *
	 * RADAR Docs:
	 *
	 *	Pending	POST	/datasets/{id}/endreview		200, 401, 403, 404, 422, 500
	 *
	 */
	public function endreview(Database $database) : JsonResponse
	{
		$endpoint = "/datasets/$database->radar_id/endreview";
		$response = $this->post($endpoint);
		return JsonResponse::fromJsonString($response->content());
	}

	/*
	 *
	 * RADAR Docs:
	 *
	 *	Retrieve DOI from dataset	GET	/datasets/{id}/doi		200, 401, 403, 404, 422, 500
	 *
	 * Return
	 *
	 *  Returns just the DOI as the body
	 *
	 */
	public function doi(Database $database) : JsonResponse
	{
		$endpoint = "/datasets/$database->radar_id/doi";
		$response = $this->get($endpoint);
		//return new JsonResponse($response);
		return JsonResponse::fromJsonString($response->content());
	}

	/*
	 *
	 * RADAR Docs:
	 *
	 *	Read	GET	/datasets/{id}		200, 401, 403, 404, 500
	 *
	 *	Returns
	 *
	 *		If the dataset does not exist, then it returns
	 *		
	 *		'statusCode' => 404
	 *		'content ' => {"message":"There is no RADAR dataset for this database"}
	 *
	 */
	public function read(Database $database)
	{
		if($database->radar_id == null || "$database->radar_id" == "")
			return response()->json([
				'message' => 'There is no RADAR dataset for this database'
			], 404);
		$endpoint = "/datasets/$database->radar_id";
		$response = $this->get($endpoint);
		return JsonResponse::fromJsonString($response->content());
	}

	/**
	 *
	 * Validate XML metadata	GET	/datasets/{id}/metadata/validate		200, 401, 403, 404, 500*
	 *
	 */
	public function metadataValidate(Database $database) : JsonResponse
	{
		$endpoint = "/datasets/$database->radar_id/metadata/validate";
		$response = $this->get($endpoint);
		return JsonResponse::fromJsonString($response->content());
	}

	/**
	 * Set metadata correction	POST	/datasets/{id}/metadata-correction		200, 401, 403, 404, 422, 500
	 */
	public function update(Database $database) : JsonResponse
	{
		// get Database in RADAR formatted array
        // eager load relationships so they appear in serializeJson()
        $database->load('creators',
            'publishers',
            'subjectareas',
            'rightsholders',
            'keywords',
        );
		$resource = new RadarDatabaseResource($database);
		$arrayBody = $resource->toArray(request()); // route called with ?format=radar
		$endpoint = "/datasets/$database->radar_id";
		$response = $this->put($endpoint, $arrayBody);
		return JsonResponse::fromJsonString($response->content());
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
    public function create(Database $database) : JsonResponse
    {
		// We already have a RADAR ID.
		if($database->radar_id != null)
		{
			return response()->json([
				'message' => 'Success',
				'status' => '200',
			], 200);
		}

		// get Database in RADAR formatted array
        // eager load relationships so they appear in serializeJson()
        $database->load('creators',
            'publishers',
            'subjectareas',
            'rightsholders',
            'keywords',
        );

		$resource = new RadarDatabaseResource($database);

		$arrayBody = $resource->toArray(request()); // route called with ?format=radar
		$dd($arrayBody);
		$endpoint = "/workspaces/$this->workspace/datasets/";
		$response = $this->post($endpoint, $arrayBody);
		if($response->status() == '201')
			return response()->json([
				'message' => 'Success',
				'status' => '201',
				'id' => $this->getJsonValue('id', $response)
			], 201);
		$array = json_decode($response->content(), true);
		return response()->json([
			'message' => $array['exception'],
			'status' => $response->status()
		], $response->status(), $response->headers->all());
	}

    /**
     * Display the specified resource.
     */
    public function show(string $id) : response
    {
		$response = $this->get("/datasets/$id");
		return $response;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    //jw:note these functions weren't created by make:controller --api
    public function testupdate(string $id)
    {
        $database = Database::find($id); // database model
        $json = $database->radardataset->toJson(); // RADAR dataset json
        $dataset_id = $database->radardataset->id;
        //dd($dataset_id);
        dd($database->radardataset->descriptiveMetadata->title);
        //dd(url()->previous());

        $body = $this->put("/datasets/$dataset_id", $json);

        $aBody = json_decode($body, true);
        dd($aBody);
        dd($body);
        //echo "$body";
        return redirect()->back();
        //return redirect($redirect);
        //
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
