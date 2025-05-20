<?php

namespace App\Http\Controllers\Api\Radar;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Str; // isJson

use App\Http\Resources\RadarDatabaseResource;

use App\Models\Database;

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

		$endpoint = "/workspaces/".env('RADAR_WORKSPACE')."/datasets/";
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
