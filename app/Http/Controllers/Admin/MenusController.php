<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ArticlesRepository;
use App\Repositories\MenusRepository;
use App\Repositories\PortfoliosRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Gate;
//фасад меню
use Menu;
use Illuminate\Support\Facades\Lang;
use App\Category;
use App\Http\Requests\MenusRequest;


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

        $this->template = config('settings.theme') . '.admin.menus';
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

        $this->content = view(config('settings.theme').'.admin.menus_content')->with('menus',$menu)->render();

        return $this->renderOut();


    }

    /**
     * @return bool
     */
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
        },[]);

        //Выборка портфолио
        $filters = \App\Filter::select('id','title','alias')->get()->reduce(function ($returnFilters, $filter){
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        },[ 'parent' => 'Раздел портфолио']);

        $portfolios = $this->p_rep->get(['id','alias','title'])->reduce(function ($returnPortfolios, $portfolio){
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        },[]);

        $this->content = view(config('settings.theme').'.admin.menus_create_content')->with(['menus'=>$menus, 'categories'=>$list, 'articles'=>$articles, 'filters' =>$filters, 'portfolios'=>$portfolios])->render();

        return $this->renderOut();
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     *
     * Обрабатывает запрос отправки формы добавления пункта меню на сервер
     * Для валидации полей испльзуем класс MenusRequest в аргументах
     */
    public function store(MenusRequest $request)
    {

        //addArticle - созранит информацию о новом материале (будет возвращать array)
        $result = $this->m_rep->аddMenu($request);
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
     * App\Menu - модель меню, пишем полный путь, чтобы не было путаницы
     *
     */
    public function edit(\App\Menu $menu)
    {
        //$menu  - находится модель ссылки, которую нужно отредактировать


        $this->title = Lang::get('ru.menu_edit_title') . $menu->title;

        //содержит в себе тип меню, который мы редактируем
        $type = FALSE;

        //для подсветки соответственной опции из выпадающего списка
        $option = FALSE;

        //НУЖНО ОТЫСКАТЬ В МОИХ РОУТАХ МАРШРУТ, КОТОРЫЙ СООТВЕТСТВУЕТ PATH РЕДАКТИРУЕМОЙ ССЫЛКИ

        // app() – функция хелпер, предоставляет доступ к глобальному сервис-контейнеру laravel
        // app('router') – ячейка, которая хранит в себе объект маршрутизатора
        //getRoutes() - djpdhfoftn колекцию роут - по всем созданным маршрутам
        //match() - из списка маршрутов найдет подходящий, который и обработает
        //create() - сформирует новый обьект запроса из пути, который передается

        $route = app('router')->getRoutes()->match(app('request')->create($menu->path));

        //хранит псевдоним маршрута, который соответствует пути для редактируемой ссылки
        //массив параметров маршрута, который соответствует пути для редактируемой ссылки
        //getName() - возвращает имя данного маршрута (смотри API ROUTE)
        $aliasRoute = $route->getName();
        //getName() - возвращает параметры для данного маршрута (смотри API ROUTE)
        $parameters = $route->parameters();

        if($aliasRoute == 'articles.index' || $aliasRoute == 'articlesCat' ){
            $type = 'blogLink';
            $option = isset($parameters['cat_alias']) ? $parameters['cat_alias'] : 'parent';
        }
        else if($aliasRoute == 'articles.show') {
            $type = 'blogLink';
            $option = isset($parameters['alias']) ? $parameters['alias'] : '';

        }
        else if($aliasRoute == 'portfolios.index') {
            $type = 'portfolioLink';
            $option = 'parent';
        }
        else if($aliasRoute == 'portfolios.show') {
            $type = 'portfolioLink';
            $option = isset($parameters['alias']) ? $parameters['alias'] : '';
        }
        else{
            $type = 'customLink';

        }
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
        },[]);

        //Выборка портфолио
        $filters = \App\Filter::select('id','title','alias')->get()->reduce(function ($returnFilters, $filter){
            $returnFilters[$filter->alias] = $filter->title;
            return $returnFilters;
        },[ 'parent' => 'Раздел портфолио']);

        $portfolios = $this->p_rep->get(['id','alias','title'])->reduce(function ($returnPortfolios, $portfolio){
            $returnPortfolios[$portfolio->alias] = $portfolio->title;
            return $returnPortfolios;
        },[]);


        $this->content = view(config('settings.theme').'.admin.menus_create_content')->with(['menu'=>$menu, 'type'=>$type ,'option' =>$option, 'menus'=>$menus, 'categories'=>$list, 'articles'=>$articles, 'filters' =>$filters, 'portfolios'=>$portfolios])->render();

        return $this->renderOut();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,\App\Menu $menu)
    {

        $result = $this->m_rep->updateMenu($request, $menu);
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
    public function destroy(\App\Menu $menu)
    {

        $result = $this->m_rep->deleteMenu($menu);
        //Если при сохранении в ячейке error что-то будет - нужно вернуть пользователя назад
        if (is_array($result) && !empty($result['error'])) {
            //with - возвращает в сессию информацию
            return back()->with($result);
        }
        //иначе делаем редирект на главную страницу админа с сообщением что ве успешно
        return redirect('/admin')->with($result);

    }
}
