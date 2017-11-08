<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //Прописываем регулярные выражения для проверки маршрутов с псевдонимом alias (в роутере с типом recourse)
        $router->pattern('alias', '[\w-]+');

        parent::boot($router);
        //связываем параметр articles с конкретной моделью по alias не по id
        // bind() привязывает текстовую информацию(d нашем случае параметр routa articles) с конкретной моделью
        // $value - это будет наш параметр alias который передаем в строке, для редактированние данной статьи
        $router->bind('articles', function($value) {
            return \App\Article::where('alias',$value)->first();
        });

        //связываем параметр portfolios с конкретной моделью по alias не по id
        // bind() привязывает текстовую информацию(d нашем случае параметр routa portfolios) с конкретной моделью
        // $value - это будет наш параметр alias который передаем в строке, для редактированние данного портфолио
        $router->bind('portfolios', function($value) {
            return \App\Portfolio::where('alias',$value)->first();
        });

        //привязали к данному параметру  'menus' конкретную модель \App\Menu
        $router->bind('menus', function($value) {
            return \App\Menu::where('id',$value)->first();
        });

        //ищем запись пользователя по идентификатору
        $router->bind('users', function($value) {
            return \App\User::find($value);
        });

    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $this->mapWebRoutes($router);

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
