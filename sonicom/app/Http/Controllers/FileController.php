<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;

class FileController extends Controller
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
        $files = \App\Models\File::all();
        return view('files\index', ['allFiles' => $files]);
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
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        //
    }
}
