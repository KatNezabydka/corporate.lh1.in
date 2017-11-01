<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);
        //регистрируем условие для проверки прав и привилегий пользователя
        //$user - объект модели User
        $gate->define('VIEW_ADMIN', function($user) {
            //canDo() - вернет true, если у пользователя есть право
            //если передали массив и поставили TRUE - значит должны быть все права из массива
            //если поставили FALSE в массиве - то должно быть хотя бы одно поле
                return $user->canDo(['VIEW_ADMIN'], FALSE);
        });
        //
    }
}
