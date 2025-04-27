<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Database;
use App\Models\Dataset;


class DatasetController extends Controller
{
    public function __construct()
    {
        // It would be possible to authorize functions using policy with this code,
        // which would mean we don't need to specify it in every function.
        // Since we are calling this controller via multiple routes, however, I'm
        // not sure how to do this.
        /*
        $this->authorizeResource(Dataset::class, 'dataset', [
            'except' => [ 'index', 'show', 'create' ],
        ]);
        */
    }
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
    {
        //dd($database);
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
        //
        //dd($dataset);
        $this->authorize('delete', $dataset);
        $dataset->delete();
        return redirect()->back();
    }
}
