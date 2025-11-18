<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Tool;
use App\Models\Database;
use App\Models\Creator;
use App\Models\Publisher;
use App\Models\Rightsholder;
use App\Models\Keyword;
use App\Models\RelatedIdentifier;
use App\Models\SubjectArea;

use Illuminate\Http\Request;
use App\Http\Resources\Json\JsonResource;

class ToolController extends Controller
{
	use AuthorizesRequests, ValidatesRequests;
	
	public function __construct()
	{
			// Users must be authenticated for all functions except index and show.
			// Guests will be redirected to login page
		$this->middleware('auth', ['except' => ['index', 'show']]);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index(Request $request)
	{
		//$tools = \App\Models\Tool::all();
		//return view('tools.index', ['allTools' => $tools]);
		$type = $request->input('type');  
		if ($type === "json")
		{
			try 
			{
				$tools = Tool::select('tools.*')->get(); // Select all order columns
				$data = $tools->map(function ($tool) { // Transform the data into a suitable format. 
						if($tool->filename) $url=asset($tool->url()); else $url=null;
						return [
								'ID' => $tool->id,
								'Title' => $tool->title,
								'Type' => \App\Models\Tool::resourcetypeDisplay($tool->resourcetype),
								'URL' => $url,
								'Subtitle' => $tool->additionaltitle,
								'Filename' => $tool->filename,
								'Production Year' => $tool->productionyear,
								'Created Date' => $tool->created_at,
								'Updated Date' => $tool->updated_at,
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
			$tools = \App\Models\Tool::all();
			return view('tools.index', ['allTools' => $tools]);
		}
	}		
	
	/**
	 * Display the specified resource.
	 */
	public function show(Tool $tool)
	{
		$user = \App\Models\User::where('id', $tool->user_id)->first();
		return view('tools.show',['tool' => $tool,  'user' => $user ]);
	}
	
	public function create()
	{
		$this->authorize('create', Tool::class);
		return view('tools.create');
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Tool $tool)
	{
		$this->authorize($tool);
		// delete tool. Note that due to onDelete('cascade') in files tool, the related files
		$tool->delete();
		return redirect()->route('tools.index')->with('success', 'Tool deleted successfully');
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Tool $tool)
	{
		$this->authorize($tool);
		return view('tools.edit', [ 'tool' => $tool ]);
	}

	/**
	 * Show the form for uploading the specified resource.
	 */
	public function upload(Tool $tool)
	{
		$this->authorize('own', $tool);
		return view('tools.upload', [ 'tool' => $tool ]);
	}

	/**
	 * Manage tool DOI options
	 */
	public function doi(Tool $tool)
	{
		$this->authorize('own', $tool);
		$user = \App\Models\User::where('id', $tool->user_id)->first();
		return view('tools.doi', [ 'tool' => $tool, 'user' => $user ]);
	}
	
	/*
	 * Display Datatheck status
	 */
	public function datathek(Tool $tool)
	{
		return view('tools.datathek', [
			'tool' => $tool,
			'tabTitle' => 'Datathek Info'
		]);
	}

	public function duplicate(Tool $tool)
	{
		$new = new Tool();
		$new->user_id = auth()->id();
		$new->title = $tool->title." (duplicate)";
		$new->additionaltitle = $tool->additionaltitle;
		$new->additionaltitletype = (\App\Models\Metadataschema::where('name', 'additionalTitleType')->where('value', 'Subtitle')->first()->id);  // fix to Subtitle
		$new->descriptiongeneral = $tool->descriptiongeneral;
		$new->descriptionabstract = $tool->descriptionabstract;
		$new->descriptionmethods = $tool->descriptionmethods;
		$new->descriptionremarks = $tool->descriptionremarks;
		$new->productionyear = strtolower($tool->productionyear);
		$new->publicationyear = $new->publicationyear; // set to default
		$new->language = $tool->language;
		$new->resourcetype = $tool->resourcetype; 
		$new->resource = $tool->resource; 
		$new->datasources = $tool->datasources;
		$new->software = $tool->software;
		$new->processing = $tool->processing;
		$new->relatedinformation = $tool->relatedinformation;
		$new->controlledrights = $tool->controlledrights;
		$new->additionalrights = $tool->additionalrights;
		$new->save();	
		
			// duplicate creators
		foreach($tool->creators as $creator)
		{
			$cr = new Creator();
			$cr->creatorable_id = $new->id;
			$cr->creatorable_type = $creator->creatorable_type;
			$cr->creatorName = $creator->creatorName;
			$cr->givenName = $creator->givenName;
			$cr->familyName = $creator->familyName;
			$cr->nameIdentifier = $creator->nameIdentifier;
			$cr->creatorAffiliation = $creator->creatorAffiliation;
			$cr->nameIdentifierSchemeIndex = $creator->nameIdentifierSchemeIndex; 
			$cr->affiliationIdentifier = $creator->affiliationIdentifier;
			$cr->affiliationIdentifierScheme = $creator->affiliationIdentifierScheme; 
			$cr->save();
		}
			// duplicate publishers
		foreach($tool->publishers as $publisher)
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
		foreach($tool->rightsholders as $rightsholder)
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
		foreach($tool->keywords as $keyword)
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
		foreach($tool->relatedidentifiers as $relatedidentifier)
		{
			$ri = new Relatedidentifier(); 
			$ri->relatedidentifierable_id = $new->id;
			$ri->relatedidentifierable_type = $relatedidentifier->relatedidentifierable_type;
			$ri->relationtype = $relatedidentifier->relationtype;
			$ri->relatedidentifiertype = $relatedidentifier->relatedidentifiertype; 
			$ri->name = $relatedidentifier->name;
			$ri->save();
		}
				// add a new relation to the old tool
		$ri = new RelatedIdentifier();
		$ri->relatedidentifierable_id = $new->id;
		$ri->relatedidentifierable_type = get_class($new);
		$ri->relationtype = \App\Models\Metadataschema::where('name', 'relationType')->where('value', 'IS_NEW_VERSION_OF')->first()->id;
		$ri->relatedidentifiertype = (\App\Models\Metadataschema::where('name', 'relatedIdentifierType')->where('value', 'URL')->first()->id); 
		$ri->name = "ECOSYSTEM_TOOL_".$tool->id; // prefix and id of the old tool
		$ri->save();
			// duplicate subject areas
		foreach($tool->subjectareas as $subjectarea)
		{
			$sa = new SubjectArea(); 
			$sa->subjectareaable_id = $new->id; 
			$sa->subjectareaable_type = $subjectarea->subjectareaable_type; 
			$sa->controlledSubjectAreaIndex = $subjectarea->controlledSubjectAreaIndex;
			$sa->additionalSubjectArea = $subjectarea->additionalSubjectArea; 
			$sa->save();
		}

		return redirect()->route('tools.edit', ['tool' => $new])->with('success', 'Tool duplicated successfully');
	}
	
}
