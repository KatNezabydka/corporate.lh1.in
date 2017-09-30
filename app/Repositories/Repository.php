<?php
namespace Corp\Repositories;

use Config;

/**
 * Каждая сущность бд будет работать со своим репозиторием
 * */

abstract class Repository {

    protected $model = FALSE;

    public function get($select = '*', $take = FALSE) {
        //select - указывает какие поля нужно выбрать их бд
        $builder = $this->model->select($select);

        if($take) {
            //take() - выдирает заданое количество записей из бд
            $builder->take($take);
        }

        return $builder->get();
    }

}