<?php

namespace App\Services;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http; // guzzle

/*
	Do API call *and* do model database stuff
 */
class RadarBridge
{
	var $access_token; // the access token for accessing RADAR
	var $refresh_token; // the refresh token for accessing RADAR
	var $workspace; // the SONICOM workspace
	var $apiurl; // the URL for the RADAR API

		// Information for calling function other than return code
		// Possibly set in inheriting class
	var $message; // a message about success or failure
	var $details; // details about error
	var $status;  // the last response status code

	public function __construct()
	{

		$this->workspace = config('services.radar.workspace');
		$this->apiurl = config('services.radar.baseurl').'/radar/api';

		// Store RADAR credentials in the .env file
		$response = Http::post($this->apiurl."/tokens", [
				'clientId'        => config('services.radar.clientid'),
				'clientSecret'    => config('services.radar.clientsecret'),
				'userName'        => config('services.radar.username'),
				'userPassword'    => config('services.radar.userpassword'),
				'redirectUrl'     => config('services.radar.redirecturl'),
		]);
		$this->status = $response->status();

		$response->throw(); // Throw an exception if a client or server error occurred...
		$body = json_decode($response->body(),true); //jw:todo do we need this 'preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body)); function?
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
	$this->status = $response->status();
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
		$this->status = $response->status();
		return $this->ensureUTF8($response);
	}

	/*
	 * Make a POST request to the RADAR API with the specified endpoint and
	 * return the response body.
	 */
	public function post(string $endpoint, array $json = null)
	{
		$url = $this->apiurl.$endpoint;
		$response = Http::withToken($this->access_token)->post($url, $json);
		$this->status = $response->status();
		//$response->throw(); // Throw an exception if a client or server error occurred...
        return $this->ensureUTF8($response);
    }

	/*
	 * Make a POST request to the RADAR API with the specified endpoint including a file
	 * and return the response body.
	 */
	public function postFile(string $endpoint, $path, $name, $mimetype = null)
	{
		if(strncmp($endpoint,'http',4)==0)
			$url = $endpoint; // absolute endpoint, e.g. file upload
		else
			$url = $this->apiurl.$endpoint;
		$response = Http::withToken($this->access_token)
			->attach('file', file_get_contents($path), $name, ['Content-Type' => $mimetype])
			->post($url);
		$this->status = $response->status();
		//$response->throw(); // Throw an exception if a client or server error occurred...
        return $this->ensureUTF8($response);
	}

	/*
	 * Make a DELETE request
	 */
	public function httpdelete(string $endpoint)
	{
		$url = $this->apiurl.$endpoint;
		$response = Http::withToken($this->access_token)
			->delete($url);
		$this->status = $response->status();
		return $this->ensureUTF8($response);
	}

	////////////////////////////////////////////////////////////////////////////////
	// Protected
	////////////////////////////////////////////////////////////////////////////////

	protected function reset()
	{
		$this->message = '';
		$this->details = '';
		$this->status = '';
	}

	protected function getJsonValue(string $key, response $response) : string
	{
		$array = json_decode($response->content(), true); // 'true' == associative array
		$lastErrorMsg = json_last_error_msg();
		if(is_array($array) && array_key_exists($key, $array))
		{
			return $array[$key];
		}
		return "ERROR retrieving the key $key from the response: $lastErrorMsg";
	}

	protected function getNestedJsonValue(string $keyPath, response $response): ?string
	{
		// Decode the JSON string as an associative array
		$data = json_decode($response->content(), true);

		// Check for JSON decoding errors
		if (json_last_error() !== JSON_ERROR_NONE) {
			return null; // Invalid JSON
		}

		// Split the key path by dots
		$keys = explode('.', $keyPath);
		$current = $data;

		// Traverse the nested keys
		foreach ($keys as $key) {
			if (is_array($current) && array_key_exists($key, $current)) {
				$current = $current[$key];
			} else {
				return null; // Key path not found
			}
		}

		// Return the final value
		return $current;
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
		if (is_array($contentType) && count($contentType) && preg_match('/charset=([a-zA-Z0-9\-]+)/i', $contentType[0], $matches)) {
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

