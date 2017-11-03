<?php
namespace App\Repositories;

use App\Category;

class CategoryRepository extends Repository {

    public function __construct(Category $category)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $category;
    }

}