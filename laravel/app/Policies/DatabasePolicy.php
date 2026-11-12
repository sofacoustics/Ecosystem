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
		return true; // anyone can view the database index page. Filter out hidden databases in the index() controller function
	}

	/**
	 * Determine whether the user can view the model. Admin role overwrites.
	 */
	public function view(?User $user, Database $database): Response
	{
		// Access only if public or owned by the user or admin
		if($database->visible === 1) {
			return Response::allow();
		}
    if ($user === null) {
        return Response::deny('You must be logged in to access this hidden database.');
    }
		if($user->hasRole('admin') || $user->id === $database->user_id) {
			return Response::allow();
		}
		return Response::deny('You must be logged in to access this hidden database.');
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
	 * Determine whether the user owns the model. Admin role overwrites.
	 */
	public function own(User $user, Database $database): Response
	{ 
		if(isset($user->id))
		{
			if(auth()->user()->hasRole('admin'))
				$access = true;
			else
				$access = ($user->id == $database->user_id); // allow only for owners and if not submitted for persistent publication yet
		}
		else
			$access = false;
		return $access
				? Response::allow()
				: Response::deny('You do not own this database!');
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
			$access = false; // do not allow if non-authorized
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
			: Response::deny('You can not delete this database because you do not own it or it is locked for persistent publication!');
	}


	/**
	 * Protect the download function
	 */
	public function download(?User $user, Database $database): Response
	{
		if($database->visible == 1) {
			return Response::allow();
		}
    if ($user === null) {
        return Response::deny('You must be logged in to access this hidden database.');
    }
		if($user->hasRole('admin') || $user->id === $database->user_id) {
			return Response::allow();
		}
		return Response::deny('You may not download this database because it is currently hidden!');
	}

	/**
	 * Protect 'comment' function
	 */
	public function comment(User $user, Database $database): Response
	{
		if($database->visible == 1) {
			return Response::allow();
		}
		// return Response::deny('You may not comment on hidden databases!');
		return Response::allow();
	}


}
