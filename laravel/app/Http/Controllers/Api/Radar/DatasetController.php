<?php

namespace App\Http\Controllers\Api\Radar;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // guzzle

use App\Models\Database;

class DatasetController extends RadarController
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Void
    {
        $this->get("/datasets");

        //dd($body);
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
        $this->get("/datasets/$id");
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

    //jw:note these functions weren't created by make:controller --api
    public function testupdate(string $id)
    {
        $database = Database::find($id); // database model
        $json = $database->radardataset->toJson(); // RADAR dataset json
        $dataset_id = $database->radardataset->id;
        //dd($dataset_id);
        //dd(url()->previous());

        $body = $this->put("/datasets/$dataset_id", $json);

        //echo "$body";
        return redirect()->back();
        //return redirect($redirect);
        //
    }
}
