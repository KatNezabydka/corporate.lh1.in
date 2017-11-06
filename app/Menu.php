<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $fillable = [
        'title', 'path','parent'
    ];

    /**
     * @param array $options
     *
     * Переопределяем метод, но корректируем его, чтобы он удалял и вложенные ссылкии
     */
    public function delete(array $options = []) {

        //self - потому что мне не нужно обратиться к конкретной модели, которую я удаляю, а нужно сделать выборку
        //здесь делаем выборку всех моделей, у которых parent - та модель, которую я удаляю
            self::where('parent',$this->id)->delete();
           return parent::delete($options);
    }

}
