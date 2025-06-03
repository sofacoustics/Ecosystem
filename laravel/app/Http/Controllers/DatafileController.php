<?php

namespace App\Http\Controllers;

use App\Models\Datafile;
use App\Services\DatasetRadarBridge;
use App\Services\FileRadarBridge;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DatafileController extends Controller
{
	public function __construct()
	{
			// https://laracasts.com/discuss/channels/general-discussion/apply-middleware-for-certain-methods?page=0
			// Users must be authenticated for all functions except index and show. 
			// Guests will be redirected to login page
		$this->middleware('auth', ['except' => ['index', 'show']]);
	}
	
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		$datafiles = \App\Models\Datafile::all();
		return view('datafiles.index', ['allDatafiles' => $datafiles]);
}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		//
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
	public function show($id)
	{
		$datafile = \App\Models\Datafile::where('id', $id)->first();
		return view('datafiles.show', ['datafile' => $datafile]);
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(Datafile $file)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, Datafile $file)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(Datafile $datafile)
	{
		$datafile->dataset->touch();
		$datafile->dataset->database->touch();
		$datafile->delete();
		return redirect()->back()->with('status', 'Datafile deleted');
    }

	public function uploadtoradar(Datafile $datafile)
	{
		if($datafile->dataset->database->radar_id == null)
			return redirect()->back()->with('status', 'There is no RADAR dataset associated with this database');
		if($datafile->radar_id)
		{
			// file already uploaded to RADAR
			// do nothing
			return redirect()->back()->with('status', 'The file has already been uploaded to the RADAR server');
		}
		else
		{
			$radar = new DatasetRadarBridge($datafile->dataset->database);
			if($radar->canUpload($datafile->name))
			{
				//jw:todo  Maybe do this in a job?
				if($radar->upload($datafile))
				{
					return redirect()->back()->with('status', 'The file was successfully uploaded to the RADAR server');
				}
			}
			else
			{
				return redirect()->back()->with('status', 'A file with this name already exists on the RADAR server!');
			}
		}
		return redirect()->back()->with('status', 'There was some error uploading the file to the RADAR server');
	}

	public function deletefromradar(Datafile $datafile)
	{
		if($datafile->radar_id)
		{
			$radar = new FileRadarBridge($datafile);
			if($radar->delete())
			{
				return redirect()->back()->with('status', $radar->message);
			}
			else
			{
				return redirect()->back()->with('status', $radar->message.' ('.$radar->details.')');
			}
		}
		return redirect()->back()->with('status', 'This datafile is not associated with a RADAR file');
	}
}
