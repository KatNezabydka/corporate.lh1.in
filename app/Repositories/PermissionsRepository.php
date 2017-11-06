<?php
namespace App\Repositories;

use App\Permission;
use Gate;

class PermissionsRepository extends Repository {

    //чтобы могли выбрать роли, доступные в нашем проекте
    protected $rol_rep;

    public function __construct(Permission $permission,RolesRepository $rol_rep)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $permission;
        $this->rol_rep = $rol_rep;
    }


    public function changePermissions($request){

        if (Gate::denies('change',  $this->model)) {
            abort(403);
        }
        //здесь изменения, которые нужно внести
        $data = $request->except('_token');

        //Привилении привязаны к ролям, значит выбираем доступные роли
        //чтобы могли обратиться к репозиторию, включили его в контруктор
        $roles = $this->rol_rep->get();

        foreach ($roles as $value) {
            //роли - это ключи в массиве
            if(isset($data[$value->id])){
                //savePermissions - описываются в модели Pole
                //$data[$value->id] - массив приав, который нужно прикрепить к данной роли
                $value->savePermissions($data[$value->id]);
            }
            else {
                //иначе у данной роли нет привилегий, передаем пустой массив
                $value->savePermissions([]);
            }
        }

        return ['status' => 'Права обновлены'];

    }

}