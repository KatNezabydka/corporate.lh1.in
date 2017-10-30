<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        //если в $e содержится класс, который относится к группе isHttpException - мы пропишем свой путь к файлу 404
        if($this->isHttpException($e)){
           $statusCode = $e->getStatusCode(); //вернет код конкретного исключения
            //если ошибка 404 тогда перенаправим на нужную страницу
            switch ($statusCode) {
                case '404':
                    //$obj создали просто обьект класса SiteController для того, чтобы обратиться к его методу getMenu (для того, чтобы сделать меню на страничке 404)
                    $obj = new \App\Http\Controllers\SiteController(new \App\Repositories\MenusRepository(new \App\Menu));

                    //это готовая панель навигации
                    $navigation = view(env('THEME') . '.navigation')->with('menu', $obj->getMenu())->render();
                    //сформинуем ответ response - вызовим вид view
                    //в качестве второго аргумента передаем массив параметров, которые будут переданы в макет

                    //Залогировать что страница не найдена и какая не найдена покажем
                    \Log::alert('Страница не найдена - '.$request->url());
                    return response()->view(env('THEME').'.404',['bar'=> 'no', 'title'=> 'Страница не найдена', 'navigation'=> $navigation]);
            }
        }

        return parent::render($request, $e);
    }
}
