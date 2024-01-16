<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Database;
use App\Http\Requests\StoreDatabaseRequest;
use App\Http\Requests\UpdateDatabaseRequest;

class DatabaseController extends Controller
{
    public function __construct()
    {
        // https://laracasts.com/discuss/channels/general-discussion/apply-middleware-for-certain-methods?page=0
        //$this->middleware('auth', ['only' => ['create', 'edit']]);
        // Users must be authenticated for all functions except index and show.
        // Guests will be redirected to login page
        $this->middleware('auth', ['except' => ['index', 'show']]);
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
        Database::create($request->validated()); // https://dev.to/secmohammed/laravel-form-request-tips-tricks-2p12

        return redirect('databases')->with('success', "Database $request->title successfully created!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Database $database)
    {
        $user = \App\Models\User::where('id', $database->user_id)->first();
        return view('databases.show',[ 'database' => $database, 'user' => $user ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Database $database)
    {
        $this->authorize($database);
        //$this->authorize('update', $database);
/*        if(auth()->user()->cannot('edit', $database)) {
            abort(403);
        }
        */
        //
        return view('databases.edit', [ 'database' => $database ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatabaseRequest $request, Database $database)
    {
        $this->authorize($database);
        /*if($request->user()->cannot('update', $database)) {
            abort(403);
        }    */
        $database->update($request->all());
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
}
