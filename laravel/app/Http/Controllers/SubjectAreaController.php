<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectArea;
use App\Models\Database;

class SubjectAreaController extends Controller
{
    public function index(Database $database)
    {
        $subjectareas = SubjectArea::with('database')->get();

        return view('databases.subjectareas.index', compact('subjectareas','database'));
    }
		
    public function edit(SubjectArea $subjectarea)
    {
        return view('databases.subjectareas.edit', ['subjectarea' => $subjectarea]);
    }
		
    public function destroy(SubjectArea $subjectarea)
    {
        $subjectarea->delete();
        return redirect()->back();
    }
}
