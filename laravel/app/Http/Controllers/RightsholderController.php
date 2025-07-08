<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\Rightsholder;
use App\Models\Database;
use App\Models\Tool;

class RightsholderController extends Controller
{
	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.rightsholders'))
		{
			$rightsholderable = Database::find($id);
			if($rightsholderable == null) return; // no entry found
			return view('databases.rightsholders.index', ['rightsholderable' =>$rightsholderable]);
		}
		else
		{
			$rightsholderable = Tool::find($id);
			if($rightsholderable == null) return; // no entry found
			return view('tools.rightsholders.index', ['rightsholderable' =>$rightsholderable]);
		}
	}
	
	public function edit(Rightsholder $rightsholder)
	{
		if($rightsholder->rightsholderable == null) return; // no database or tool found
		if($rightsholder->rightsholderable_type === 'App\Models\Database')
			return view('databases.rightsholders.edit', ['rightsholderable' =>$rightsholder->rightsholderable, 'rightsholder' => $rightsholder]);
		else
			return view('tools.rightsholders.edit', ['rightsholderable' =>$rightsholder->rightsholderable, 'rightsholder' => $rightsholder]);
	}

	public function destroy(Rightsholder $rightsholder)
	{
		if($rightsholder->rightsholderable_type === 'App\Models\Database')
			\App\Models\Database::find($rightsholder->rightsholderable_id)->touch();
		else
			\App\Models\Tool::find($rightsholder->rightsholderable_id)->touch();
		$rightsholder->delete();
		return redirect()->back();
	}

	public function copyRightsholder(Rightsholder $A, Rightsholder $B)
	{
		$B->rightsholderName = $A->rightsholderName;
		$B->nameIdentifier = $A->nameIdentifier;
		$B->nameIdentifierSchemeIndex = $A->nameIdentifierSchemeIndex;
		$B->schemeURI = $A->schemeURI;
		$B->created_at = $A->created_at;
		$B->updated_at = $A->updated_at;
		return $B;
	}
	
	public function up($id)
	{
		$rightsholderA = Rightsholder::where('id', $id)->get()->first();
		$rightsholderB = Rightsholder::where('rightsholderable_id',$rightsholderA->rightsholderable_id)->where('id','<', $id)->get()->last();
		$temp = new Rightsholder;
		$temp = $this->copyRightsholder($rightsholderA, $temp); 
		$rightsholderA = $this->copyRightsholder($rightsholderB, $rightsholderA);
		$rightsholderB = $this->copyRightsholder($temp, $rightsholderB);
		$rightsholderA->save(); 
		$rightsholderB->save();
		return redirect()->back();
	}

	public function down($id)
	{
		$rightsholderA = Rightsholder::where('id', $id)->get()->first();
		$rightsholderB = Rightsholder::where('rightsholderable_id',$rightsholderA->rightsholderable_id)->where('id','>', $id)->get()->first();
		$temp = new Rightsholder;
		$temp = $this->copyRightsholder($rightsholderA, $temp); 
		$rightsholderA = $this->copyRightsholder($rightsholderB, $rightsholderA);
		$rightsholderB = $this->copyRightsholder($temp, $rightsholderB);
		$rightsholderA->save(); 
		$rightsholderB->save();
		return redirect()->back();
	}
}
