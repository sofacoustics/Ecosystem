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
     * Determine whether the user can view the dataset.
     */
    public function view(User $user, Dataset $dataset): Response
    {
			$access = (($user->id === $dataset->database->user_id) || $dataset->database->visible);
			return $access
				? Response::allow()
				: Response::deny('You can not view this dataset because it is not public and you do not own it! \n(' . 
						$user->id . " " . $dataset->database->user_id . " " . $dataset->database->visible .")");
						
			//return true; 
    }

    /**
     * Determine whether the user can create models.
	 *
	 * Note that we're passing a *database* here!
     */
    public function create(User $user, Database $database): Response
    {
			$access = ($user->id == $database->user_id && $database->radar_status < 2);
			return $access
            ? Response::allow()
            : Response::deny('You can not create a dataset because it is not public and you do not own it!');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dataset $dataset): bool
    {
			if($user->id === $dataset->database->user_id && $dataset->database->radar_status < 2)
					return true;
			return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dataset $dataset): bool
    {
			if($user->id === $dataset->database->user_id && $dataset->database->radar_status < 2)
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
