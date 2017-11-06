<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ArticlesRepository;
use App\Repositories\MenusRepository;
use App\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Gate;
use Menu;

class MenusController extends AdminController
{
    //для работы с элементами будущего меню
    protected $m_rep;


    public function __construct(MenusRepository $m_rep, ArticlesRepository $a_rep, PortfoliosRepository $p_rep)
    {
        parent::__construct();

        //Проверяем есть ли у пользователя права
        if (Gate::denies('VIEW_ADMIN_MENU')) {
            abort(403);
        }

        $this->m_rep = $m_rep;
        $this->a_rep = $a_rep;
        $this->p_rep = $p_rep;

        $this->template = env('THEME') . '.admin.menus';
    }

    /**
     * Display a listing of the resource.
     * Формирует начальную страницу как только переходим в Админ\Меню
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //вернет меню для панели администратора для раздела Меню
        $menu = $this->getMenus();

        $this->content = view(env('THEME').'.admin.menus_content')->with('menus',$menu)->render();

        return $this->renderOut();


    }

    public function getMenus()
    {
        $menu = $this->m_rep->get();

        if ($menu->isEmpty()) {
            return FALSE;
        }

        //Получаем из этой колекции конкретное меню
        //Вернет объект для формирования будущего меню
        //use ($menu) - чтобы переменная была доступна в функции
        return Menu::make('forMenuPart', function ($m) use ($menu) {
            foreach ($menu as $item) {
                //если 0 - значит это родительский пункт меню
                if ($item->parent == 0) {
                    $m->add($item->title, $item->path)->id($item->id);
                } else {
                    //find() - для поиска по интересующему пункту меню его id
                    //если нашли id значит это родитель..и к нему можно добавить пункт меню
                    if ($m->find($item->parent)) {
                        //добавляем к родителю пункт меню по id
                        $m->find($item->parent)->add($item->title, $item->path)->id($item->id);

                    }
                }


            }

        });


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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
