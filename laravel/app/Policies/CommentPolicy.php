<?php

namespace App\Policies;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
	public function viewAny(?User $user) : Response 
	{
		return Response::allow();
	}

	public function view(?User $user, Comment $comment) : Response 
	{
		dd('view');
		return Response::allow();
	}


	public function create(User $user, $comment): Response
	{
		if($comment->database->visible === 1) {
			return Response::allow();
		}
		return Response::deny('You cannot create comments on a hidden database, no matter who you are!');
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, $comment): Response
	{
		if(isset($user->id))
		{
			if(auth()->user()->hasRole('admin'))
				$access = true;
			else
				$access = ($user->id == $comment->user_id); // allow only for owners
		}
		else
			$access = false; // do not allow if non-authorized
		return $access
			? Response::allow()
			: Response::deny('You can not edit this comment because you do not own it!');
	}
}
