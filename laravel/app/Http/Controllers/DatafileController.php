<?php

namespace App\Http\Controllers;

use App\Models\Datafile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class DatafileController extends Controller
{
    public function __construct()
    {
        // https://laracasts.com/discuss/channels/general-discussion/apply-middleware-for-certain-methods?page=0
        // Users must be authenticated for all functions except index and show. 
        // Guests will be redirected to login page
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $datafiles = \App\Models\Datafile::all();
        return view('datafiles.index', ['allDatafiles' => $datafiles]);
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
    public function show($id)
    {
        $datafile = \App\Models\Datafile::where('id', $id)->first();
        return view('datafiles.show', ['datafile' => $datafile]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Datafile $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Datafile $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Datafile $datafile)
    {
        //$datafile = Datafile::find($id);
        //$this->authorize('delete', $datafile);
        // delete datafile model and associated files
        $datafile->delete();
        return redirect()->back()->with('status', 'Datafile deleted');
    }
}
