<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\Rightsholder;
use App\Models\Database;

class RightsholderController extends Controller
{
	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.rightsholders'))
		{
			$rightsholderable = Database::find($id);
			return view('databases.rightsholders.index', ['rightsholderable' =>$rightsholderable]);
		}
		else
		{
			$rightsholderable = Tool::find($id);
			return view('tools.rightsholders.index', ['rightsholderable' =>$rightsholderable]);
		}
	}
	
	public function edit(Rightsholder $rightsholder)
	{
		if($rightsholder->rightsholderable_type === 'App\Models\Database')
			return view('databases.rightsholders.edit', ['rightsholderable' =>$rightsholder->rightsholderable, 'rightsholder' => $rightsholder]);
		else
			return view('tools.rightsholders.edit', ['rightsholderable' =>$rightsholder->rightsholderable, 'rightsholder' => $rightsholder]);
	}

	public function destroy(Rightsholder $rightsholder)
	{
		$rightsholder->delete();
		return redirect()->back();
	}

}
