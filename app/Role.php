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
}
