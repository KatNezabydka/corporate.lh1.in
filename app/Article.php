<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * Article и User - один ко многим - одна запись привязанна к конкретному пользователю
     * но у пользователя может быть много записей
     * Получим информацию о том, кто добавил запись
     */
    public function user() {
        return $this->belongsTo('App\User');
    }


    /**
     * Article и Category - один ко многим - одна запись привязанна к конкретной категории,
     * но одной сатегории может принадлежать много статей
     * получаем категорию, к которой привязанна конкретная запись
     */

    public function category() {
        return $this->belongsTo('App\Category');
    }

    /**
     * Article и Comment - многие к одному - у одной записи может быть множество комментариев

     * получаем коментарии, привязанные к записи
     */

    public function comments() {
        return $this->hasMany('App\Comment');
    }

}
