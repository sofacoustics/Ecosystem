<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Creator;
use App\Models\Database;

class CreatorController extends Controller
{
    public function index() // product --> single category
														// creator --> single database
    {
        $creators = Creator::with('database')->get(); 

        return view('creators.index', compact('creators'));
    }
		
    public function edit(Creator $creator)
    {
        //
		return view('creators.edit', [ 'creator' => $creator]);
    }
		
    /*public function destroy(Datasetdef $datasetdef)
    {
        $datasetdef->delete();
        return redirect()->back();
    }*/

}
