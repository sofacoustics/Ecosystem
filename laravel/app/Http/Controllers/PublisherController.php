<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publisher;
use App\Models\Database;

class PublisherController extends Controller
{
    public function index(Database $database)
    {
        $publishers = Publisher::with('database')->get();

        return view('databases.publishers.index', compact('publishers','database'));
    }
		
    public function edit(Publisher $publisher)
    {
        return view('databases.publishers.edit', ['publisher' => $publisher]);
    }
		
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();
        return redirect()->back();
    }
}
