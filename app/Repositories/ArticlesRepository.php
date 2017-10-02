<?php
namespace Corp\Repositories;

use Corp\Article;

class ArticlesRepository extends Repository {

    public function __construct(Article $articles)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $articles;
    }

}