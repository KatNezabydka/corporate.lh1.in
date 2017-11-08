<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

class PortfolioPolicy
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
        return $user->canDo('CREATE_PORTFOLIOS');
    }

    public function edit(User $user)
    {
        return $user->canDo('EDIT_PORTFOLIOS');
    }

    public function delete(User $user)
    {
        return $user->canDo('DELETE_PORTFOLIOS');
    }

}
