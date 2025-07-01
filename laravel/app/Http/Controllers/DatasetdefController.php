<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDatasetdefRequest;
use App\Http\Requests\UpdateDatasetdefRequest;
use App\Models\Datasetdef;

class DatasetdefController extends Controller
{
	public function __construct()
	{
			$this->middleware('auth', ['except' => ['index', 'show']]);
	}

	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
			//
			$datasetdefs = \App\Models\Datasetdef::all();
			return view('datasetdefs.index', ['allDatasetdefs' => $datasetdefs]);
	}


	/**
	 * Display the specified resource.
	 */
	public function show(Datasetdef $datasetdef)
	{
			return view('datasetdefs.show',[ 'datasetdef' => $datasetdef]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Datasetdef $datasetdef)
	{
			//
	return view('datasetdefs.edit', [ 'datasetdef' => $datasetdef]);
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Datasetdef $datasetdef)
	{
		$datasetdef->delete();
		return redirect()->back();
	}

	/**
	 * Duplicate a definition and add a number to the name. 
	 * Search if that new name exists, if yes, increase the number. 
	 */
	public function duplicate(Datasetdef $datasetdef)
	{
		$new = new Datasetdef();
		$datasetdefs = $datasetdef->database->datasetdefs;
		$idx=1;
		do
		{
			$idx++;
			$name = $datasetdef->name.' '.$idx;
			$found = 0;
			foreach($datasetdefs as $dat)
				if($dat->name === $name) $found=1;
		}
		while($found);
		$new->name = $name;
		$new->description = $datasetdef->description;
		$new->database_id = $datasetdef->database->id;
		$new->datafiletype_id = $datasetdef->datafiletype_id;
		$new->widget_id = $datasetdef->widget_id > 0 ? $datasetdef->widget_id : null;
		$new->save();
		$datasetdef->database->touch(); 
		return redirect()->route('databases.datasetdefs', $datasetdef->database->id);
	}
}
