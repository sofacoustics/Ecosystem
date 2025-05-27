<?php

namespace App\Policies;

use App\Models\Database;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DatabasePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
	    //
	    return true;
    }

    /* This seems to have no effect
		public function show(?User $user, Database $database): bool
    {
				dd($database);
        return true;
    }*/ 
		
    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Database $database): Response
    {
				// Access only if public or owned by the user
			if (empty($user))
				$access = $database->visible;
			else
				$access = ($user->id == $database->user_id) || $database->visible;
      return $access
            ? Response::allow()
            : Response::deny('You can not see this database because it is not public and you do not own it!');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->id != 0) 
					if ($user->orcid_verified_at != null)
						return true;
				return false; 
    }

    /**
     * Determine whether the user can update the model.
     */
    public function own(User $user, Database $database): Response
    {
        // this is called from the controller edit function, when $this->authorize($database) is called.
				//dd($user);
        return ($user->id == $database->user_id) // allow only for owners 
            ? Response::allow()
            : Response::deny('You can not update this database because you do not own it!');
    }


    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Database $database): Response
    {
        // this is called from the controller edit function, when $this->authorize($database) is called.
				if (isset($user->id))
					$enable = ($user->id == $database->user_id) && ($database->radarstatus < 2); // allow only for owners and if not submitted for persistent publication
				else
					$enable = false; // do not allow if non-authorized
        return $enable
            ? Response::allow()
            : Response::deny('You can not update this database because you do not own it or it is locked for persistent publication!');
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Database $database): Response
    {
        return ($user->id == $database->user_id) && ($database->radarstatus < 2) // allow only for owners and if not submitted for persistent publication
            ? Response::allow()
            : Response::deny('You may not delete this database, since you do not own it!');

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Database $database): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Database $database): bool
    {
        //
    }
}
