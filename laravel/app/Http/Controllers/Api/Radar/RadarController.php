<?php

namespace App\Http\Controllers\Api\Radar;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;

/*
 * SONICOM base class for RADAR API Communication
 *
 * All authentication settings are specified in the .env file.
 *
 * Functions generally have an endpoint parameter (e.g. /datasets), which is appended to
 * the API URL (e.g. https://datathektest.oeaw.ac.at/radar/api)
 *
 * Implements GET
 *
 * Usage:
 *
 *  $radar = new RadarController;
 *  $json = $radar->get("/datasets");
 *
 *  Laravel documentation: https://laravel.com/docs/11.x/http-client
 */
class RadarController extends Controller
{
    var $access_token; // the access token for accessing RADAR
    var $refresh_token; // the refresh token for accessing RADAR
    var $workspace; // the SONICOM workspace
    var $apiurl; // the URL for the RADAR API

    public function __construct()
    {
        $this->workspace = env("RADAR_WORKSPACE");
        $baseurl = env("RADAR_BASEURL");
        $this->apiurl = $baseurl."/radar/api";

        // Store RADAR credentials in the .env file
        $response = Http::post($this->apiurl."/tokens", [
            'clientId'        => env("RADAR_CLIENTID"),
            'clientSecret'    => env("RADAR_CLIENTSECRET"),
            'userName'        => env("RADAR_USERNAME"),
            'userPassword'    => env("RADAR_USERPASSWORD"),
            'redirectUrl'     => env("RADAR_REDIRECTURL"),
        ]);

        $response->throw(); // Throw an exception if a client or server error occurred...
        $body = json_decode($response->body(),true); //jw:todo do we need this 'preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body)); function?
        $this->access_token = $body['access_token'];
        $this->refresh_token = $body['refresh_token'];
    }

    /*
     * Make a GET request to the RADAR API with the specified endpoint and
     * return the response body.
     */
    public function get(string $endpoint) : String
    {
        $url = $this->apiurl.$endpoint;
        $response = Http::withOptions([
            'debug' => false,
        ])->withToken($this->access_token)
          ->get("$url");

        $response->throw(); // Throw an exception if a client or server error occurred...

        return $response->body();
    }

    /*
     * Make a PUT request to the RADAR API with the specified endpoint and
     * return the response body.
     */
    public function put(string $endpoint, string $json) : String
    {
        //jw:todo
        $url = $this->apiurl.$endpoint;
        //dd($url);
        //dd($this->access_token);
        //dd($json);
        $response = Http::withOptions([
            'debug' => false,
        ])->withToken($this->access_token)
          ->put("$url", json_decode($json,true));
        $body = json_decode($response->body(),true);
//        if($response['statusCode'] == 400)
        //print_r($response); // note that this will write to the 'response'!!!
        if($response->status() != 200)
        {
            //dd(false);
            //dd($response->body());
            if(array_key_exists("exception", $body))
            {
                //jw:todo do something
                //abort(404, $body['exception']);
                //dd($body['exception']);
                return back()->withError($body['exception'])->withInput();
            }
            //dd(json_decode($response->body(),true));
        }


        //dd($json);
        //$response = Http::withHeaders([
        //                'Authorization' => 'Bearer ' . $token
        //                      ])->put('https://api.example.com/api/v1/groups/' . $request->input('id'), [
        //                                      'name' => $request->input('name')
        //                                      ]);
        return $response->body();
    }

    ////////////////////////////////////////////////////////////////////////////////
    // Private
    ////////////////////////////////////////////////////////////////////////////////
}
