<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Creator;
use App\Models\Database;

class CreatorController extends Controller
{
    public function index() 
    {
        $creators = Creator::with('database')->get(); 

        return view('creators.index', compact('creators'));
    }
		
    public function edit(Creator $creator)
    {
        return view('creators.edit', ['creator' => $creator]);
    }
		
    public function destroy(Creator $creator)
    {
        $creator->delete();
        return redirect()->back();
    }
}
