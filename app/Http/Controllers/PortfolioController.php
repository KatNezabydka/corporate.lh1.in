<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\PortfoliosRepository;

class PortfolioController extends SiteController
{
    public function __construct(PortfoliosRepository $p_rep)
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));

        $this->p_rep = $p_rep;

        //указываем имя страницы
        $this->template = config('settings.theme') . '.portfolios';
    }

    public function index()
    {

        // Можно добавить описание в мета всего блока
        $this->title = 'Портфолио';
        $this->keywords = 'Портфолио';
        $this->meta_desc= 'Портфолио';

        //Получаем записи из таблички портфолио
        //"ne информацию мы используем для формирования контента
        $portfolios = $this->getPortfolios();

        $content = view(env("THEME") . '.portfolios_content')->with('portfolios',$portfolios)->render();
        $this->vars = array_add($this->vars, 'content', $content);


        return $this->renderOutput();

    }

    public function getPortfolios($take = FALSE, $paginate = TRUE)
    {
        //работаем с репозиторием p_rep
        $portfolios = $this->p_rep->get('*', $take,$paginate);

        //если есть связанная модель - а у нас это фильтр - нужно подгрузить ее
        if($portfolios ) {
            $portfolios->load('filter');
        }

        return $portfolios;

    }


    //Показывает конкретную страницу статьи
    //$alias - указание, какую страницу выбирать из бд
    public function show($alias)
    {

        $portfolio = $this->p_rep->one($alias);
        $portfolio->img = json_decode($portfolio->img);

              // Добавляем описание в мета конкретной статьи
        $this->title = $portfolio->title;
        $this->keywords = $portfolio->keywords;
        $this->meta_desc= $portfolio->meta_desc;

        $portfolios = $this->getPortfolios(config('settings.other_portfolios'), FALSE);

        $content = view(config('settings.theme') . '.portfolio_content')->with(['portfolio' => $portfolio, 'portfolios' => $portfolios])->render();
        $this->vars = array_add( $this->vars,'content',$content );


        return $this->renderOutput();

    }

}
