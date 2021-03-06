<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//
//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::auth_not_use();
//
//Route::get('/home', 'HomeController@index');
//
//Route::auth_not_use();
////
//Route::get('/home', 'HomeController@index');
//

Route::resource('/','IndexController',[
                                    'only' => ['index'],
                                    'names' => [
                                                'index' => 'home'
                                                ],

                                    ]);

Route::resource('portfolios','PortfolioController',[

                                                    'parameters' =>[

                                                                    'portfolios' => 'alias'
                                                                ]

                                                    ]);

Route::resource('articles','ArticlesController',[

                                                'parameters' =>[

                                                        'articles' => 'alias'
                                                ]
                                                ]);
//w - любое слово (проверяем строку статьи на валидность)
//articlesCat - для отображения статей, привязанных к определенной категории
Route::get('articles/cat/{cat_alias?}',['uses' => 'ArticlesController@index', 'as' => 'articlesCat'])->where('cat_alias', '[\w-]+');


Route::resource('comment','CommentController',['only' =>['store']]);

Route::match(['get','post'],'/contacts',['uses'=>'ContactsController@index', 'as'=>'contacts']);


//Route::auth();
Route::get('login','Auth\AuthController@showLoginForm');
Route::post('login','Auth\AuthController@login');
Route::get('logout','Auth\AuthController@logout');

//admin
//'middleware' => 'auth' - для того, чтобы доступ в данный раздел могли получить только зарегестрированные пользователи
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    //admin - главная страница
    Route::get('/',['uses' => 'Admin\IndexController@index','as'=>'adminIndex']);
    //параметры данного маршрута формируются как articles
    //admin - Статьи
    Route::resource('/articles','Admin\ArticlesController');
    //admin - Портфолио
    Route::resource('/portfolios','Admin\PortfoliosController');
    //admin - Меню
    //передаваемые параметры menus
    Route::resource('/menus','Admin\MenusController');
    //admin - Пользователи
    Route::resource('/users','Admin\UsersController');
    //admin - Привилегии
    Route::resource('/permissions','Admin\PermissionsController');
    //admin - Слайдер
    Route::resource('/sliders','Admin\SliderController');




});