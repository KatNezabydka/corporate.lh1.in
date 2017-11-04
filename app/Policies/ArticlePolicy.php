<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;

//Регистрируем класс в Providers
class ArticlePolicy
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
     * @param User $user - принимает объект модели User
     */
    public function save(User $user){
        return $user->canDo('ADD_ARTICLES');

    }

    public function edit(User $user){
        return $user->canDo('UPDATE_ARTICLES');

    }
}
