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
use Illuminate\Support\Facades\Lang;
use App\Category;

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
     *
     * Создание нового пункта меню
     */
    public function create()
    {
        $this->title = Lang::get('ru.menu_title');

        //Нам нужно все привести к массиву, чтобы могли использовать SELECT в HTML

        //Выборка пунктов меню

        //roots() - вызов родительских пунктов меню
        $tmp = $this->getMenus()->roots();
        //reduce() - позволяет описать функцию, которая будет вызвана для каждого элемента коллекции
        //и уменьшает коллекцию на 1 элемент при каждом вызове
        //и результат, который возвращается передается как аргумент в следующую итерацию
        //для первого вызова $returnMenus = 0;
        //reduce() - 1й аргумент функция, 2й - аргумент для 1го вызова функции
        //$returnMenus = массивчик наш, $menu - для какого пункта меню вызвалась функция
        $menus = $tmp->reduce(function ($returnMenus, $menu){
            $returnMenus[$menu->id] = $menu->title;
                return $returnMenus;
        },['0' => 'Родительский пункт меню']);

        //Выборка категорий

        $categories = Category::select(['title', 'alias', 'parent_id','id'])->get();

        $list = array();
        //array_add - добавляет элемент в массив
        //1)какой массив; 2)ключ; 3)значение
        $list  = array_add($list  , '0', 'Не используется');
        $list  = array_add($list  , 'parent', 'Раздел блог');

        foreach($categories as $category){
            //значит работаем с родителем
            if($category->parent_id == 0) {
                $list[$category->title] = array();
            }
            else {
                //значит это дочерний элемент
                //$category->parent_id)->first()->title - условие поиска id
                //ищем parent_id у текущей модели категории, берем first элемент потому что он один и выбираем у него title
                $list[$categories->where('id',$category->parent_id)->first()->title][$category->alias] = $category->title;
            }
        }

        //Выборка материалов
        $articles = $this->a_rep->get(['id','title','alias']);

        $articles = $articles->reduce(function ($returnArticles, $article){
            $returnArticles[$article->alias] = $article->title;
            return $returnArticles;
        },['0' => 'Не используется']);

        //Выборка портфолио
        $filters = \App\Filter::select('id','title','alias')->get()->reduce(function ($returnFilters, $filter){
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        },['0' => 'Не используется','parent' => 'Раздел портфолио']);

        $portfolios = $this->p_rep->get(['id','alias','title'])->reduce(function ($returnPortfolios, $portfolio){
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        },['0' => 'Не используется']);


        $this->content = view(env('THEME').'.admin.menus_create_content')->with(['menus'=>$menus, 'categories'=>$list, 'articles'=>$articles, 'filters' =>$filters, 'portfolios'=>$portfolios])->render();

        return $this->renderOut();
        
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
