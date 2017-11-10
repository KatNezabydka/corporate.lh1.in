<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Article;
use App\Portfolio;
use App\Permission;
use App\Menu;
use App\User;

use App\Policies\PermissionPolicy;
use App\Policies\ArticlePolicy;
use App\Policies\MenuPolicy;
use App\Policies\UserPolicy;
use App\Policies\PortfolioPolicy;

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
        Menu::class => MenuPolicy::class,
        User::class => UserPolicy::class,
        Portfolio::class => PortfolioPolicy::class,

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

        $gate->define('VIEW_ADMIN_PERMISSIONS', function ($user) {
            return $user->canDo('VIEW_ADMIN_PERMISSIONS', FALSE);
        });

        $gate->define('VIEW_ADMIN_MENU', function ($user) {
            return $user->canDo('VIEW_ADMIN_MENU', FALSE);
        });

        $gate->define('VIEW_ADMIN_USERS', function ($user) {
            return $user->canDo('VIEW_ADMIN_USERS', FALSE);
        });

        $gate->define('VIEW_ADMIN_PORTFOLIOS', function ($user) {
            return $user->canDo('VIEW_ADMIN_PORTFOLIOS', FALSE);
        });

        $gate->define('CREATE_PORTFOLIOS', function ($user) {
            return $user->canDo('CREATE_PORTFOLIOS', FALSE);
        });

        $gate->define('EDIT_SLIDERS', function ($user) {
            return $user->canDo('EDIT_SLIDERS', FALSE);
        });


    }

}
