<?php

namespace App\Policies;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ToolPolicy
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
    public function view(User $user, Tool $tool): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->can('add tools'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tool $tool): bool
    {
        if($user->can('edit tools'))
            return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tool $tool): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tool $tool): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tool $tool): bool
    {
        //
    }
}
