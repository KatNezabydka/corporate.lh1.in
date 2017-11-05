<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * Принимает объект модели User
     */
    public function change(User $user)
    {
        //он делает с  EDIT_USERS
       return $user->canDo('EDIT_PERMISSIONS');
    }

}
