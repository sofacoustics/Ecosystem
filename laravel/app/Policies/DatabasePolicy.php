<?php

namespace App\Policies;

use App\Models\Database;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DatabasePolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(?User $user): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can view the model. Admin role overwrites.
	 */
	public function view(User $user, Database $database): Response
	{
			// Access only if public or owned by the user or admin
		if (empty($user))
			$access = $database->visible;
		elseif(auth()->user()->hasRole('admin'))
			$access = true;
		else
			$access = ($user->id == $database->user_id) || $database->visible;
		return $access
					? Response::allow()
					: Response::deny('You can not see this database because it is not public and you do not own it!');
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
	public function own(User $user, Database $database): Response
	{ 
		if(isset($user->id))
		{
			if(auth()->user()->hasRole('admin'))
				$access = true;
			else
				$access = ($user->id == $database->user_id); // allow only for owners 
		}
		return $access
				? Response::allow()
				: Response::deny('You can not update this database because you do not own it!');
	}


	/**
	 * Determine whether the user can update the model. Admin role overwrites.
	 */
	public function update(?User $user, Database $database): Response
	{ 
		if(isset($user->id))
		{
			if(auth()->user()->hasRole('admin'))
				$access = true;
			else
				$access = ($user->id == $database->user_id) && ($database->radar_status < 2); // allow only for owners and if not submitted for persistent publication yet
		}
		else
			$enable = false; // do not allow if non-authorized
		return $access
				? Response::allow()
				: Response::deny('You can not update this database because you do not own it or it is locked for persistent publication!');
	}


	/**
	 * Determine whether the user can delete the model. Admin role overwrites.
	 */
	public function delete(User $user, Database $database): Response
	{
		if(auth()->user()->hasRole('admin'))
			$access = true;
		else
			$access = ($user->id == $database->user_id) && ($database->radar_status < 2); // allow only for owners and if DOI not assigned yet
		return $access
				? Response::allow()
				: Response::deny('You may not delete this database, since you do not own it!');

	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Database $database): bool
	{
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Database $database): bool
	{
	}
}
