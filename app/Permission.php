<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    // Связь с моделью role многие ко многим
    public function roles()
    {
        //c какой моделью связь, и через какую таблицу
        return $this->belongsToMany('App\Role', 'permission_role');
    }
}
