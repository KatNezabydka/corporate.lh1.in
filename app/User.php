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
    public function articles()
    {
        return $this->hasMany('App\Article');
    }

    /**
     * Users и Comment - многие к одному - один юзер может написать много комментов
     * получаем комменты, которые привязанны к конкретному пользователю
     */

    public function comment()
    {
        return $this->hasMany('App\Comment');
    }

    // Связь с моделью role многие ко многим
    public function roles()
    {
        //c какой моделью связь, и через какую таблицу
        return $this->belongsToMany('App\Role', 'role_user');
    }

    /**
     * @param $permission (string || array)
     * @param bool $require
     * @return bool
     */
    public function canDo($permission, $require = FALSE)
    {
        //может прийти или одно свойство на проверку или массив
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                //если передали массив, то берем первое проверяемое свойство и вызываем рекурсивно наш метод
                $permName = $this->canDo($permName);
                //если у пользователя есть права
                if ($permName && !$require) {
                    return TRUE;
                } //если у пользователя нет права
                else if (!$permName && $require) {
                    return FALSE;
                }

            }
            //сюда код дойдет, только если require будет истина и на каждой итерации цикла получим тоже истину
            return $require;
        } else {
            //можно было написать $this->roles()->get()
            //можно обратиться к динамическому свойству roles, которое вернет коллекцию ролей пользователей
            foreach ($this->roles as $role) {
                //в омодели Role есть метод perms()
                //можно было обратиться к динамическому свойству $this->parms
                //в parm - записываем модель конкретной привилегии
                foreach ($role->perms as $perm) {
                    //имя привилегии, которая привязанна к данной роли, а роль привязанна к пользователю
                    //str_is порверяет соответствует ли строка(2й аргумент) определенной маски (1й аргумент) -
                    //проверяем 2 строки на равенство
                    if (str_is($permission, $perm->name)) {
                        return true;
                    }

                }
            }

        }
    }


    /**
     * @param $name
     * @param bool $require
     * @return bool
     * будет возвращать истину если пользователь привязан к определенной роли
     */
    public function hasRole($name, $require = FALSE)
    {
        //может прийти или одно свойство на проверку или массив
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$require) {
                    return TRUE;
                } else if (!$hasRole && $require) {
                    return FALSE;
                }

            }
            //сюда код дойдет, только если require будет истина и на каждой итерации цикла получим тоже истину
            return $require;
        } else {
            //можно было написать $this->roles()->get()
            //можно обратиться к динамическому свойству roles, которое вернет коллекцию ролей пользователей
            foreach ($this->roles as $role) {
                if ($role->name == $name) {
                    return true;
                }

            }
        }

    }

}
