<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use App\Models\SubjectArea;
use App\Models\Database;
use App\Models\Tool;

class SubjectAreaController extends Controller
{
	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.subjectareas'))
		{
			$subjectareaable = Database::find($id);
			if($subjectareaable == null) return; // no entry found
			return view('databases.subjectareas.index', ['subjectareaable' =>$subjectareaable]);
		}
		else
		{
			$subjectareaable = Tool::find($id);
			if($subjectareaable == null) return; // no entry found
			return view('tools.subjectareas.index', ['subjectareaable' =>$subjectareaable]);
		}
	}
	
	public function edit(SubjectArea $subjectarea)
	{
		if($subjectarea->subjectareaable == null) return; // no database or tool found
		if($subjectarea->subjectareaable_type === 'App\Models\Database')
			return view('databases.subjectareas.edit', ['subjectareaable' =>$subjectarea->subjectareaable, 'subjectarea' => $subjectarea]);
		else
			return view('tools.subjectareas.edit', ['subjectareaable' =>$subjectarea->subjectareaable, 'subjectarea' => $subjectarea]);
	}

	public function destroy(SubjectArea $subjectarea)
	{
		$subjectarea->delete();
		return redirect()->back();
	}
}
