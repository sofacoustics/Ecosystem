<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Database;
use App\Http\Resources\DatabaseResource;
use App\Http\Resources\RadarDatabaseResource;

/*
	Controller for API access to Ecosystem Database
*/
class DatabaseController extends Controller
{
	public function show(Database $database)
	{
		// load relationships
		$database->load([
			'creators',
			'publishers',
			'rightsholders',
			'keywords',
			//'relatedidentifiers',
			'subjectareas',
		]);
		$format = request()->query('format');
		if("$format" == 'radar')
			return new RadarDatabaseResource($database);

		return new DatabaseResource($database);
	}
}
