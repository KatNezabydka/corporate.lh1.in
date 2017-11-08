<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class UserPolicy
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

    public function save(User $user)
    {
        return $user->canDo('CREATE_USERS');
    }

    public function update(User $user)
    {
        return $user->canDo('CHANGE_USERS');
    }

    public function delete(User $user)
    {
        return $user->canDo('DELETE_USERS');
    }


}

