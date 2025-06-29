<?php

namespace App\Policies;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ToolPolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(?User $user): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(?User $user, Tool $tool): Response
	{
		return Response::allow();
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user): bool
	{
		if($user->id != 0) // if logged in
			if ($user->hasVerifiedEmail()) // if email verified
				if ($user->orcid_verified_at != null) // if ORCID linked 
					return true;
		return false; 
	}

	/**
	 * Determine whether the user can update the model. Admin role overwrites.
	 */
	public function own(User $user, Tool $tool): Response
	{
		if(isset($user->id))
		{
			if(auth()->user()->hasRole('admin'))
				$access = true;
			else
				$access = ($user->id == $tool->user_id); // allow only for owners 
		}
		else
			$access = false;
		return $access
			? Response::allow()
			: Response::deny('You can not edit this tool because you do not own it!');
	}


	/**
	 * Determine whether the user can update the model.
	 */
	public function update(?User $user, Tool $tool): Response
	{
		if(isset($user->id))
		{
			if(auth()->user()->hasRole('admin'))
				$access = true;
			else
				$access = ($user->id == $tool->user_id) && ($tool->radar_status < 2); // allow only for owners and if not submitted for persistent publication yet
		}
		else
			$access = false; // do not allow if non-authorized
		return $access
			? Response::allow()
			: Response::deny('You can not update this tool because you do not own it or it is locked for persistent publication!');
	}


	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Tool $tool): Response
	{
		if(auth()->user()->hasRole('admin'))
			$access = true;
		else
			$access = ($user->id == $tool->user_id) && ($tool->radar_status < 2); // allow only for owners and if DOI not assigned yet
		return $access
			? Response::allow()
			: Response::deny('You can not delete this tool because you do not own it or it is locked for persistent publication!');
	}
}
