<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\Database;
use App\Models\Keyword;
use App\Models\Tool;

class KeywordController extends Controller
{
	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.keywords'))
		{
			$keywordable = Database::find($id);
			return view('databases.keywords.index', ['keywordable' =>$keywordable]);
		}
		else
		{
			$keywordable = Tool::find($id);
			return view('tools.keywords.index', ['keywordable' =>$keywordable]);
		}
	}
	
	public function edit(Keyword $keyword)
	{
		if($keyword->keywordable_type === 'App\Models\Database')
			return view('databases.keywords.edit', ['keywordable' =>$keyword->keywordable, 'keyword' => $keyword]);
		else
			return view('tools.keywords.edit', ['keywordable' =>$keyword->keywordable, 'keyword' => $keyword]);
	}

	public function destroy(Keyword $keyword)
	{
		$keyword->delete();
		return redirect()->back();
	}
}
