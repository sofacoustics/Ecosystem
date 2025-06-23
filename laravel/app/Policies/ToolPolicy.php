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
		//
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
	 * Determine whether the user can update the model.
	 */
	public function own(User $user, Tool $tool): Response
	{
		// this is called from the controller edit function, when $this->authorize($tool) is called.
		//dd($user);
		return ($user->id == $tool->user_id) // allow only for owners 
			? Response::allow()
			: Response::deny('You can not update this tool because you do not own it!');
	}


	/**
	 * Determine whether the user can update the model.
	 */
	public function update(?User $user, Tool $tool): Response
	{
		// this is called from the controller edit function, when $this->authorize($tool) is called.
		if (isset($user->id))
			$enable = ($user->id == $tool->user_id) && ($tool->radar_status < 2); // allow only for owners and if not submitted for persistent publication
		else
			$enable = false; // do not allow if non-authorized
		return $enable
			? Response::allow()
			: Response::deny('You can not update this tool because you do not own it or it is locked for persistent publication!');
	}


	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Tool $tool): Response
	{
		return ($user->id == $tool->user_id) && ($tool->radar_status < 2) // allow only for owners and if DOI not assigned yet
			? Response::allow()
			: Response::deny('You may not delete this tool, since you do not own it!');

	}


	/**
	 * Determine whether the user can restore the model.
	 */
/*    public function restore(User $user, Database $database): bool
	{
			//
	}
*/
	/**
	 * Determine whether the user can permanently delete the model.
	 */
/*    public function forceDelete(User $user, Database $database): bool
	{
			//
	} 
*/
}
