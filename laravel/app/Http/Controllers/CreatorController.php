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
			$creatorable = Database::find($id);
			return view('databases.creators.index', ['creatorable' =>$creatorable]);
		}
		else
		{
			$creatorable = Tool::find($id);
			return view('tools.creators.index', ['creatorable' =>$creatorable]);
		}
	}

	public function edit(Creator $creator)
	{
		if($creator->creatorable_type === 'App\Models\Database')
			return view('databases.creators.edit', ['creatorable' =>$creator->creatorable, 'creator' => $creator]);
		else
			return view('tools.creators.edit', ['creatorable' =>$creator->creatorable, 'creator' => $creator]);
	}
	
	public function destroy(Creator $creator)
	{
		if($creator->creatorable_type === 'App\Models\Database')
			\App\Models\Database::find($creator->creatorable_id)->touch();
		else
			\App\Models\Tool::find($creator->creatorable_id)->touch();
		$creator->delete();
		return redirect()->back();
	}
}