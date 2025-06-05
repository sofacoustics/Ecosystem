<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Database;
use App\Models\Dataset;

use App\Services\DatasetRadarFolderBridge;

class DatasetController extends Controller
{
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$datasets = \App\Models\Dataset::all();
		return view('datasets.index', ['datasets' => $datasets]);
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create(Database $database)
	{
		$this->authorize('create', $database);
		return view('datasets.create', ['database' => $database]);
	}

	public function bulkupload(Database $database)
	{		// PM: Do we need this function at all???
		return view('datasets.bulkupload', ['database' => $database]);
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(Dataset $dataset)
	{
		if (!($dataset->database->visible))
			$this->authorize('view', $dataset);	// if database not visible, check if the user allowed to see the dataset
		return view('datasets.show', ['dataset' => $dataset]);
	}


	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Dataset $dataset)
	{
		return view('datasets.edit', ['dataset' => $dataset]);
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Dataset $dataset)
	{
		$this->authorize('delete', $dataset);
		$dataset->database->touch();
		$dataset->delete();
		return redirect()->back();
	}

	/*
	 * Upload this dataset and it's datafiles to RADAR
	 */
	public function uploadtoradar(Dataset $dataset)
	{
		// create dataset folders
		$radar = new DatasetRadarFolderBridge($dataset);
		if(!$radar->upload())
		{
			return redirect()->back()->with('error', $radar->message.' ('.$radar->details.')');
		}
		else
		{
			return redirect()->back()->with('success', $radar->message);
		}
		//
		//
		// upload datafiles
		return redirect()->back()->with('error', 'Failed to upload to radar. Unknown error');
	}
}
