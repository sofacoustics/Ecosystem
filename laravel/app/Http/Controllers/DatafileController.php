<?php

namespace App\Http\Controllers;

use App\Models\Datafile;
use App\Services\DatabaseRadarDatasetBridge;
use App\Services\DatasetRadarFolderBridge;
use App\Services\DatafileRadarFileBridge;

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
		// create RADAR dataset for the database first
		if($datafile->dataset->database->radar_id == null)
		{
			$radardataset = new DatabaseRadarDatasetBridge($datafile->dataset->database);
			if(!$radardataset->create())
			{
				return redirect()->back()->with('error', $radardataset->message.' ('.$radardataset->details.')');
			}
		}
		// create RADAR folder for the dataset
		if($datafile->dataset->radar_id == null)
		{
			$radarfolder = new DatasetRadarFolderBridge($datafile->dataset);
			if(!$radarfolder->create())
			{
				return redirect()->back()->with('error', $radarfolder->message.' ('.$radarfolder->details.')');
			}
		}
		if($datafile->radar_id)
		{
			// file already uploaded to RADAR
			// do nothing
			return redirect()->back()->with('success', 'The file has already been uploaded to the RADAR server');
		}
		else
		{
			$radarfile = new DatafileRadarFileBridge($datafile);
			//jw:todo  Maybe do this in a job?
			if($radarfile->upload($datafile))
			{
				return redirect()->back()->with('success', $radarfile->message);
			}
			else
			{
				return redirect()->back()->with('error', $radarfile->message .' ('.$radarfile->details.')');
			}
		}
		return redirect()->back()->with('error', 'There was some error uploading the file to the RADAR server');
	}

	public function deletefromradar(Datafile $datafile)
	{
		if($datafile->radar_id)
		{
			$radar = new DatafileRadarFileBridge($datafile);
			if($radar->delete())
			{
				return redirect()->back()->with('success', $radar->message);
			}
			else
			{
				return redirect()->back()->with('error', $radar->message.' ('.$radar->details.')');
			}
		}
		return redirect()->back()->with('error', 'This datafile is not associated with a RADAR file');
	}
}
