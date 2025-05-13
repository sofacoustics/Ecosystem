<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Database;

class CommentController extends Controller
{
	/*public function index(Database $database)
	{
		dd($database);
		$comments = Comment::with('database')->get();
		return view('databases.comments.index', compact('comments','database'));
	}
	*/
	public function edit(Comment $comment)
	{
		if($comment->commentable_type === 'App\Models\Database')
			return view('databases.comments.edit', ['database' =>$comment->commentable, 'comment' => $comment]);
		else
			return view('tools.comments.edit', ['tool' =>$comment->commentable, 'comment' => $comment]);
	}
	
	public function destroy(Comment $comment)
	{
		$comment->delete();
		return redirect()->back();
	}
}
