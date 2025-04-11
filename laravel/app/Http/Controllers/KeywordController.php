<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Database;
use App\Models\Keyword;

class KeywordController extends Controller
{
    public function index(Database $database)
    {
        $keywords = Keyword::with('database')->get();

        return view('databases.keywords.index', compact('keywords','database'));
    }
		
    public function edit(Keyword $keyword)
    {
        return view('databases.keywords.edit', ['keyword' => $keyword]);
    }
		
    public function destroy(Keyword $keyword)
    {
        $keyword->delete();
        return redirect()->back();
    }
}
