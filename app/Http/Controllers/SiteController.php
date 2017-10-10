<?php

namespace App\Http\Controllers;

use App\Repositories\MenusRepository;
use Illuminate\Http\Request;


use App\Http\Requests;

use Menu;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;
use Illuminate\Cookie\CookieJar;


/**
 * Главный контроллер пользовательской части сайта
 * */
class SiteController extends Controller
{
    //Родитель для всех нашиъ контроллеров

    //для хранения объекта класса PortfolioRepository (логика для работы с портфолио)
    protected $p_rep;
    // логика по работе со слайдером
    protected $s_rep;
    // логика по работе со статьями блога
    protected $a_rep;
    // логика по работе со статьями меню
    protected $m_rep;
    // логика по работе со комментариями
    protected $c_rep;

    // меняем заголовки и мета-данные
    protected $keywords;
    protected $meta_desc;
    protected $title;

    // храним имя шаблона для отображения инфы на конкретной странице
    protected $template;
    //массив, переменные передаваемые в шаблон
    protected $vars = array();
    //информация отображающая в левом и правом сайт-баре
    protected $contentRightBar = FALSE;
    protected $contentLeftBar = FALSE;

    protected $bar = 'no';

    public function __construct(MenusRepository $m_rep)
    {
        $this->m_rep = $m_rep;


//        $cook = new CookieJar();
//        return response('Hello World')->withCookie(
//            'name', 'value', time() + 3600);

    }




    /**
     * Отображаем конкретный вид на экране
     * @return $this
     */
    protected function renderOutput()
    {

        //формирует меню
        $menu = $this->getMenu();

        //$navigation - меню
        //render() - макет преобразует в строку
        $navigation = view(env('THEME') . '.navigation')->with('menu', $menu)->render();
        //добавили в параметры навигацию - add - добавляет в массив ячейка (1)в какой массив, 2) ключ создаваемой ячейки, 3) значение)
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        // правый бар
        if ($this->contentRightBar) {
            $rightBar = view(env('THEME') . '.rightBar')->with('content_rightBar', $this->contentRightBar)->render();
            // добавляем в шаблон для отображения
            $this->vars = array_add($this->vars, 'rightBar', $rightBar);
        }
        //здесь передаем значение bar - будет ли оно вообще отображаться/слева/справа
        $this->vars = array_add($this->vars, 'bar', $this->bar);

        // меняем заголовки и мета-данные

        $this->vars = array_add($this->vars, 'keywords', $this->keywords);
        $this->vars = array_add($this->vars, 'meta_desc', $this->meta_desc);
        $this->vars = array_add($this->vars, 'title', $this->title);

        //футер
        $footer = view(env('THEME') . '.footer')->render();
        $this->vars = array_add($this->vars, 'footer', $footer);
        //имя шаблона и список параметров, передаваемых в вид
        return view($this->template)->with($this->vars);
    }

    //Все, что касается меню
    protected function getMenu()
    {
        //lavary/laravel-menu - используем расширение
        //получили колекцию моделей
        $menu = $this->m_rep->get();

        //Получаем из этой колекции конкретное меню
        //Вернет объект для формирования будущего меню
        //use ($menu) - чтобы переменная была доступна в функции
        $mBuilder = Menu::make('MyNav', function ($m) use ($menu) {

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


        return $mBuilder;

    }

}
