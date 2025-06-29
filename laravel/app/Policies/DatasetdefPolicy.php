<?php

namespace App\Policies;

use App\Models\Datasetdef;
use App\Models\Database;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DatasetdefPolicy
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
	public function view(User $user, Datasetdef $datasetdef): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can create models.
	 * Use: "@can('create', App\Models\Datasetdef::class)" in a blade
	 */
	public function create(User $user): bool
	{
		if ($user->can('add datasetdefs'))
			return true;
		else
			return false;
	}

	/**
	 * Determine whether the user can update the model. Admin role overwrites. 
	 */
	public function update(User $user, Datasetdef $datasetdef, Database $database): bool
	{
			// Admin can edit always
		if(auth()->user()->hasRole('admin'))
			return true; 
			// User can only edit datasets from their database and if persistent publication not requested yet
		if($user->id == $datasetdef->database->user_id && $database->radar_status < 2)
			return true;
		else
			return false;
			
	}

	/**
	 * Determine whether the user can delete the the datasetdef
	 *
	 * User can only delete the datasetdef if there are not datasets which use it!
	 */
	public function delete(User $user, Datasetdef $datasetdef, Database $database): bool
	{
		$nDatasets = count($datasetdef->database->datasets);
			// If datasets exist, definitions cannot be deleted
		if($nDatasets > 0)
			return false;
			// Admin can delete, if no datasets yet
		if(auth()->user()->hasRole('admin'))
			return true; 
			// User can only delete datasets from their database and if persistent publication not requested yet
		if($user->id == $datasetdef->database->user_id && $database->radar_status < 2)
			return true;
		else
			return false;
	}

	/**
	 * Determine whether the user can restore the model.
	 */
	public function restore(User $user, Datasetdef $datasetdef): bool
	{
		//
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 */
	public function forceDelete(User $user, Datasetdef $datasetdef): bool
	{
		//
	}
}
