<?php
namespace App\Repositories;

use App\Slider;

class SlidersRepository extends Repository {

    public function __construct(Slider $slider)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $slider;
    }

}