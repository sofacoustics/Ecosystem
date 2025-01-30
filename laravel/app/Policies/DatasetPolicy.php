<?php

namespace App\Policies;

use App\Models\Database;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DatasetPolicy
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
    public function view(User $user, Dataset $dataset): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
	 *
	 * Note that we're passing a *database* here!
     */
    public function create(User $user, Database $database): bool
    {
		//dd('dataset "create" policy');
		if($user->id === $database->user_id)
			return true;
		return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dataset $dataset): bool
    {
        if($user->id === $dataset->database->user_id)
            return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dataset $dataset): bool
    {
        //
        //if($user->id == $dataset->database->user_id || $user->has_role('admin'))
        if($user->id === $dataset->database->user_id)
            return true;
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dataset $dataset): bool
    {
        if($user->id === $dataset->database->user_id)
            return true;
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dataset $dataset): bool
    {
        if($user->id === $dataset->database->user_id)
            return true;
        return false;
    }
}
