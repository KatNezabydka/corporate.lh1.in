<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MenusRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * Здесь и проверим, а есть ли у пользователя право изменять пункты меню
     */
    public function authorize()
    {
        return \Auth::user()->canDo('ADD_MENU');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * Здесь описываем правила валидации
     */
    public function rules()
    {
        return [

                'title' => 'required|max:255',
        ];
    }
}
