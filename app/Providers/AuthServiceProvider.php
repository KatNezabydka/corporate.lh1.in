<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Article;
use App\Permission;
use App\Menu;
use App\Policies\PermissionPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\MenusPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     * Регистрируем политику безопасности, которую создали в Policies
     */
    protected $policies = [
        Article::class => ArticlePolicy::class,
        Permission::class => PermissionPolicy::class,
        Menu::class => MenusPolicy::class,

    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        //регистрируем условие для проверки прав и привилегий пользователя
        //$user - объект модели User
        $gate->define('VIEW_ADMIN', function ($user) {
            //canDo() - вернет true, если у пользователя есть право
            //если передали массив и поставили TRUE - значит должны быть все права из массива
            //если поставили FALSE в массиве - то должно быть хотя бы одно поле
            return $user->canDo('VIEW_ADMIN', FALSE);

        });

        $gate->define('VIEW_ADMIN_ARTICLES', function ($user) {
            //canDo() - вернет true, если у пользователя есть право
            //если передали массив и поставили TRUE - значит должны быть все права из массива
            //если поставили FALSE в массиве - то должно быть хотя бы одно поле
            return $user->canDo('VIEW_ADMIN_ARTICLES', FALSE);
            //
        });

        $gate->define('EDIT_USERS', function ($user) {
            return $user->canDo('EDIT_USERS', FALSE);
        });

        $gate->define('VIEW_ADMIN_MENU', function ($user) {
            return $user->canDo('VIEW_ADMIN_MENU', FALSE);
        });

        $gate->define('VIEW_ADMIN_USERS', function ($user) {
            return $user->canDo('VIEW_ADMIN_USERS', FALSE);
        });


        $gate->define('CREATE_USERS', function ($user) {
            return $user->canDo('CREATE_USERS', FALSE);
        });

        $gate->define('CHANGE_USERS', function ($user) {
            return $user->canDo('CHANGE_USERS', FALSE);
        });

        $gate->define('DELETE_USERS', function ($user) {
            return $user->canDo('DELETE_USERS', FALSE);
        });

    }

}
