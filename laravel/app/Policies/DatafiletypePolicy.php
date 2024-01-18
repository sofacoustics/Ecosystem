<?php

namespace App\Policies;

use App\Models\Datafiletype;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DatafiletypePolicy
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
    public function view(User $user, Datafiletype $datafiletype): bool
    {
			//
			return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
			if ($user->can('add datafiletypes'))
				return true;
			return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Datafiletype $datafiletype): bool
    {
			//
			if ($user->can('add datasettypes'))
				return true;
			return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Datafiletype $datafiletype): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Datafiletype $datafiletype): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Datafiletype $datafiletype): bool
    {
        //
    }
}
