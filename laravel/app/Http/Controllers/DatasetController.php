<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dataset;
use App\Http\Requests\StoreDatasetRequest;
use App\Http\Requests\UpdateDatasetRequest;

class DatasetController extends Controller
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
        $datasets = \App\Models\Dataset::all();
        return view('datasets\index', ['allDatasets' => $datasets]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Dataset::class);
        // https://pusher.com/blog/laravel-mvc-use/#controllers-creating-our-controller
        return view('datasets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDatasetRequest $request)
    {
        Dataset::create($request->validated()); // https://dev.to/secmohammed/laravel-form-request-tips-tricks-2p12

        return redirect('datasets')->with('success', "Dataset $request->title successfully created!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Dataset $dataset)
    {
        $user = \App\Models\User::where('id', $dataset->uploader_id)->first();
        return view('datasets\show',[ 'dataset' => $dataset, 'user' => $user ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dataset $dataset)
    {
        $this->authorize($dataset);
        //$this->authorize('update', $dataset);
/*        if(auth()->user()->cannot('edit', $dataset)) {
            abort(403);
        }    
        */
        //
        return view('datasets.edit', [ 'dataset' => $dataset ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatasetRequest $request, Dataset $dataset)
    {
        $this->authorize($dataset);
        /*if($request->user()->cannot('update', $dataset)) {
            abort(403);
        }    */
        $dataset->update($request->all());
        return redirect('datasets')->with('success', 'Dataset successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dataset $dataset)
    {
        $this->authorize($dataset);
        // delete dataset. Note that due to onDelete('cascade') in files database, the related files
        // will be deleted too!
        $dataset->delete();
        return redirect()->route('datasets.index')->with('success', 'Dataset deleted successfully');
    }
}
