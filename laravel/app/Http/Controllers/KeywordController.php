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
			if($keywordable == null) return; // no entry found
			return view('databases.keywords.index', ['keywordable' =>$keywordable]);
		}
		else
		{
			$keywordable = Tool::find($id);
			if($keywordable == null) return; // no entry found
			return view('tools.keywords.index', ['keywordable' =>$keywordable]);
		}
	}
	
	public function edit(Keyword $keyword)
	{
		if($keyword->keywordable == null) return; // no database or tool found
		if($keyword->keywordable_type === 'App\Models\Database')
			return view('databases.keywords.edit', ['keywordable' =>$keyword->keywordable, 'keyword' => $keyword]);
		else
			return view('tools.keywords.edit', ['keywordable' =>$keyword->keywordable, 'keyword' => $keyword]);
	}

	public function destroy(Keyword $keyword)
	{
		if($keyword->keywordable_type === 'App\Models\Database')
			\App\Models\Database::find($keyword->keywordable_id)->touch();
		else
			\App\Models\Tool::find($keyword->keywordable_id)->touch();
		$keyword->delete();
		return redirect()->back();
	}

	public function copyKeyword(Keyword $A, Keyword $B)
	{
		$B->keywordName = $A->keywordName;
		$B->keywordSchemeIndex = $A->keywordSchemeIndex;
		$B->schemeURI = $A->schemeURI;
		$B->valueURI = $A->valueURI;
		$B->classificationCode = $A->classificationCode;
		$B->created_at = $A->created_at;
		$B->updated_at = $A->updated_at;
		return $B;
	}
	
	public function up($id)
	{
		$keywordA = Keyword::where('id', $id)->get()->first();
		$keywordB = Keyword::where('keywordable_id',$keywordA->keywordable_id)->where('id','<', $id)->get()->last();
		$temp = new Keyword;
		$temp = $this->copyKeyword($keywordA, $temp); 
		$keywordA = $this->copyKeyword($keywordB, $keywordA);
		$keywordB = $this->copyKeyword($temp, $keywordB);
		$keywordA->save(); 
		$keywordB->save();
		return redirect()->back();
	}

	public function down($id)
	{
		$keywordA = Keyword::where('id', $id)->get()->first();
		$keywordB = Keyword::where('keywordable_id',$keywordA->keywordable_id)->where('id','>', $id)->get()->first();
		$temp = new Keyword;
		$temp = $this->copyKeyword($keywordA, $temp); 
		$keywordA = $this->copyKeyword($keywordB, $keywordA);
		$keywordB = $this->copyKeyword($temp, $keywordB);
		$keywordA->save(); 
		$keywordB->save();
		return redirect()->back();
	}

}
