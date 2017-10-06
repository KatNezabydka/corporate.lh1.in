<?php

namespace Corp;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    /**
     * Category и Article - многие к одному - у одной категории может быть множество записей
     * получаем записи, которые принадлежат конкретной категории
     */
    public function articles() {
        return $this->hasMany('Corp\Article');
    }
}
