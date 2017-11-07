<?php
namespace App\Repositories;

use App\User;
use Gate;
use Illuminate\Support\Facades\Lang;

class UsersRepository extends Repository
{

    public function __construct(User $user)
    {
//model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $user;
    }

    public function addUser($request){
        if (Gate::denies('CREATE_USERS')) {
            abort(403);
        }

        //Провести валидацию!!!
        $data = $request->all();
//        //можно так
//        $user = User::create([
//                'name' => $data['name'],
//                'login' => $data['login'],
//                'email' => $data['email'],
//                'password' => bcrypt($data['password']),
//
//            ]);

        $user =$this->model->create([
            'name' => $data['name'],
            'login' => $data['login'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        //привязываем к пользователю роль
        if($user) {
            $user->roles()->attach($data['role_id']);
        }

        return ['status' => Lang::get('ru.add_user')];

    }

    public function updateUser($request, $user){
        //создали класс политики безопасности php artisan make:policy ArticlesPolicy
        if (Gate::denies('CHANGE_USERS')) {
            abort(403);
        }

        $data = $request->all();
        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        //записываем в табличку пользователи
        $user->fill($data)->update();

        //получаем доступ к связанной модели roles()
        //синхронизируем роли
        $user->roles()->sync([$data['role_id']]);

        return ['status' => Lang::get('ru.changed_user')];
    }

    public function deleteUser($user) {

        if (Gate::denies('DELETE_USERS')) {
            abort(403);
        }

        //отвязываем его от конкретной роли
        $user->roles()->detach();

        //delete() - удаляет
        if($user->delete()) {
            return ['status' => Lang::get('ru.delete_user')];
        }

    }
}

