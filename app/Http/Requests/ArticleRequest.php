<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\Auth;

class ArticleRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * Если djpdhfoftn True - то доступ к приложению разрешается
     * Здесь и проверим, а есть ли у пользователя право добавлять новую статью
     */
    public function authorize()
    {
        return Auth::user()->canDo('ADD_ARTICLES');
    }

    /**
     * Illuminate\Foundation\Http FormRequest extends Request
     * мы переопределяем метод, который наследуется у Request - а там есть обьект Валидатора
     * Данный метод возвращает объект валидатора
     */
    protected function getValidatorInstance()
    {
        //вызвали родительский метод
        $validator = parent::getValidatorInstance();

        // 3й параметр функция($input - объект с входными данными), если вернет true - то условия валидации будут выполняться
        $validator->sometimes('alias', 'unique:articles|max:255', function ($input) {

            //ИЗМЕНЯЕМ УСЛОВИЯ, ЧТОБЫ МОГЛИ РЕДАКТИРОВАТЬ ЗАПИСЬ ОСТАВЛЯЯ ALIAS ТЕМ ЖЕ

            //получаем доступ к текущему маршруту
            if ($this->route()->hasParameter('articles')) {
                //в $model выборка интересующего материала из бд - того, который мы редактируем
                $model = $this->route()->parameter('articles');
                //$input->alias - тот псевдоним, который пользователь вводит
                //$model->alias - тот псевдоним, который уже есть в модели
                //так вот мы вернем true только если они не равны и ввели псевдоним
                return ($model->alias !== $input->alias) && !empty($input->alias);
            }
            return !empty($input->alias);
        });
        //Возвращаем объект валидатора - который валидует поле ALIAS при добавлении новой статьи
        return $validator;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * Описываем масив данных, для валидации
     * @return array
     */
    public function rules()
    {
        return [
            //
            'title' => 'required|max:255',
            'text' => 'required',
            'category_id' => 'required|integer',

        ];
    }
}
