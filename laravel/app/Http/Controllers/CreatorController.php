<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Creator;
use App\Models\Database;

class CreatorController extends Controller
{
    public function index(Database $database)
    {
        $creators = Creator::with('database')->get();

        return view('databases.creators.index', compact('creators','database'));
    }
		
    public function edit(Creator $creator)
    {
        return view('databases.creators.edit', ['creator' => $creator]);
    }
		
    public function destroy(Creator $creator)
    {
        $creator->delete();
        return redirect()->back();
    }
}
