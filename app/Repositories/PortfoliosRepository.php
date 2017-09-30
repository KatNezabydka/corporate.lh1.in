<?php

namespace Corp\Repositories;

use Corp\Portfolio;

class PortfoliosRepository extends Repository {

    public function __construct(Portfolio $portfolio)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $portfolio;
    }
}