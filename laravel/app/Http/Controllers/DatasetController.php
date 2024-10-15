<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Database;

class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($databaseId = 0)
    {
        //
        echo "databaseId = $databaseId<br>";
        $database = \App\Models\Database::where('id', $databaseId)->first();


        //
        //
        //print_r($database);
        //echo $database->name;
        //echo "name = " . $database->name() . "<br>";
        return view('datasets.index', ['database' => $database]);
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
    public function show(string $id)
    {
        $dataset = \App\Models\Dataset::where('id', $id)->first();
        return view('datasets.show', ['dataset' => $dataset]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
    public function destroy(string $id)
    {
        //
    }
}
