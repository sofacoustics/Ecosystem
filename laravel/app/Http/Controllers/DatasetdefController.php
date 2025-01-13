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
        return view('datasetdefs.index');
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
    public function store(StoreDatasetdefRequest $request)
    {
        //
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatasetdefRequest $request, Datasetdef $datasetdef)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Datasetdef $datasetdef)
    {
        //
    }
}
