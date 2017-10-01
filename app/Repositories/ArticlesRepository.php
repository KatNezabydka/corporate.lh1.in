<?php
namespace Corp\Repositories;

use Corp\Articles;

class ArticlesRepository extends Repository {

    public function __construct(Articles $articles)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $articles;
    }

}