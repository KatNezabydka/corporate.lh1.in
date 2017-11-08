<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Portfolio extends Model
{
    // поля, которые разрешенны к массовому заполнению
    protected $fillable = [
        'title','img','alias','text','customer','keywords','meta_desc','filter_alias'
    ];

    //Связь с таблицей filter - один ко многим...
    //один фильтр может быть связан со многими портфолио

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filter() {
        //портфолио сылается на запись из таблички фильтр
        //1) Модель таблицы, с которой идет связь
        //2) поле внешнего ключа в таблице portfolio (наша таблица)
        //3) поле внешнего ключа в таблице filter (которая связывается с нашей таблицей)
        return $this->belongsTo('App\Filter', 'filter_alias', 	'alias');
    }

}
