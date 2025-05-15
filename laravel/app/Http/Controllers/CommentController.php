<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Comment;
use App\Models\Database;
use App\Models\Tool;

class CommentController extends Controller
{
	public function index($id)
	{
		$route = Route::current();
		if($route->named('databases.comments'))
		{
			$commentable = Database::find($id);
			return view('databases.comments.index', ['commentable' =>$commentable]);
		}
		else
		{
			$commentable = Tool::find($id);
			return view('tools.comments.index', ['commentable' =>$commentable]);
		}
	}
	
	public function edit(Comment $comment)
	{
		if($comment->commentable_type === 'App\Models\Database')
			return view('databases.comments.edit', ['commentable' =>$comment->commentable, 'comment' => $comment]);
		else
			return view('tools.comments.edit', ['commentable' =>$comment->commentable, 'comment' => $comment]);
	}
	
	public function destroy(Comment $comment)
	{
		$comment->delete();
		return redirect()->back();
	}
}
