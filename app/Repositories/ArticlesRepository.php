<?php
namespace App\Repositories;

use App\Article;

class ArticlesRepository extends Repository {

    public function __construct(Article $articles)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $articles;
    }

    //переопределяем метод one()

    public function one($alias, $attr = array()) {
        //вызвали родительский метод, который сформировал модель Article
       $article = parent::one($alias, $attr);

       if ($article && !empty($attr)) {
           //подгрузили таблицу комментариев
           $article->load('comments');
           //далее подгрузили информацию о пользователе, которые оставляют комменты
           $article->comments->load('user');
       }

       return $article;

    }

}