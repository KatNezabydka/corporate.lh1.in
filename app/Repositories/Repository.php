<?php
namespace Corp\Repositories;

use Config;

// Каждая сущность бд будет работать со своим репозиторием
abstract class Repository {

    protected $model = FALSE;

    public function get() {
        //select - указывает какие поля нужно выбрать их бд
        $builder = $this->model->select('*');

        return $builder->get();
    }

}