<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Database;
use App\Models\Tool;

class PublisherController extends Controller
{
	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.publishers'))
		{
			$publisherable = Database::find($id);
			return view('databases.publishers.index', ['publisherable' =>$publisherable]);
		}
		else
		{
			$publisherable = Tool::find($id);
			return view('tools.publishers.index', ['publisherable' =>$publisherable]);
		}
	}
	
	public function edit(Publisher $publisher)
	{
		if($publisher->publisherable == null) return; // no database or tool found
		if($publisher->publisherable_type === 'App\Models\Database')
			return view('databases.publishers.edit', ['publisherable' =>$publisher->publisherable, 'publisher' => $publisher]);
		else
			return view('tools.publishers.edit', ['publisherable' =>$publisher->publisherable, 'publisher' => $publisher]);
	}		
	
	public function destroy(Publisher $publisher)
	{
		if($publisher->publisherable_type === 'App\Models\Database')
			\App\Models\Database::find($publisher->publisherable_id)->touch();
		else
			\App\Models\Tool::find($publisher->publisherable_id)->touch();
		$publisher->delete();
		return redirect()->back();
	}
}
