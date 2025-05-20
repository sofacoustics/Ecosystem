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
        $this->apiurl = env("RADAR_BASEURL")."/radar/api";

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
		//dd($body);
        $this->access_token = $body['access_token'];
		$this->refresh_token = $body['refresh_token'];
    }

    /*
     * Make a GET request to the RADAR API with the specified endpoint and
     * return the response body.
     */
    public function get(string $endpoint)
    {
        $url = $this->apiurl.$endpoint;
        $response = Http::withToken($this->access_token)->get("$url");
		return $this->ensureUTF8($response);
    }

    /*
     * Make a PUT request to the RADAR API with the specified endpoint and
     * return the response body.
     */
    public function put(string $endpoint, array $json)
    {
        $url = $this->apiurl.$endpoint;
		$response = Http::withToken($this->access_token)->put("$url", $json);
        return $this->ensureUTF8($response);
    }

    /*
     * Make a POST request to the RADAR API with the specified endpoint and
     * return the response body.
     */
    public function post(string $endpoint, array $json)
	{
		$url = $this->apiurl.$endpoint;
        $response = Http::withToken($this->access_token)->post($url, $json);
		//$response->throw(); // Throw an exception if a client or server error occurred...
        return $this->ensureUTF8($response);
    }

    ////////////////////////////////////////////////////////////////////////////////
    // Private
	////////////////////////////////////////////////////////////////////////////////

	/*
	 * Ensure that encoding is UTF-8. Reencode if necessary (RADAR has been returning
	 * ISO-8859-1, even if requesting UTF-8
	 */
	private function ensureUTF8(\Illuminate\Http\Client\Response $response)
	{
		$contentType = $response->getHeader('Content-Type'); // e.g., "text/html; charset=ISO-8859-1"
		$charset = null;
		if (preg_match('/charset=([a-zA-Z0-9\-]+)/i', $contentType[0], $matches)) {
			    $charset = $matches[1];
		}
		if($charset != null)
		{
			$content = $response->body(); // Get raw body
			$utf8Content = mb_convert_encoding($content, 'UTF-8', $charset); // Convert encoding

			return response($utf8Content, $response->getStatusCode())
					->withHeaders($response->headers())
					->header('Content-Type', 'application/json; charset=UTF-8');
		}
		return response($response->body(), $response->getStatusCode())->withHeaders($response->headers());
	}
}
