<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Repositories\SlidersRepository;
use App\Repositories\PortfoliosRepository;
use App\Repositories\ArticlesRepository;
use Config;
use Illuminate\Support\Facades\Cookie;


/**
 * Контроллер для обработки главной страницы
 */
class IndexController extends SiteController
{
    //в параметрах внедряем зависсимость
    public function __construct(SlidersRepository $s_rep,PortfoliosRepository  $p_rep, ArticlesRepository $a_rep)
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));

        $this->a_rep = $a_rep;
        $this->s_rep = $s_rep;
        $this->p_rep = $p_rep;
        //показываем что на главной странице, что там есть правый бар
        $this->bar = 'right';

        $this->middleware('web');
        //указываем имя страницы
        $this->template = env('THEME') . '.index';
    }

    /**
     * Отображение главной страницы пользовательской части
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //на главной странице есть еще информация о работах компании - портфолио
        //только их много, отображать нужно только 4
        $portfolios = $this->getPortfolio();

        $content = view(env('THEME') . '.content')->with('portfolios', $portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);


        $sliderItems = $this->getSliders();

        //Добавляем слайдер - он только на главной странице
        //render() - переводит вид в строку
        //with - это параметры, которые передаются
        $sliders = view(env('THEME') . '.slider')->with('sliders', $sliderItems)->render();
        $this->vars = array_add($this->vars, 'sliders', $sliders);

        //переопределяем переменные

        $this->keywords = 'Home Page';
        $this->meta_desc = 'Home Page';
        $this->title = 'Home Page';

        //отображение правого бара

        $articles = $this->getArticles();

        $this->contentRightBar = view(env('THEME') . '.indexBar')->with('articles', $articles)->render();


        return $this->renderOutput();

    }



    /**
     * @return sliders
     */
    public function getSliders()
    {
        //get() - выберет все записи из таблички sliders
        $sliders = $this->s_rep->get();

        if ($sliders->isEmpty()) {
            return FALSE;
        }
        //позволяет описать функцию, которая будет вызвана для каждого элемента коллекции
        //$item - модель,  key - id
        $sliders->transform(function ($item, $key) {

            $item->img = Config::get('settings.slider_path') . '/' . $item->img;
            return $item;

        });

        return $sliders;

    }

    protected function getArticles() {

        $articles = $this->a_rep->get(['title','created_at','img','alias'], Config::get('settings.home_atricles_count'));

        return $articles;
    }

    protected function getPortfolio() {

        $portfolio = $this->p_rep->get('*', Config::get('settings.home_port_count'));

        return $portfolio;
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
