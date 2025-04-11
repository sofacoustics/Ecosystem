<?php

namespace App\Http\Controllers;

use Carbon;

use App\Models\User;
use App\Models\Database;
use App\Models\Radardataset;
use App\Models\Radardatasetresourcetype;

use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;

use App\Data\RadardatasetData;
use App\Data\RadardatasetpureData;
use App\Data\RadardatasetresourcetypeData;
use App\Data\RadardatasetsubjectareaData;
use App\Data\RadarcreatorData;
use App\Data\RadarpublisherData;

class DatabaseController extends Controller
{
    public function __construct()
    {
        // https://laracasts.com/discuss/channels/general-discussion/apply-middleware-for-certain-methods?page=0
        //$this->middleware('auth', ['only' => ['create', 'edit']]);
        // Users must be authenticated for all functions except index and show.
        // Guests will be redirected to login page
        $this->middleware('auth', ['except' => ['index', 'show', 'datasetdefs', 'radarShow']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $databases = \App\Models\Database::all();
        return view('databases.index', ['allDatabases' => $databases]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Database::class);
        // https://pusher.com/blog/laravel-mvc-use/#controllers-creating-our-controller
        return view('databases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDatabaseRequest $request)
    {
        // An alternative - where rules are in the model: https://medium.com/@konafets/a-better-place-for-your-validation-rules-in-laravel-f5e3f5b7cc
        Database::create($request->validated()); // https://dev.to/secmohammed/laravel-form-request-tips-tricks-2p12

        return redirect('databases')->with('success', "Database $request->title successfully created!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Database $database)
    {
        $this->authorize($database);
				$user = \App\Models\User::where('id', $database->user_id)->first();
        return view('databases.show',[ 'database' => $database, 'user' => $user ]);
    }

    /**
     * Manage database visibility options
     */
    public function visibility(Database $database)
    {
        $this->authorize('own', $database);
				$user = \App\Models\User::where('id', $database->user_id)->first();
        return view('databases.visibility', [ 'database' => $database, 'user' => $user ]);
        //return view('databases.visibility',[ 'database' => $database, 'user' => $user ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Database $database)
    {
        $this->authorize($database);
        return view('databases.edit', [ 'database' => $database ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        $this->authorize($database);
        $database->update($request->except('_method', '_token', 'submit'));
        return redirect('databases')->with('success', 'Database successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Database $database)
    {
        $this->authorize($database);
        // delete database. Note that due to onDelete('cascade') in files database, the related files
        // will be deleted too!
        $database->delete();
        return redirect()->route('databases.index')->with('success', 'Database deleted successfully');
    }


    public function radarShow(Database $database)
    {
        return view('databases.radar.show',[
           'database' => $database
        ]);
    }

    public function radarEdit(Database $database)
    {
        return view('databases.radar.edit',[
           'database' => $database
        ]);
    }

    public function datasetdefs(Database $database)
    {

        return view('databases.datasetdefs.index', [
            'database' => $database
        ]);
    }

    public function creators(Database $database)
    {

        return view('databases.creators.index', [
            'database' => $database
        ]);
    }

    public function upload(Database $database)
    {
        return view('databases.upload', [
            'database' => $database
        ]);
    }
}
