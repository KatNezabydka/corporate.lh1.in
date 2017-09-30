<?php
namespace Corp\Repositories;

use Corp\Slider;

class SlidersRepository extends Repository {

    public function __construct(Slider $slider)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $slider;
    }

}