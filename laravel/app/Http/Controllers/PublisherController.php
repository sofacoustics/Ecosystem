<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Database;

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
		if($publisher->publisherable_type === 'App\Models\Database')
			return view('databases.publishers.edit', ['publisherable' =>$publisher->publisherable, 'publisher' => $publisher]);
		else
			return view('tools.publishers.edit', ['publisherable' =>$publisher->publisherable, 'publisher' => $publisher]);
	}		
	
	public function destroy(Publisher $publisher)
	{
		$publisher->delete();
		return redirect()->back();
	}
}
