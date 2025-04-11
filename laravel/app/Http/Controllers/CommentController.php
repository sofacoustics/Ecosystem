<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Database;

class CommentController extends Controller
{
    public function index(Database $database)
    {
        $comments = Comment::with('database')->get();

        return view('databases.comments.index', compact('comments','database'));
    }
		
    public function edit(Comment $comment)
    {
        return view('databases.comments.edit', ['comment' => $comment]);
    }
		
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->back();
    }
}
