<?php

namespace App\Http\Controllers\Admin;

use App\Portfolio;
use App\Filter;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Repositories\PortfoliosRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use App\Http\Requests\PortfolioRequest;

class PortfoliosController extends AdminController
{

    protected $p_rep;
    /**
     * ArticlesController constructor.
     *  Подключаем ArticlesRepository - который работает с табличкой
     */

    public function __construct(PortfoliosRepository $p_rep)
    {
        parent::__construct();

        if (Gate::denies('VIEW_ADMIN_PORTFOLIOS')) {
            abort(403);
        }

        //переопределяем значение свойства
        $this->p_rep = $p_rep;

        //переопределяем свойство template - имя шаблона, которое используется для главной страницы
        $this->template = config('settings.theme') . '.admin.portfolios';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Отобразить список материалов, которые уже добавлены
        $this->title = Lang::get('ru.manager_portfolios');

        //выбор портфолио из бд коллекции моделей
        $portfolios = $this->getPortfolios();
        //формируем контент - with - передать переменные
        $this->content = view(config('settings.theme') . '.admin.portfolios_content')->with('portfolios', $portfolios)->render();


        return $this->renderOut();
    }


    public function getPortfolios()
    {
        //работаем с репозиторием p_rep
        $portfolios = $this->p_rep->get();

//        //если есть связанная модель - а у нас это фильтр - нужно подгрузить ее
//        if($portfolios ) {
//            $portfolios->load('filter');
//        }

        return $portfolios;

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $this->title = Lang::get('ru.add_new_portfolio');

        //получаем фильтры из таблицы - РЕАЛИЗОВАТЬ!!!
        $filters = Filter::select('title', 'alias', 'id')->get();
        // формируем выпадающий списток с группами  документации collective расширение для html и forms
        //выпадающий список select с групами

        $lists = array();

        foreach ($filters as $filter) {
            //значит это родительская категория
            $lists[$filter->alias] = $filter->title;
        }

        $this->content = view(config('settings.theme') . '.admin.portfolios_create_content')->with(['filters'=>$lists])->render();
        return $this->renderOut();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArticleRequest $request
     * @return \Illuminate\Http\Response
     * Получаем данные из метода post по созданию данной страницы, делаем валидацию
     * дальше передаем управление конкретному репозиторию (сохранение материала делает ArticlesRepository)
     * В аргументах не Request, а ArticleRequest (здесь проходит валидация!!!)
     */
    public function store(PortfolioRequest $request)
    {

        //addArticle - созранит информацию о новом материале (будет возвращать array)
        $result = $this->p_rep->аddPortfolio($request);

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
     */
    //Внедрение зависимости, можно было в коде написать $article = Article::where('alias', $alias); где $alias - аргумент функции
    //но мы напишем в аргументы $article - и это будет модель редактируемого материала

    public function edit(Portfolio $portfolio)
    {

        //dd($article) - вернет модель, но пустую, потому что ожидает он ее по идентификатору, а мы выводим по alias
        //проверяем есть ли у пользователя права на выполнение данного материала
        if(Gate::denies('edit', new Portfolio)) {
            abort(403);
        }

        $this->title = 'Редактирование портфолио - '. $portfolio->title;

        //переводим изображение в объект, чтобы могли работать с ним
        $portfolio->img = json_decode($portfolio->img);

        //получаем фильтры из таблицы - РЕАЛИЗОВАТЬ!!!
        $filters = Filter::select('title', 'alias', 'id')->get();
        // формируем выпадающий списток с группами  документации collective расширение для html и forms
        //выпадающий список select с групами

        $lists = array();

        foreach ($filters as $filter) {
            //значит это родительская категория
            $lists[$filter->alias] = $filter->title;
        }

        $this->content = view(config('settings.theme') . '.admin.portfolios_create_content')->with(['portfolio'=>$portfolio, 'filters'=>$lists])->render();
        return $this->renderOut();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    //Внедряем зависимость для метода update (объект модели Article)
    //При этом в сервис провайдере RouteServiceProvider мы связали параметр articles с моделью Article

    public function update(PortfolioRequest $request, Portfolio $portfolio)
    {
        //Article $article - сформированная модель
        //ArticleRequest $request - новые данные, которые нужно заменить
        //ArticleRequest но он валидирует alias, чтобы его ввели уникальным, а здесь мы хотим только отредактировать..так что немного переделаем валидацию
        //addArticle - созранит информацию о новом материале (будет возвращать array)
        $result = $this->p_rep->updatePortfolio($request,$portfolio);
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
     * у нас идет поиск статьи не по id, а по alias - поэтому в аргументах модель
     */
    public function destroy(Portfolio $portfolio)
    {
        //
        $result = $this->p_rep->deletePortfolio($portfolio);
        //Если при сохранении в ячейке error что-то будет - нужно вернуть пользователя назад
        if (is_array($result) && !empty($result['error'])) {
            //with - возвращает в сессию информацию
            return back()->with($result);
        }
        //иначе делаем редирект на главную страницу админа с сообщением что ве успешно
        return redirect('/admin')->with($result);
    }
}
