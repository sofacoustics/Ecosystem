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
			if($relatedidentifierable == null) return; // no entry found
			return view('databases.relatedidentifiers.index', ['relatedidentifierable' =>$relatedidentifierable]);
		}
		else
		{
			$relatedidentifierable = Tool::find($id);
			if($relatedidentifierable == null) return; // no entry found
			return view('tools.relatedidentifiers.index', ['relatedidentifierable' =>$relatedidentifierable]);
		}
	}
	
	public function edit(RelatedIdentifier $relatedidentifier)
	{
		if($relatedidentifier->relatedidentifierable == null) return; // no database or tool found
		if($relatedidentifier->relatedidentifierable_type === 'App\Models\Database')
			return view('databases.relatedidentifiers.edit', ['relatedidentifierable' =>$relatedidentifier->relatedidentifierable, 'relatedidentifier' => $relatedidentifier]);
		else
			return view('tools.relatedidentifiers.edit', ['relatedidentifierable' =>$relatedidentifier->relatedidentifierable, 'relatedidentifier' => $relatedidentifier]);
	}

	public function destroy(RelatedIdentifier $relatedidentifier)
	{
		if($relatedidentifier->relatedidentifierable_type === 'App\Models\Database')
			\App\Models\Database::find($relatedidentifier->relatedidentifierable_id)->touch();
		else
			\App\Models\Tool::find($relatedidentifier->relatedidentifierable_id)->touch();
		$relatedidentifier->delete();
		return redirect()->back();
	}

}
