<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dataset;
use App\Http\Requests\StoreDatasetRequest;
use App\Http\Requests\UpdateDatasetRequest;

class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datasets = \App\Models\Dataset::all();
        return view('dataset\index', ['allDatasets' => $datasets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // https://pusher.com/blog/laravel-mvc-use/#controllers-creating-our-controller
        return view('dataset\create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDatasetRequest $request)
    {
        //jw:todo
    }

    /**
     * Display the specified resource.
     */
    public function show(Dataset $dataset)
    {
        $user = \App\Models\User::where('id', $dataset->uploader_id)->first();
        return view('dataset\show',[ 'dataset' => $dataset, 'user' => $user ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dataset $dataset)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatasetRequest $request, Dataset $dataset)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dataset $dataset)
    {
        //
    }
}
