<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
//Работаем с фасадом Auth, Menu
use Illuminate\Support\Facades\Auth;
use Menu;
use Gate;

class AdminController extends \App\Http\Controllers\Controller
{
    //
    //работа с портфолио репозиторием
    protected $p_rep;
    //работа с articles репозиторием
    protected $a_rep;
    //идентифицированный пользователь
    protected $user;
    // логика по работе со слайдером
    protected $s_rep;
    //для сохранения шаблона
    protected $template;
    // основная часть в html для панели администратора
    protected $content = FALSE;
    //заголовок конкретной страницы
    protected $title;
    //массив переменных, который будет передаваться в шаблон
    protected $vars;


    public function __construct()
    {
        //ссохраняем обьект аутентифицированного пользователя, если пользователь не аутентиф., получим 0
        $this->user = Auth::user();
        //если у пользователя нет доступа в админку не пускаем его
        if (!$this->user) {
            abort(403);
        }
    }

    public function renderOut(){
        //добавим в массив первую ячейку с title
        $this->vars= array_add($this->vars, 'title',$this->title);

        //формируем меню
        $menu = $this->getMenu();

        //код главного меню панели администратора - heml код
        //с with передаем переменные
        //render() - преобразует объект в готовый html код
        $navigation = view(config('settings.theme').'.admin.navigation')->with('menu',$menu)->render();
        $this->vars= array_add($this->vars, 'navigation',$navigation);

        //если основной контент есть - добавляем его к переменным vars
        if($this->content) {
            $this->vars= array_add($this->vars, 'content',$this->content);
        }
        //формируем футер
        $footer = view(config('settings.theme').'.admin.footer')->render();
        $this->vars= array_add($this->vars, 'footer',$footer);

        return view($this->template)->with($this->vars);
    }

    public function getMenu(){

         return Menu::make('adminMenu', function($menu) {
            //добавляем пункты меню - Имя и Путь тот, у которого тип ресурс

             //Проверяем есть ли у пользователя право на выполнение определенного действия, если нет - ссылку не показываем
             //allows() - рпазрешено ли выполнять
            if(Gate::allows('VIEW_ADMIN_ARTICLES')){
                $menu->add('Статьи',array('route' => 'admin.articles.index'));
            }
             if(Gate::allows('VIEW_ADMIN_PORTFOLIOS')){
                 $menu->add('Портфолио',array('route' => 'admin.portfolios.index'));
             }
             if(Gate::allows('VIEW_ADMIN_MENU')){
                 $menu->add('Меню',array('route' => 'admin.menus.index'));
             }
             if(Gate::allows('VIEW_ADMIN_USERS')){
                 $menu->add('Пользователи',array('route' => 'admin.users.index'));
             }

             if(Gate::allows('VIEW_ADMIN_PERMISSIONS')){
                 $menu->add('Привилегии',array('route' => 'admin.permissions.index'));
             }

             if(Gate::allows('EDIT_SLIDERS')){
                 $menu->add('Сдайдер',array('route' => 'admin.sliders.index'));
             }

        });
    }
}
