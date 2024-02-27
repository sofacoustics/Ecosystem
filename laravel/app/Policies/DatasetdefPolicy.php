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
        if ($user->can('add datasetdefs'))
			return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Datasetdef $datasetdef): bool
    {
        if ($user->can('add datasetdefs') && $user->id == $datasetdef->database->user_id)
			return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Datasetdef $datasetdef): bool
    {
        $database = Database::find($datasetdef->database_id);
        if($user->id == $database->user_id)
            return true;
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
