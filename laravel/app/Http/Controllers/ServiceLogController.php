<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(ServiceLog $serviceLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceLog $serviceLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceLog $serviceLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceLog $serviceLog)
    {
        //
    }
		
	public function removejob($id)
	{
		DB::table('jobs')->where('id', $id)->delete();
		return redirect()->back();
	}

	/**
	 * Remove all queued Service jobs (App\Jobs\Service). Other jobs (e.g. RADAR uploads) are
	 * not touched, because removing them would leave their batch (and the publication) stuck.
	 */
	public function removealljobs()
	{
		$ids = [];
		foreach(DB::table('jobs')->select('id', 'payload')->cursor() as $job)
		{
			$payload = json_decode($job->payload, true);
			if(($payload['displayName'] ?? null) === \App\Jobs\Service::class)
				$ids[] = $job->id;
		}
		foreach(array_chunk($ids, 1000) as $chunk)
			DB::table('jobs')->whereIn('id', $chunk)->delete();
		return redirect()->back();
	}

	/**
	 * Remove a single service log entry. The status page is reloaded afterwards, so an older
	 * log of the same datafile becomes the latest one (and is listed if it failed too).
	 */
	public function removelog($id)
	{
		DB::table('service_logs')->where('id', $id)->delete();
		return redirect()->back();
	}
			
}
