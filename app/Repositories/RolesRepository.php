<?php
namespace App\Repositories;

use App\Role;

class RolesRepository extends Repository {

    public function __construct(Role $role)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $role;
    }

}