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
			if($creatorable == null) return; // no entry found
			return view('databases.creators.index', ['creatorable' =>$creatorable]);
		}
		else
		{
			$creatorable = Tool::find($id);
			if($creatorable == null) return; // no entry found
			return view('tools.creators.index', ['creatorable' =>$creatorable]);
		}
	}

	public function edit(Creator $creator)
	{
		if($creator->creatorable == null) return; // no database or tool found
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
	
	public function copyCreator(Creator $A, Creator $B)
	{
		$B->creatorName = $A->creatorName;
		$B->givenName = $A->givenName;
		$B->familyName = $A->familyName;
		$B->nameIdentifier = $A->nameIdentifier;
		$B->nameIdentifierSchemeIndex = $A->nameIdentifierSchemeIndex;
		$B->creatorAffiliation = $A->creatorAffiliation;
		$B->affiliationIdentifierScheme = $A->affiliationIdentifierScheme;
		$B->affiliationIdentifier = $A->affiliationIdentifier;
		$B->created_at = $A->created_at;
		$B->updated_at = $A->updated_at;
		return $B;
	}
	
	public function up($id)
	{
		$creatorA = Creator::where('id', $id)->get()->first();
		$creatorB = Creator::where('creatorable_id',$creatorA->creatorable_id)->where('id','<', $id)->get()->last();
		$temp = new Creator;
		$temp = $this->copyCreator($creatorA, $temp); 
		$creatorA = $this->copyCreator($creatorB, $creatorA);
		$creatorB = $this->copyCreator($temp, $creatorB);
		$creatorA->save(); 
		$creatorB->save();
		return redirect()->back();
	}

	public function down($id)
	{
		$creatorA = Creator::where('id', $id)->get()->first();
		$creatorB = Creator::where('creatorable_id',$creatorA->creatorable_id)->where('id','>', $id)->get()->first();
		$temp = new Creator;
		$temp = $this->copyCreator($creatorA, $temp); 
		$creatorA = $this->copyCreator($creatorB, $creatorA);
		$creatorB = $this->copyCreator($temp, $creatorB);
		$creatorA->save(); 
		$creatorB->save();
		return redirect()->back();
	}

}