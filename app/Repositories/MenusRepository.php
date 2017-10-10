<?php
namespace App\Repositories;

use App\Menu;

class MenusRepository extends Repository {

    public function __construct(Menu $menu)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $menu;
    }

}