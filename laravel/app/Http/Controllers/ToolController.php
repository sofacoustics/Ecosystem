<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Tool;

class ToolController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
		
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
        $tools = \App\Models\Tool::all();

        return view('tools.index', ['allTools' => $tools]);
    }		
    /**
     * Display the specified resource.
     */
    public function show(Tool $tool)
    {
        return view('tools.show',[ 'tool' => $tool]);
    }
}
