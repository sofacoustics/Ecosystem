<?php

namespace App\Policies;

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
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Dataset $dataset): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
        return $user->id != 0;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Dataset $dataset): Response
    {
        // this is called from the controller edit function, when $this->authorize($dataset) is called.
        return $user->id == $dataset->uploader_id
            ? Response::allow()
            : Response::deny('You may not update this dataset, since you do not own it!');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Dataset $dataset): Response
    {
        //
        return $user->id == $dataset->uploader_id
            ? Response::allow()
            : Response::deny('You may not delete this dataset, since you do not own it!');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Dataset $dataset): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Dataset $dataset): bool
    {
        //
    }
}
