<?php
namespace Corp\Repositories;

use Config;

/**
 * Каждая сущность бд будет работать со своим репозиторием
 * */

abstract class Repository {

    protected $model = FALSE;

    /**
     * Возвращает коллеекцию выбранных моделей
     * @param string $select
     * @param bool $take
     * @return mixed
     */
    public function get($select = '*', $take = FALSE, $pagination = FALSE) {
        //select - указывает какие поля нужно выбрать их бд
        $builder = $this->model->select($select);

        if($take) {
            //take() - выдирает заданое количество записей из бд
            $builder->take($take);
        }
        //добавили пагинацию
        if($pagination) {
            return $this->check($builder->paginate(Config::get('settings.paginate')));
        }

        return $this->check($builder->get());
    }

    /**
     * Отобращаем img в нормальном виде...не в формате json
     * Если изначально он не был в формате json - то не трогаем
     * @param $result
     * @return bool
     */
    protected function check($result) {

        if($result->isEmpty()) {
            return FALSE;
        }
        $result->transform(function($item,$key) {
            //если строка, если декодирование возвращает строку, и если декодирование не дало ошибок
            if(is_string($item->img) && is_object(json_decode($item->img)) && (json_last_error() == JSON_ERROR_NONE)) {

                //декодируем строку формата json в объект
                $item->img = json_decode($item->img);
            }


            return $item;
        });

        return $result;

    }

}