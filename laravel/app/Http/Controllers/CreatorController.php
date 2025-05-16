<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\Creator;
use App\Models\Database;
use App\Models\Tool;

class CreatorController extends Controller
{
	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.creators'))
		{
			$commentable = Database::find($id);
			return view('databases.creators.index', ['commentable' =>$commentable]);
		}
		else
		{
			$commentable = Tool::find($id);
			return view('tools.creators.index', ['commentable' =>$commentable]);
		}
	}

	public function edit(Creator $creator)
	{
		if($creator->commentable_type === 'App\Models\Database')
			return view('databases.creators.edit', ['commentable' =>$creator->commentable, 'creator' => $creator]);
		else
			return view('tools.creators.edit', ['commentable' =>$creator->commentable, 'creator' => $creator]);
	}
	
	public function destroy(Creator $creator)
	{
		$creator->delete();
		return redirect()->back();
	}
}

