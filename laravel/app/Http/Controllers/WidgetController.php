<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Widget;

class WidgetController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
		
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
        $widgets = \App\Models\Widget::all();

        return view('widgets.index', ['allWidgets' => $widgets]);
    }		
    /**
     * Display the specified resource.
     */
    public function show(Widget $widget)
    {
        return view('widgets.show',[ 'widget' => $widget]);
    }
}
