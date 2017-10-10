<?php
namespace App\Repositories;

use App\Comment;

class CommentsRepository extends Repository {

    public function __construct(Comment $comment)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $comment;
    }

}