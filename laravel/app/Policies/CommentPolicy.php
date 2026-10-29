<?php

namespace App\Policies;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
	/**
	 * Determine whether the user can update the model.
	 */
	public function update(?User $user, $comment): Response
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
