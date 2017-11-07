<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //должно быть прописано в AuthServiceProvider
        return \Auth::user()->canDo('CREATE_USERS');
    }


    protected function getValidatorInstance()
    {
        //вызвали родительский метод
        $validator = parent::getValidatorInstance();

        // 3й параметр функция($input - объект с входными данными), если вернет true - то условия валидации будут выполняться
        $validator->sometimes('password', 'required|min:6|confirmed', function ($input) {
            //если пользователь редактируется, то не нужно заново валидировать поле пароль
            if (!empty($input->password) || ((empty($input->password) && $this->route()->getName() !== 'admin.users.update'))) {
                return TRUE;
            }
            return FALSE;
        });
        //Возвращаем объект валидатора - который валидует поле password при добавлении новой статьи
        return $validator;

    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        //в RouteServiceProvider прописали условие поиска, в parameter('users') лежит именно тот id, к которому привязанна данная страница-роут
        // если редактируем пользователя - передаем идентификатор того пользователя, который должен быть проигнорирован
        //route() - возвращает текущий маршрут
        $id = (isset($this->route()->parameter('users')->id)) ? $this->route()->parameter('users')->id : '';
        return [
            'name' => 'required|max:255',
            'login' => 'required|max:255',
            'role_id' => 'required|integer',
            'email' => 'required|email|max:255|unique:users,email,' . $id
        ];
    }

}
