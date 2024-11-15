<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Api\Radar\RadarController as RadarapiController;

class RadarController extends Controller
{
    var $access_token; // the access token for accessing RADAR
    var $workspace;
    var $baseurl;

    var $radar;

    public function __construct()
    {

        // Get valid access token
//        $response = Http::post("$this->baseurl/radar/api/tokens", [
//            'clientId'        => 'institut-fuer-schallforschung',
//            'clientSecret'    => '7yb§cwEtfb',
//            'userName'        => 'jonathanstuefer',
//            'userPassword'    => 'ZtbI2ljoVKzL1yWDfK8Z',
//            'redirectUrl'     => 'https://www.oeaw.ac.at/isf'
//        ]);

        //var $workspace = "RDJBmjTacaxKwSGW"; // Karlsruhe
        //var $baseurl = 'https://test.radar-service.eu'; // Karlsruhe
        $this->workspace = env("RADAR_WORKSPACE");
        $this->baseurl = env("RADAR_BASEURL");
        $this->dataseturl = $this->baseurl."/radar/api/datasets";

        $this->radar = new RadarapiController; // this controller can be used for RADAR API calls

        // Store RADAR credentials in the .env file
        $response = Http::post($this->baseurl."/radar/api/tokens", [
            'clientId'        => env("RADAR_CLIENTID"),
            'clientSecret'    => env("RADAR_CLIENTSECRET"),
            'userName'        => env("RADAR_USERNAME"),
            'userPassword'    => env("RADAR_USERPASSWORD"),
            'redirectUrl'     => env("RADAR_REDIRECTURL"),
        ]);

        $response->throw(); // Throw an exception if a client or server error occurred...
        $body = json_decode($response->body(),true);
        $this->access_token = $body['access_token'];
    }

	/*
	* List all datasets
	*/
    public function index() : View
    {
        $body = $this->radar->get("/datasets");
        $datasets = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body));
        return view('datasets', [
            'datasets' => $datasets
        ]);


        /*
        $response = Http::withOptions([
            'debug' => false,
        ])->withToken($this->access_token)
        ->get("$this->baseurl/radar/api/datasets/?query=parentId:$this->workspace");

        $response->throw(); // Throw an exception if a client or server error occurred...

        $datasets = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->body()));

        //Storage::disk('data')->put('test.txt', 'some test text written from laravel :-)');

        return view('datasets', [ 
            'datasets' => $datasets
        ]);
         */
    }

    public function dataset(string $id) : String
    {
        $this->_get($this->dataseturl."/$id");
    }

    public function get(string $id) : Void
    {
        $this->_get($this->dataseturl."/$id");
        //dd($id);
    }

    ////////////////////////////////////////////////////////////////////////////////
    // Private
    ////////////////////////////////////////////////////////////////////////////////
    /*
     * Request a 'GET' with the specified URL amd return the body.
     */
    private function _get(string $url) : String
    {
        $response = Http::withOptions([
            'debug' => false,
        ])->withToken($this->access_token)
        ->get("$url");

        $response->throw(); // Throw an exception if a client or server error occurred...

        $body = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->body()));

        //Storage::disk('data')->put('test.txt', 'some test text written from laravel :-)');

        dd($body);
        return "$body";
    }
}
