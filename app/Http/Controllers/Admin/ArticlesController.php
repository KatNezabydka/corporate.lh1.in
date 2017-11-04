<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ArticlesRepository;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use App\Category;
use App\Article;

class ArticlesController extends AdminController
{
    /**
     * ArticlesController constructor.
     *  Подключаем ArticlesRepository - который работает с табличкой
     */
    public function __construct(ArticlesRepository $a_rep, CategoryRepository $cat_rep)
    {
        parent::__construct();

        //проверка если у авторизированного пользователя права на просмотр этого раздела
        //для этого используем сервис провайдер (Открываем Provider\AuthServiceProvider), вызываем фасад Gate
        //Если запрещен доступ к админке, выкидываем его
        if (Gate::denies('VIEW_ADMIN_ARTICLES')) {
            abort(403);
        }

        //переопределяем значение свойства
        $this->a_rep = $a_rep;
        $this->cat_rep = $cat_rep;

        //переопределяем свойство template - имя шаблона, которое используется для главной страницы
        $this->template = env('THEME') . '.admin.articles';

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Отобразить список материалов, которые уже добавлены
        $this->title = Lang::get('ru.manager_articles');

        //выбор статей из бд коллекции моделей
        $articles = $this->getArticles();
        //формируем контент - with - передать переменные
        $this->content = view(env('THEME') . '.admin.articles_content')->with('articles', $articles)->render();


        return $this->renderOut();
    }


    public function getArticles()
    {
        //Возвращаем вызов метода get() репозитория articlesRepository
        return $this->a_rep->get();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Проверяем есть ли у пользователя права на добавление article save - проверяемое действие, условие будем формировать в класе политике безопасности \App\Article
        //создали класс политики безопасности php artisan make:policy ArticlesPolicy
        if (Gate::denies('save', new Article)) {
            abort(403);

        }
        $this->title = "Добавить новый материал";

        //получаем категории из таблицы
        $categories = Category::select('title', 'alias', 'parent_id', 'id')->get();
        // формируем выпадающий списток с группами  документации collective расширение для html и forms
        //выпадающий список select с групами
        $lists = array();

        foreach ($categories as $category) {
            //значит это родительская категория
            if ($category->parent_id == 0) {
                $lists[$category->title] = array();
            }

            //если это модель дочерней категории
            //where - в категориях найдем конкретную модель у которой id находится значение родителя
            //но whereвозвращает коллекцию, но мы знаем что у дочерней модели может быть только один родитель
            /// а значит выбираем first()
            else {
                $lists[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->content = view(env('THEME') . '.admin.articles_create_content')->with('categories', $lists)->render();
        return $this->renderOut();

    }

//    public function getCategory()
//    {
//        //Возвращаем вызов метода get() репозитория articlesRepository
//        $categories = $this->cat_rep->get('title','alias','parent_id','id');
//    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ArticleRequest $request
     * @return \Illuminate\Http\Response
     * Получаем данные из метода post по созданию данной страницы, делаем валидацию
     * дальше передаем управление конкретному репозиторию (сохранение материала делает ArticlesRepository)
     * В аргументах не Request, а ArticleRequest (здесь проходит валидация!!!)
     */
    public function store(ArticleRequest $request)
    {
        //addArticle - созранит информацию о новом материале (будет возвращать array)
        $result = $this->a_rep->addArticle($request);
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

    public function edit(Article $article)
    {
        //dd($article) - вернет модель, но пустую, потому что ожидает он ее по идентификатору, а мы выводим по alias
        //проверяем есть ли у пользователя права на выполнение данного материала
        if(Gate::denies('edit', new Article)) {
            abort(403);
        }

        //переводим изображение в объект, чтобы могли работать с ним
        $article->img = json_decode($article->img);

        //получаем категории из таблицы
        $categories = Category::select('title', 'alias', 'parent_id', 'id')->get();
        // формируем выпадающий списток с группами  документации collective расширение для html и forms
        //выпадающий список select с групами
        $lists = array();

        foreach ($categories as $category) {
            //значит это родительская категория
            if ($category->parent_id == 0) {
                $lists[$category->title] = array();
            }

            //если это модель дочерней категории
            //where - в категориях найдем конкретную модель у которой id находится значение родителя
            //но whereвозвращает коллекцию, но мы знаем что у дочерней модели может быть только один родитель
            /// а значит выбираем first()
            else {
                $lists[$categories->where('id', $category->parent_id)->first()->title][$category->id] = $category->title;
            }
        }
        $this->title = 'Редактирование материала - '. $article->title;

        $this->content = view(env('THEME') . '.admin.articles_create_content')->with(['article'=>$article ,'categories'=> $lists])->render();
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

    public function update(ArticleRequest $request, Article $article)
    {
        //Article $article - сформированная модель
        //ArticleRequest $request - новые данные, которые нужно заменить
        //ArticleRequest но он валидирует alias, чтобы его ввели уникальным, а здесь мы хотим только отредактировать..так что немного переделаем валидацию
        //addArticle - созранит информацию о новом материале (будет возвращать array)
        $result = $this->a_rep->updateArticle($request,$article);
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
    public function destroy($id)
    {
        //
    }
}
