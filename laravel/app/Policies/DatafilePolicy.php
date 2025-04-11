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
    public function create(User $user, Dataset $dataset): bool
    {
		//dd('datafile create policy');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Datafile $datafile): bool
    {
        //
		//dd('datafile update policy');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Datafile $datafile): Response
    {
        //
        //dd('datafile policy delete');
        return $user->id == $datafile->dataset->database->user_id
            ? Response::allow()
            : Response::deny('You may not delete this datafile, since you do not own it!');
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
