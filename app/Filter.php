<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Filter extends Model
{
    protected $fillable = [
        'title', 'alias','id'
    ];

    //НЕЯСНО
    //Связь с таблицей portfolio - многие к одному...
    //один фильтр может быть связан со многими портфолио

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
//    public function portfolio() {
//        return $this->belongsTo('App\Portfolio');
//    }


}
