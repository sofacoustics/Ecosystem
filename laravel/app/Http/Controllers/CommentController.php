<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Comment;
use App\Models\Database;

class CommentController extends Controller
{
	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.comments'))
		{
			$commentable = Database::find($id);
			return view('databases.comments.index', ['database' =>$commentable]);
		}
		else
		{
			$commentable = Tool::find($id);
			return view('tools.comments.index', ['tool' =>$commentable]);
		}
	}
	
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
