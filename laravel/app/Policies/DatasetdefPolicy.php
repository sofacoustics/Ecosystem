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
        //
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Datasetdef $datasetdef): bool
    {
        //
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //jw:note this appears to work, if you use "@can('create', App\Models\Datasetdef::class)"
        //jw:note in a blade template
        if ($user->can('add datasetdefs'))
			return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Datasetdef $datasetdef, Database $database): bool
    {
			// this code: User can only update a datsetdef if there are *no* datasets using it.
      //  $nDatasets = count($datasetdef->database->datasets);
      //  if ($nDatasets == 0 && $user->can('add datasetdefs') && $user->id == $datasetdef->database->user_id)
			//return true;
      //  return false;
			
				// User can only update their database
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
        return ($nDatasets == 0 && $user->id == $datasetdef->database->user_id && $database->radar_status < 2)
            ? true
            : false;
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
