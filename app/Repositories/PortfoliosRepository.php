<?php

namespace App\Repositories;

use App\Portfolio;

class PortfoliosRepository extends Repository {

    public function __construct(Portfolio $portfolio)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $portfolio;
    }

    // чтобы не проделывать манипуляцию переконвертирования imj в контроллере
    public function one($alias, $attr = array()) {
        // родительский метод one вернет конкретную модель из бд
        $portfolio =  parent::one($alias,$attr);
        //А далее преобразуем пункт imj из json в объект
        if($portfolio && $portfolio->img) {
            $portfolio->img = json_decode($portfolio->img);
        }

        return $portfolio;
    }

}