<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    //User и Article - связь один ко многим - один юзер может добавить много записей hasMany()
    //выборка статей, добавленных конкретным пользователем
    /**
     * User и Article - связь многие к одному - один юзер может добавить много записей
     * выборка статей, добавленных конкретным пользователем
     */
    public function articles() {
        return $this->hasMany('App\Article');
    }

    /**
     * Users и Comment - многие к одному - один юзер может написать много комментов

     * получаем комменты, которые привязанны к конкретному пользователю
     */

    public function comment() {
        return $this->hasMany('App\Comment');
    }
}
