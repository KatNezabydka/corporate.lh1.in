<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\PortfoliosRepository;
use App\Repositories\ArticlesRepository;
use App\Repositories\CommentsRepository;

use App\Http\Requests;
use App\Category;


class ArticlesController extends SiteController
{
    //
    public function __construct(PortfoliosRepository $p_rep, ArticlesRepository $a_rep, CommentsRepository $c_rep)
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));

        $this->a_rep = $a_rep;
        $this->p_rep = $p_rep;
        $this->c_rep = $c_rep;
        //показываем что на главной странице, что там есть правый бар
        $this->bar = 'right';
        //указываем имя страницы
        $this->template = env('THEME') . '.articles';
    }

    //отображение списка статей, в центральной области
    public function index($cat_alias = FALSE)
    {

        // Можно добавить описание в мета всего блока
        $this->title = 'Блок';
        $this->keywords = 'String';
        $this->meta_desc= 'String';

        //список статей
        $articles = $this->getArticles($cat_alias);

        $content = view(env("THEME") . '.articles_content')->with('articles', $articles)->render();
        $this->vars = array_add($this->vars, 'content', $content);

        $comments = $this->getComments(config('settings.recent_comment'));
        $portfolios = $this->getPortfolios(config('settings.recent_portfolios'));


        $this->contentRightBar = view(env('THEME') . '.articlesBar')->with(['comments' => $comments, 'portfolios' => $portfolios])->render();

        return $this->renderOutput();

    }

    //Показывает конкретную страницу статьи
    public function show($alias = FALSE)
    {
        //one() - делает выборку одной записи из бд
        $article = $this->a_rep->one($alias,['comments' => TRUE]);


       // dd($article->comments->groupBy('parent_id'));
        if ($article) {
            $article->img = json_decode($article->img);
        }

        //условие если статья существует, чтобы не было ошибки, когда мы материал удалили и потом пытаемся зайти на него
        if(isset($article)){
            // Добавляем описание в мета конкретной статьи
            $this->title = $article->title;
            $this->keywords = $article->keywords;
            $this->meta_desc= $article->meta_desc;
        }
        else {
            //ВЫВЕСТИ ЧТО НЕТ ДАННЫХ
        }

        $content = view(env('THEME') . '.article_content')->with('article', $article)->render();
        $this->vars = array_add( $this->vars,'content',$content );

        $comments = $this->getComments(config('settings.recent_comment'));
        $portfolios = $this->getPortfolios(config('settings.recent_portfolios'));

        $this->contentRightBar = view(env('THEME') . '.articlesBar')->with(['comments' => $comments, 'portfolios' => $portfolios])->render();


        return $this->renderOutput();

    }


    public function getComments($take)
    {
        // $take - количество выбираемых файлов из репозитория
        $comments = $this->c_rep->get(['text', 'name', 'email', 'site', 'article_id', 'user_id'], $take);

        if ($comments) {
            //load() - позволяет подгрузить информацию из связанных моделей на этапе формирования информации
            $comments->load('user', 'article');
        }

        return $comments;

    }

    public function getPortfolios($take)
    {
        $portfolios = $this->p_rep->get(['title', 'text', 'alias', 'customer', 'img', 'filter_alias'], $take);
        return $portfolios;

    }

    public function getArticles($alias = FALSE)
    {

        $where = FALSE;

        if ($alias) {
            // WHERE `alias` = $alias;
            //first() - возвращает первую запись, так как alias - уникальное поле
            $id = Category::select('id')->where('alias', $alias)->first()->id;
            //WHERE category_id = $id
            $where = ['category_id', $id];
        }

        $articles = $this->a_rep->get(['id', 'title', 'alias', 'created_at', 'img', 'desc', 'user_id', 'category_id','keywords','meta_desc'], FALSE, TRUE, $where);

        if ($articles) {
            //оптимизация запросов к бд
            //load() - позволяет подгрузить информацию из связанных моделей на этапе формирования информации
            $articles->load('user', 'category', 'comments');
        }

        return $articles;

    }


}
