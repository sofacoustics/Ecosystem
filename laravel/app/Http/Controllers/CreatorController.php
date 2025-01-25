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
}
