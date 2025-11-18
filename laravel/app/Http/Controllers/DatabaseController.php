<?php

namespace App\Http\Controllers;

use Carbon;

use App\Models\User;
use App\Models\Database;
use App\Models\Datafile;
use App\Models\Datasetdef;
use App\Models\Radardataset;
use App\Models\Radardatasetresourcetype;
use App\Models\Creator;
use App\Models\Publisher;
use App\Models\Rightsholder;
use App\Models\Keyword;
use App\Models\RelatedIdentifier;
use App\Models\SubjectArea;

use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;

use App\Http\Resources\DatabaseResource;
use App\Http\Resources\Json\JsonResource;

use App\Services\DatabaseRadarDatasetBridge;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

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
		if($database->radar_id)
		{
			$radar = new DatabaseRadarDatasetBridge($database);
			$radar->delete();	// Delete the database from RADAR
		}
		$database->delete(); // Note that due to onDelete('cascade') in files database, the related files will be deleted too!
		return redirect()->route('databases.index')->with('success', 'Database deleted successfully');
	}

	public function datasetdefs(Database $database)
	{
		$edits = false;
		$deletes = false;
		$user = auth()->user();
		foreach($database->datasetdefs as $datasetdef)
		{ 
			if(Gate::allows('update', [Datasetdef::class, $datasetdef, $database]))
			{ 
				$edits = true;
				break;
			}
		}
		foreach($database->datasetdefs as $datasetdef)
		{ 
			if(Gate::allows('delete', [Datasetdef::class, $datasetdef, $database]))
			{ 
				$deletes = true;
				break;
			}
		}
		$colspan=0;
		if($edits) $colspan=1; // button: edit
		if($deletes) $colspan=$colspan+2; // buttons: delete and duplicate
		if(count($database->datasetdefs)>1 & $deletes) $colspan=$colspan+2; // buttons: up and down
		return view('databases.datasetdefs.index', ['database'=>$database, 'edits'=>$edits, 'deletes'=>$deletes, 'colspan' =>$colspan]);
	}

	public function upload(Database $database)
	{
		return view('databases.upload', ['database' => $database]);
	}

	public function purge(Database $database)
	{
		return view('databases.purge', ['database' => $database]);
	}

	public function duplicate(Database $database)
	{
		
		$new = new Database();
		$new->user_id = auth()->id();
		$new->title = $database->title." (duplicate)";
		$new->additionaltitle = $database->additionaltitle;
		$new->additionaltitletype = (\App\Models\Metadataschema::where('name', 'additionalTitleType')->where('value', 'Subtitle')->first()->id);  // fix to Subtitle
		$new->descriptiongeneral = $database->descriptiongeneral;
		$new->descriptionabstract = $database->descriptionabstract;
		$new->descriptionmethods = $database->descriptionmethods;
		$new->descriptionremarks = $database->descriptionremarks;
		$new->productionyear = strtolower($database->productionyear);
		$new->publicationyear = $new->publicationyear; // set to default
		$new->language = $database->language;
		$new->resourcetype = null; 
		$new->resource = "SONICOM Ecosystem"; 
		$new->datasources = $database->datasources;
		$new->software = $database->software;
		$new->processing = $database->processing;
		$new->relatedinformation = $database->relatedinformation;
		$new->controlledrights = $database->controlledrights;
		$new->additionalrights = $database->additionalrights;
		$new->save();	
		
			// duplicate creators
		foreach($database->creators as $creator)
		{
			$cr = new Creator();
			$cr->creatorable_id = $new->id;
			$cr->creatorable_type = $creator->creatorable_type;
			$cr->creatorName = $creator->creatorName;
			$cr->givenName = $creator->givenName;
			$cr->familyName = $creator->familyName;
			$cr->nameIdentifier = $creator->nameIdentifier;
			$cr->creatorAffiliation = $creator->creatorAffiliation;
			$cr->nameIdentifierSchemeIndex = $creator->nameIdentifierSchemeIndex; // ORCID	
			$cr->affiliationIdentifier = $creator->affiliationIdentifier;
			$cr->affiliationIdentifierScheme = $creator->affiliationIdentifierScheme; // ROR
			$cr->save();
		}
			// duplicate publishers
		foreach($database->publishers as $publisher)
		{
			$pub = new Publisher(); 
			$pub->publisherable_id = $new->id;
			$pub->publisherable_type = $publisher->publisherable_type; 
			$pub->publisherName = $publisher->publisherName; 
			$pub->nameIdentifier = $publisher->nameIdentifier; 
			$pub->nameIdentifierSchemeIndex = $publisher->nameIdentifierSchemeIndex;
			$pub->save();
		}
			// duplicate rightholders
		foreach($database->rightsholders as $rightsholder)
		{
			$rh = new Rightsholder(); 
			$rh->rightsholderable_id = $new->id;
			$rh->rightsholderable_type = $rightsholder->rightsholderable_type;
			$rh->rightsholderName = $rightsholder->rightsholderName;
			$rh->nameIdentifier = $rightsholder->nameIdentifier;
			$rh->nameIdentifierSchemeIndex = $rightsholder->nameIdentifierSchemeIndex;
			$rh->schemeURI = $rightsholder->schemeURI;
			$rh->save();
		}		
			// duplicate keywords
		foreach($database->keywords as $keyword)
		{
			$kw = new Keyword(); 
			$kw->keywordable_id = $new->id;
			$kw->keywordable_type = $keyword->keywordable_type;
			$kw->keywordName = $keyword->keywordName;
			$kw->keywordSchemeIndex = $keyword->keywordSchemeIndex;
			$kw->schemeURI = $keyword->schemeURI;
			$kw->valueURI = $keyword->valueURI;
			$kw->classificationCode = $keyword->classificationCode;
			$kw->save();
		}		
			// duplicate relations
		foreach($database->relatedidentifiers as $relatedidentifier)
		{
			$ri = new Relatedidentifier(); 
			$ri->relatedidentifierable_id = $new->id;
			$ri->relatedidentifierable_type = $relatedidentifier->relatedidentifierable_type;
			$ri->relationtype = $relatedidentifier->relationtype;
			$ri->relatedidentifiertype = $relatedidentifier->relatedidentifiertype; 
			$ri->name = $relatedidentifier->name;
			$ri->save();
		}
				// add a new relation to the old database
		$ri = new RelatedIdentifier();
		$ri->relatedidentifierable_id = $new->id;
		$ri->relatedidentifierable_type = get_class($new);
		$ri->relationtype = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_NEW_VERSION_OF')->first()->id;
		$ri->relatedidentifiertype = (\App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id); 
		$ri->name = "ECOSYSTEM_DATABASE_".$database->id; // prefix and id of the old database
		$ri->save();
			// duplicate subject areas
		foreach($database->subjectareas as $subjectarea)
		{
			$sa = new SubjectArea(); 
			$sa->subjectareaable_id = $new->id; 
			$sa->subjectareaable_type = $subjectarea->subjectareaable_type; 
			$sa->controlledSubjectAreaIndex = $subjectarea->controlledSubjectAreaIndex;
			$sa->additionalSubjectArea = $subjectarea->additionalSubjectArea; 
			$sa->save();
		}
			// duplicate dataset definitions
		foreach($database->datasetdefs as $datasetdef)
		{
			$sa = new Datasetdef(); 
			$sa->database_id = $new->id;
			$sa->name = $datasetdef->name;
			$sa->description = $datasetdef->description;
			$sa->datafiletype_id = $datasetdef->datafiletype_id;
			$sa->widget_id = $datasetdef->widget_id; 
			$sa->save();
		}

		return redirect()->route('databases.edit', ['database' => $new])->with('success', 'Database duplicated successfully');
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

	public function copyDatasetdef(Datasetdef $A, Datasetdef $B)
	{
		$B->name = $A->name;
		$B->description = $A->description;
		$B->datafiletype_id = $A->datafiletype_id;
		$B->bulk_upload_filename_filter = $A->bulk_upload_filename_filter;
		$B->bulk_upload_pattern_description = $A->bulk_upload_pattern_description;
		$B->widget_id = $A->widget_id;
		$B->created_at = $A->created_at;
		$B->updated_at = $A->updated_at;
		return $B;
	}
	
	public function datasetdefup($id)
	{
		$datasetdefA = Datasetdef::where('id', $id)->get()->first();
		$datasetdefB = Datasetdef::where('database_id',$datasetdefA->database_id)->where('id','<', $id)->get()->last();
		$temp = new Datasetdef;
		$temp = $this->copyDatasetdef($datasetdefA, $temp); 
		$datasetdefA = $this->copyDatasetdef($datasetdefB, $datasetdefA);
		$datasetdefB = $this->copyDatasetdef($temp, $datasetdefB);
		$datasetdefA->save(); 
		$datasetdefB->save();
		return redirect()->back();
	}

	public function datasetdefdown($id)
	{
		$datasetdefA = Datasetdef::where('id', $id)->get()->first();
		$datasetdefB = Datasetdef::where('database_id',$datasetdefA->database_id)->where('id','>', $id)->get()->first();
		$temp = new Datasetdef;
		$temp = $this->copyDatasetdef($datasetdefA, $temp); 
		$datasetdefA = $this->copyDatasetdef($datasetdefB, $datasetdefA);
		$datasetdefB = $this->copyDatasetdef($temp, $datasetdefB);
		$datasetdefA->save(); 
		$datasetdefB->save();
		return redirect()->back();
	}
}
