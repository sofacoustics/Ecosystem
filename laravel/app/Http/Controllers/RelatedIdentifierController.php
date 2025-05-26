<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\RelatedIdentifier;
use App\Models\Database;
use App\Models\Tool;

class RelatedIdentifierController extends Controller
{
 	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.relatedidentifiers'))
		{
			$relatedidentifierable = Database::find($id);
			return view('databases.relatedidentifiers.index', ['relatedidentifierable' =>$relatedidentifierable]);
		}
		else
		{
			$relatedidentifierable = Tool::find($id);
			return view('tools.relatedidentifiers.index', ['relatedidentifierable' =>$relatedidentifierable]);
		}
	}
	
	public function edit(RelatedIdentifier $relatedidentifier)
	{
		if($relatedidentifier->relatedidentifierable_type === 'App\Models\Database')
			return view('databases.relatedidentifiers.edit', ['relatedidentifierable' =>$relatedidentifier->relatedidentifierable, 'relatedidentifier' => $relatedidentifier]);
		else
			return view('tools.relatedidentifiers.edit', ['relatedidentifierable' =>$relatedidentifier->relatedidentifierable, 'relatedidentifier' => $relatedidentifier]);
	}

	public function destroy(RelatedIdentifier $relatedidentifier)
	{
		$relatedidentifier->delete();
		return redirect()->back();
	}

}
