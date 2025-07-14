<?php

namespace App\Policies;

use App\Models\Datafile;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DatafilePolicy
{
	/**
	 * Determine whether the user can view any models.
	 */
	public function viewAny(User $user): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can view the model.
	 */
	public function view(User $user, Datafile $datafile): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function create(User $user, Dataset $dataset): Response
	{
		if(auth()->user()->hasRole('admin'))
			$access = true;
		else
			$access = ($user->id == $datafile->dataset->database->user_id) && ($datafile->dataset->database->radar_status < 2); // allow only for owners and if DOI not assigned yet
		return $access
			? Response::allow()
			: Response::deny('You may not create a datafile for this database because you do not own it or it is locked for persistent publication!');
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Datafile $datafile): Response
	{
		if(auth()->user()->hasRole('admin'))
			$access = true;
		else
			$access = ($user->id == $datafile->dataset->database->user_id) && ($datafile->dataset->database->radar_status < 2); // allow only for owners and if DOI not assigned yet
		return $access
					? Response::allow()
					: Response::deny('You may not update this datafile because you do not own it or it is locked for persistent publication!');
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Datafile $datafile): Response
	{
		if(auth()->user()->hasRole('admin'))
			$access = true;
		else
			$access = ($user->id == $datafile->dataset->database->user_id) && ($datafile->dataset->database->radar_status < 2); // allow only for owners and if DOI not assigned yet
		return $access
			? Response::allow()
			: Response::deny('You may not delete this datafile because you do not own it or it is locked for persistent publication!');
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Datafile $datafile): bool
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Datafile $datafile): bool
	{
		//
	}
}
