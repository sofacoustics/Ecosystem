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
			if($publisherable == null) return; // no entry found
			return view('databases.publishers.index', ['publisherable' =>$publisherable]);
		}
		else
		{
			$publisherable = Tool::find($id);
			if($publisherable == null) return; // no entry found
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
	public function copyPublisher(Publisher $A, Publisher $B)
	{
		$B->publisherName = $A->publisherName;
		$B->nameIdentifier = $A->nameIdentifier;
		$B->nameIdentifierSchemeIndex = $A->nameIdentifierSchemeIndex;
		$B->schemeURI = $A->schemeURI;
		$B->created_at = $A->created_at;
		$B->updated_at = $A->updated_at;
		return $B;
	}
	
	public function up($id)
	{
		$publisherA = Publisher::where('id', $id)->get()->first();
		$publisherB = Publisher::where('publisherable_id',$publisherA->publisherable_id)->where('id','<', $id)->get()->last();
		//dd([$publisherA->id, $publisherB->id]); 
		$temp = new Publisher;
		$temp = $this->copyPublisher($publisherA, $temp); 
		$publisherA = $this->copyPublisher($publisherB, $publisherA);
		$publisherB = $this->copyPublisher($temp, $publisherB);
		$publisherA->save(); 
		$publisherB->save();
		return redirect()->back();
	}

	public function down($id)
	{
		$publisherA = Publisher::where('id', $id)->get()->first();
		$publisherB = Publisher::where('publisherable_id',$publisherA->publisherable_id)->where('id','>', $id)->get()->first();
		//dd([$publisherA->id, $publisherB->id]); 
		$temp = new Publisher;
		$temp = $this->copyPublisher($publisherA, $temp); 
		$publisherA = $this->copyPublisher($publisherB, $publisherA);
		$publisherB = $this->copyPublisher($temp, $publisherB);
		$publisherA->save(); 
		$publisherB->save();
		return redirect()->back();
	}
}
