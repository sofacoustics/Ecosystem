<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Http; // guzzle
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Api\Radar\RadarController as RadarapiController;
use App\Http\Controllers\Api\Radar\DatasetController as RadarDatasetController;

/*
 * Access RADAR info via controller and return views
 */
class RadarController extends Controller
{
	/*
	* List all datasets
	*/
    public function index() : View
	{
		//jw:todo implement pagination, since this returns a *maximum* of 20 records!
		$radar = new RadarDatasetController;
		$response = $radar->index();
		$error = '';
		if($response->status() != 200)
			$error = 'There was an error (status: ' . $response->status() . ')';
		$body = $response->content();
        //$datasets = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $body));
		$array = json_decode($body); //jw:note if you specify 'true', then you have to access is via $array['data']. If you don't, then you can access if via $array->data
        return view('datasets', [
			'datasets' => $array->data,
			'error' => $error
        ]);
    }
}
