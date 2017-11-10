<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Repositories\RolesRepository;
use App\Repositories\UsersRepository;
use Gate;
use Illuminate\Support\Facades\Lang;
use App\Http\Requests\UserRequest;

class UsersController extends AdminController
{
    protected $us_rep;
    protected $rol_rep;


    public function __construct(RolesRepository $rol_rep, UsersRepository $us_rep)
    {
        parent::__construct();


        if (Gate::denies('VIEW_ADMIN_USERS')) {
            abort(403);
        }

        $this->us_rep = $us_rep;
        $this->rol_rep = $rol_rep;
        //каталог и имя шаблона
        $this->template = config('settings.theme') . '.admin.users';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $users = $this->us_rep->get();

        $this->content = view(config('settings.theme') . '.admin.users_content')->with(['users' => $users])->render();

        return $this->renderOut();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->title = Lang::get('ru.new_users');

        $roles = $this->getRoles()->reduce(function ($returnRoles, $role) {
            $returnRoles[$role->id] = $role->name;
            return $returnRoles;
        }, []);

        $this->content = view(config('settings.theme') . '.admin.users_create_content')->with('roles', $roles)->render();
        return $this->renderOut();

    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        //обращаемся к репозиторию, делаем выборку их бд
        $roles = $this->rol_rep->get();
        return $roles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $result = $this->us_rep->addUser($request);
        //Если при сохранении в ячейке error что-то будет - нужно вернуть пользователя назад
        if (is_array($result) && !empty($result['error'])) {
            //with - возвращает в сессию информацию
            return back()->with($result);
        }
        //иначе делаем редирект на главную страницу админа с сообщением что ве успешно
        return redirect('/admin')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * User $user - обьект модели пользователя, которого нужно отредактировать
     */
    public function edit(User $user)
    {

        $this->title = Lang::get('ru.change_user') . ' - ' . $user->name;

        $roles = $this->getRoles()->reduce(function ($returnRoles, $role) {
            $returnRoles[$role->id] = $role->name;
            return $returnRoles;
        }, []);

        $this->content = view(config('settings.theme') . '.admin.users_create_content')->with(['roles' => $roles, 'user' => $user])->render();
        return $this->renderOut();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $result = $this->us_rep->updateUser($request, $user);
        //Если при сохранении в ячейке error что-то будет - нужно вернуть пользователя назад
        if (is_array($result) && !empty($result['error'])) {
            //with - возвращает в сессию информацию
            return back()->with($result);
        }
        //иначе делаем редирект на главную страницу админа с сообщением что ве успешно
        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $result = $this->us_rep->deleteUser($user);
        //Если при сохранении в ячейке error что-то будет - нужно вернуть пользователя назад
        if (is_array($result) && !empty($result['error'])) {
            //with - возвращает в сессию информацию
            return back()->with($result);
        }
        //иначе делаем редирект на главную страницу админа с сообщением что ве успешно
        return redirect('/admin')->with($result);
    }
}
