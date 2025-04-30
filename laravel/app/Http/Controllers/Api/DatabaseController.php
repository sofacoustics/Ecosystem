<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Database;
use App\Http\Resources\DatabaseResource;

/*
	Controller for API Database access
*/
class DatabaseController extends Controller
{
	public function show(Database $database)
	{
		// load relationships
		$database->load([
			'creators',
			'publishers',
			'subjectareas',
			'rightsholders',
		]);
		return new DatabaseResource($database);
	}
}
