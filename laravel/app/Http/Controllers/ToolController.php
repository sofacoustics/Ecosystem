<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Tool;
use App\Models\Database;
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
}
