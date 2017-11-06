<?php

namespace App\Repositories;

use App\Menu;
use Gate;
use Illuminate\Support\Facades\Lang;

class MenusRepository extends Repository
{

    public function __construct(Menu $menu)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $menu;
    }


    public function аddMenu($request)
    {

        //еще раз проверяем есть ли доступ...указываем $this->model потому что данное
        // условие будет проверенно в класе политики безопасности MenuPolicy

        if (Gate::denies('save', $this->model)) {
            abort(403);
        }
        //only() - выбираем только то, что указываем
        //type - тип меню (по радиокнопки) пользователь хочет создать
        //title - заголовок меню
        //parent - родительский пункт меню (чтобы понять будет это родитель или дочерний элемент)
        $data = $request->only('type', 'title', 'parent');

        //Проверка если пользователь ничего не ввел - вернули что данных нет
        if (empty($data)) {
            return ['error' => Lang::get('ru.no_data')];
        }
//
//        dd($request->all());

        switch ($data['type']) {
            case 'customLink':
                // Если пользователь ввел поьзовательскую ссылку, то в качестве пути используем данные с инпута!!!
                $data['path'] = strip_tags($request->input('custom_link'));
                break;

            case 'blogLink':
                //
                if ($request->input('category_alias')) {
                    //если это родитель, значит это маршрут где отображаются все article - это раздел Блог
                    if ($request->input('category_alias') == 'parent') {
                        $data['path'] = route('articles.index');

                    } else {
                        //Переход на конкретную категорию дочернюю
                        $data['path'] = route('articlesCat', ['cat_alias' => $request->input('category_alias')]);
                    }
                } //Переход на конкретную статью
                else if ($request->input('article_alias')) {
                    $data['path'] = route('articles.show', ['alias' => $request->input('article_alias')]);
                }

                break;

            case 'portfolioLink':
                if ($request->input('filter_alias')) {
                    if ($request->input('filter_alias') == 'parent') {
                        $data['path'] = route('portfolios.index');

                    }
                } else if ($request->input('portfolio_alias')) {
                    //Переход на конкретную категорию дочернюю
                    $data['path'] = route('portfolios.show', ['alias' => $request->input('portfolio_alias')]);
                }
                break;

            default: dd('fdsfsdfsd');
        }

        //За пределами switch удаляем то, что нам не интересно
        unset($data['type']);

        //у нас есть пользовательская ссылка и title - уже можно созранить пункт меню
        //fill() - заполняем текущую модель данными
        //save() - созраняем
        if ($this->model->fill($data)->save()) {
            return ['status' => Lang::get('ru.add_link')];
        }
    }

    public function updateMenu($request, $menu)
    {

        //еще раз проверяем есть ли доступ...указываем $this->model потому что данное
        // условие будет проверенно в класе политики безопасности MenuPolicy

        if (Gate::denies('update', $this->model)) {
            abort(403);
        }
        //only() - выбираем только то, что указываем
        //type - тип меню (по радиокнопки) пользователь хочет создать
        //title - заголовок меню
        //parent - родительский пункт меню (чтобы понять будет это родитель или дочерний элемент)
        $data = $request->only('type', 'title', 'parent');

        //Проверка если пользователь ничего не ввел - вернули что данных нет
        if (empty($data)) {
            return ['error' => Lang::get('ru.no_data')];
        }

//        dd($request->all());

        switch ($data['type']) {
            case 'customLink':
                // Если пользователь ввел поьзовательскую ссылку, то в качестве пути используем данные с инпута!!!
                $data['path'] = strip_tags($request->input('custom_link'));
                break;

            case 'blogLink':
                //
                if ($request->input('category_alias')) {
                    //если это родитель, значит это маршрут где отображаются все article - это раздел Блог
                    if ($request->input('category_alias') == 'parent') {
                        $data['path'] = route('articles.index');

                    } else {
                        //Переход на конкретную категорию дочернюю
                        $data['path'] = route('articlesCat', ['cat_alias' => $request->input('category_alias')]);
                    }
                } //Переход на конкретную статью
                else if ($request->input('article_alias')) {
                    $data['path'] = route('articles.show', ['alias' => $request->input('article_alias')]);
                }

                break;

            case 'portfolioLink':
                if ($request->input('filter_alias')) {
                    if ($request->input('filter_alias') == 'parent') {
                        $data['path'] = route('portfolios.index');

                    }
                } else if ($request->input('portfolio_alias')) {
                    //Переход на конкретную категорию дочернюю
                    $data['path'] = route('portfolios.show', ['alias' => $request->input('portfolio_alias')]);
                }
                break;

            default: back();
        }

        //За пределами switch удаляем то, что нам не интересно
        unset($data['type']);

        //у нас есть пользовательская ссылка и title - уже можно созранить пункт меню
        //fill() - заполняем текущую модель данными
        //update - обновляем

        //$menu - люращаемся к меню той ссылки, которая редактируется
        if ($menu->fill($data)->update()) {
            return ['status' => Lang::get('ru.update_link')];
        }
    }

    public function deleteMenu($menu) {

        if (Gate::denies('delete', $this->model)) {
            abort(403);
        }

        //delete() - удаляет
        if($menu->delete()) {
            return ['status' => Lang::get('ru.delete_link')];
        }

    }


}