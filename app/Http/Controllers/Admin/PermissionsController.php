<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\PermissionsRepository;
use App\Repositories\RolesRepository;
use Gate;
use Illuminate\Support\Facades\Lang;


class PermissionsController extends AdminController
{

    protected $per_rep;
    protected $rol_rep;


    //Данный контроллер работает как с правами так и с ролями
    //значит нужны репозитории для ролей и привилегий
    public function __construct(PermissionsRepository $per_rep, RolesRepository $rol_rep)
    {
        parent::__construct();

        //Проверяем есть ли у пользователя права на редактирование привилегий
        if (Gate::denies('VIEW_ADMIN_PERMISSIONS')) {
            abort(403);
        }

        $this->per_rep = $per_rep;
        $this->rol_rep = $rol_rep;
        //каталог и имя шаблона
        $this->template = config('settings.theme').'.admin.permissions';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * Здесь делаем выборку по ролям и по правам
     */
    public function index()
    {
        $this->title = Lang::get('ru.permissions_title');
        // формируем коллекцию ролей и привилегий
        $roles = $this->getRoles();
        $permissions = $this->getPermissions();

        //Формируем внешний вид
        $this->content = view(config('settings.theme').'.admin.permissions_content')->with(['roles'=>$roles, 'priv'=>$permissions])->render();

        return $this->renderOut();


    }

    /**
     * Вернет коллекцию доступных ролей
     * @return mixed
     */
    public function getRoles()
    {
        //обращаемся к репозиторию, делаем выборку их бд
        $roles = $this->rol_rep->get();
        return $roles;
    }

    /**
     * Вернет коллекцию доступных прав
     * @return mixed
     */
    public function getPermissions()
    {
        //обращаемся к репозиторию, делаем выборку их бд
        $permissions = $this->per_rep->get();
        return $permissions;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * Код такой же как в Admin\ArticleController
     */
    public function store(Request $request)
    {
       $result = $this->per_rep->changePermissions($request);

       if(is_array($result) && !empty($result['error'])) {
           return back()->with($result);
       }

        return back()->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
