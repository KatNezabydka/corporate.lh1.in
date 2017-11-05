<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    // Связь с моделью users многие ко многим
    public function users()
    {
        //c какой моделью связь, и через какую таблицу
        return $this->belongsToMany('App\User', 'role_user');
    }
    // Связь с моделью permission многие ко многим
    public function perms()
    {
        //c какой моделью связь, и через какую таблицу
        return $this->belongsToMany('App\Permission', 'permission_role');
    }

    /**
     * @param $name - конкретная привилегия
     * @param bool $require
     * @return bool
     * будет возвращать истину если конкретная роль содержит конкретную привилегию
     */
    public function hasPermission($name, $require = FALSE)
    {
        //может прийти или одно свойство на проверку или массив
        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = $this->hasPermission($permissionName);

                if ($hasPermission && !$require) {
                    return TRUE;
                } else if (!$hasPermission && $require) {
                    return FALSE;
                }

            }
            //сюда код дойдет, только если require будет истина и на каждой итерации цикла получим тоже истину
            return $require;
        } else {
            //можно было написать $this->roles()->get()
            //можно обратиться к динамическому свойству roles, которое вернет коллекцию ролей пользователей
            foreach ($this->perms()->get() as $permission) {
                if ($permission->name == $name) {
                    return true;
                }

            }
        }

    }

    public function savePermissions($inputPermissions) {

        if(!empty($inputPermissions)) {
            //$this - это модель Role
            //$this->perms() - предоставляет доступ к связанной модели Permission
            //sync() - синхронизация связанных моделей через связующую таблицу
            // в соответствии со списком идентификаторов который мы и передаем
            // в качестве 1го аргумента функции savePermissions, а
            //идентификатор Роли берется из свойства id модели Role
            //или обновляется или дописывается или стирается
            $this->perms()->sync($inputPermissions);
        }
        //когда передается пустой массив, например когда для какой-то роли не заданы вообще права
        else {
            //datach() - удаляет привязки все) то есть если до этого были какие то права
            //для конкретной роли - все убирается
            $this->perms()->detach();
        }
        return true;
    }




}
