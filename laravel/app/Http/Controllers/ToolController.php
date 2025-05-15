<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Tool;
use App\Models\Database;

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
	public function index()
	{
			$tools = \App\Models\Tool::all();
			return view('tools.index', ['allTools' => $tools]);
	}		
	
	/**
	 * Display the specified resource.
	 */
	public function show(Tool $tool)
	{
			return view('tools.show',[ 'tool' => $tool]);
	}
	
	public function create()
	{
		$this->authorize('create', Database::class);
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
