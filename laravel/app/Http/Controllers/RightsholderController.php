<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rightsholder;
use App\Models\Database;


class RightsholderController extends Controller
{
    public function index(Database $database)
    {
        $rightsholders = Rightsholder::with('database')->get();

        return view('databases.rightsholders.index', compact('rightsholders','database'));
    }
		
    public function edit(Rightsholder $rightsholder)
    {
        return view('databases.rightsholders.edit', ['rightsholder' => $rightsholder]);
    }
		
    public function destroy(Rightsholder $rightsholder)
    {
        $rightsholder->delete();
        return redirect()->back();
    }

}
