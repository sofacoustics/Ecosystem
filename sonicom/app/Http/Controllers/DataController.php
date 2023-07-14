<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http; // guzzle

class DataController extends Controller
{
    var $access_token; // the access token for accessing RADAR
    var $workspace;

    public function __construct()
    {
        $this->workspace = "RDJBmjTacaxKwSGW";
    
        // Get valid access token
        $url = 'https://test.radar-service.eu/radar/api/tokens';
        $response = Http::post($url, [
            'clientId'        => 'institut-fuer-schallforschung',
            'clientSecret'    => '7yb§cwEtfb',
            'userName'        => 'jonathanstuefer',
            'userPassword'    => 'ZtbI2ljoVKzL1yWDfK8Z',
            'redirectUrl'     => 'https://www.oeaw.ac.at/isf'
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
        $url = "https://test.radar-service.eu/radar/api/datasets/?query=parentId:$this->workspace";
        $response = Http::withOptions([
            'debug' => false,
        ])->withToken($this->access_token)
        ->get($url);

        $response->throw(); // Throw an exception if a client or server error occurred...

        $datasets = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->body()));

        return view('datasets', [ 
            'datasets' => $datasets
        ]);
    }
}
