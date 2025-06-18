<?php

namespace App\Http\Controllers;

use Carbon;

use App\Models\User;
use App\Models\Database;
use App\Models\Datafile;
use App\Models\Radardataset;
use App\Models\Radardatasetresourcetype;

use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;

use App\Http\Resources\DatabaseResource;
use App\Http\Resources\Json\JsonResource;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DatabaseController extends Controller
{
	public function __construct()
	{
			// https://laracasts.com/discuss/channels/general-discussion/apply-middleware-for-certain-methods?page=0
			// Users must be authenticated for all functions except index and show.
			// Guests will be redirected to login page
		$this->middleware('auth', ['except' => ['index', 'show', 'download', 'showdatasets', 'datasetdefs', 'radarShow', 'radar']]);
	}

		/**
		 * Display a listing of the resource.
		 */
	public function index(Request $request)
	{
		$type = $request->input('type');  
		if ($type === "json")
		{
			try 
			{
				$databases = Database::where('databases.visible', '=', 1) // get visible databases only
					->select('databases.*') // Select all order columns
					->get();

				$data = $databases->map(function ($database) { // Transform the data into a suitable format.  This is CRUCIAL.
						return [
								'ID' => $database->id,
								'Title' => $database->title,
								'URL' => url()->current() . '/' . $database->id . '/download?type=json',
								'Subtitle' => $database->additionaltitle,
								'Production Year' => $database->productionyear,
								'Created Date' => $database->created_at,
								'Updated Date' => $database->updated_at,
						];
				});
				// Return the file data as a JSON response.
				return response()->json([
						'success' => true,
						'message' => 'Files retrieved successfully.',
						'data'    => $data,
				], 200); // 200 OK
			} 
			catch (\Exception $e) 
			{
				// Handle any errors that occur during the process.
				return response()->json([
						'success' => false,
						'message' => 'Failed to retrieve files: ' . $e->getMessage(),
				], 500); // 500 Internal Server Error
			}
		}
		else
		{
			$databases = \App\Models\Database::all();
			return view('databases.index', ['allDatabases' => $databases]);
		}
	}

		/**
		 * Show the form for creating a new resource.
		 */
	public function create()
	{
		$this->authorize('create', Database::class);
		// https://pusher.com/blog/laravel-mvc-use/#controllers-creating-our-controller
		return view('databases.create');
	}

		/**
		 * Store a newly created resource in storage.
		 */
	public function store(StoreDatabaseRequest $request)
	{
			// An alternative - where rules are in the model: https://medium.com/@konafets/a-better-place-for-your-validation-rules-in-laravel-f5e3f5b7cc
		Database::create($request->validated()); // https://dev.to/secmohammed/laravel-form-request-tips-tricks-2p12
		return redirect('databases')->with('success', "Database $request->title successfully created!");
	}

		/**
		 * Display the specified resource.
		 */
	public function show(Database $database)
	{
		$this->authorize($database);
		$user = \App\Models\User::where('id', $database->user_id)->first();
		return view('databases.show',[ 'database' => $database, 'user' => $user ]);
	}

		/**
		 * Manage database visibility options
		 */
	public function visibility(Database $database)
	{
		$this->authorize('own', $database);
		$user = \App\Models\User::where('id', $database->user_id)->first();
		return view('databases.visibility', [ 'database' => $database, 'user' => $user ]);
	}

		/**
		 * Show the form for editing the specified resource.
		 */
	public function edit(Database $database)
	{
		$this->authorize($database);
		return view('databases.edit', [ 'database' => $database ]);
	}

		/**
		 * Update the specified resource in storage.
		 */
	public function update(UpdateDatabaseRequest $request, Database $database)
	{
		$this->authorize($database);
		$database->update($request->except('_method', '_token', 'submit'));
		return redirect('databases')->with('success', 'Database successfully updated!');
	}

		/**
		 * Remove the specified resource from storage.
		 */
	public function destroy(Database $database)
	{
		$this->authorize($database);
		$database->delete(); // Note that due to onDelete('cascade') in files database, the related files will be deleted too!
		return redirect()->route('databases.index')->with('success', 'Database deleted successfully');
	}

	/*public function radarShow(Database $database)
	{
		return view('databases.radar.show',['database' => $database]);
	}

	public function radarEdit(Database $database)
	{
		return view('databases.radar.edit',['database' => $database]);
	}*/

	public function datasetdefs(Database $database)
	{
		return view('databases.datasetdefs.index', ['database' => $database]);
	}

	public function upload(Database $database)
	{
		return view('databases.upload', ['database' => $database]);
	}

	public function purge(Database $database)
	{
		return view('databases.purge', ['database' => $database]);
	}

	public function download(Request $request, Database $database) 
	{
		$type = $request->input('type');  
		if ($type === "json")
		{
			if (!($database->visible))
			{
				return response()->json([
						'success' => false,
						'message' => 'Database not visible.',
				], 500); // 500 Internal Server Error
			}
			try 
			{
				$files = Datafile::join('datasets', 'datafiles.dataset_id', '=', 'datasets.id')
					->join('databases','datasets.database_id', '=', 'databases.id')
					->where('databases.id', '=', $database->id)
					->select('datafiles.*') // Select all order columns
					->get();

				$fileData = $files->map(function ($file) { // Transform the data into a suitable format.  This is CRUCIAL.
						return [
								'Datafile ID' => $file->id,
								'Datafile Name' => $file->name,
								'Datafile URL' => asset($file->url()),
								'Datafile Type' => $file->datasetdef->name,
								'Dataset ID' => $file->dataset->id,
								'Dataset Name' => $file->dataset->name, 
								'Dataset Description' => $file->dataset->description,
								'Database ID' => $file->dataset->database->id,
								'Database Title' => $file->dataset->database->title,
						];
				});
				// Return the file data as a JSON response.
				return response()->json([
						'success' => true,
						'message' => 'Files retrieved successfully.',
						'data'    => $fileData,
				], 200); // 200 OK
			}
			catch (\Exception $e) 
			{
				// Handle any errors that occur during the process.
				return response()->json([
						'success' => false,
						'message' => 'Failed to retrieve files: ' . $e->getMessage(),
				], 500); // 500 Internal Server Error
			}
		}
		else
			return view('databases.download', ['database' => $database, 'type' => $type]);
	}

	public function showdatasets(Database $database)
	{
		return view('databases.showdatasets', ['database' => $database]);
	}

	/*
	 * Display Datatheck status
	 */
	public function datathek(Database $database)
	{

		return view('databases.datathek', [
			'database' => $database,
			'tabTitle' => 'Datathek Info'
		]);
	}

}
