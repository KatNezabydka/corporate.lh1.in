<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // поля, которые разрешенны к массовому заполнению
    protected $fillable = ['name','text','site','user_id','article_id','parent_id','email'];
    /**
     * Comment и Article  - один ко многим - один коммент привязан к конкретной записи

     * получаем запись, к которой привязан данный комментарий
     */

    public function article() {
        return $this->belongsTo('App\Article');
    }

    /**
     * Comment и Users - один ко многим - один коммент привязан к конкретному пользователю

     * получаем юзера, к которому привязан данный комментарий
     */

    public function user() {
        return $this->belongsTo('App\User');
    }
}
