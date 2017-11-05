<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\User;
use App\Article;

//Регистрируем класс в Providers
class ArticlePolicy
{
    use HandlesAuthorization;


    /**
     * ArticlePolicy constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @return bool
     * принимает объект модели User
     */
    public function save(User $user){
        return $user->canDo('ADD_ARTICLES');

    }

    /**
     * @param User $user
     * @return bool
     */
    public function edit(User $user){
        return $user->canDo('UPDATE_ARTICLES');

    }

    /**
     * принимает объект модели User и обьект модели Article
     * @param User $user
     * @param Article $article
     * @return bool
     * Проверяем этот ли юзер добавил статью и есть ли у него право удалять ее
     */
    public function destroy(User $user,Article $article){
        return ($user->canDo('DELETE_ARTICLES') && ($user->id == $article->user_id));


    }
}
