<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Lang;

class ContactsController extends SiteController
{
    public function __construct()
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));


        //показываем что на главной странице, что там есть правый бар
        $this->bar = 'left';
        //указываем имя страницы
        $this->template = config('settings.theme') . '.contacts';
    }


    public function index(Request $request)
    {

        if ($request->isMethod('post')) {
//        dd($request->all());// ВСЕ ОТПРАВЛЯЕТСЯ
            //метка :attribute - показывает имена полей
            $messages = [
                'required' => "Поле :attribute обязательно к заполнению",
                'email' => "Поле :attribute должно соответствовать email адресу ",
            ];

            //валидация полей  required - обязательно к заполнению, max - максимальное количество символов
            // email - правильный имэйл
            //для каждого поля сформируем пользовательские сообщения
            //если есть ошибка делаемм редирект на главную страницу
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'text' => 'required',
            ], $messages);
//            //все данные массива сохраняем в переменную data
            $data = $request->all();
            //отправка сообщений
            //use ($data) - пишем, если хотим использовать значение этой переменной - данные с отправляемой формы
            $result = Mail::send(config('settings.theme') . '.email', ['data' => $data], function ($m) use ($data) {

                $mail_admin = config('settings.mail_admin');

                $m->from($data['email'], $data['name']);
                $m->to($mail_admin, 'Mr. Admin')->subject('Question');
            });

            if ($result) {
                //with('status', 'Email is send') - записываем в сессию в ячейку status значение Email is send
                $res = redirect()->route('contacts')->with('status', Lang::get('ru.email_is_send'));
                return $res;
            }
        }

        $this->title = 'Контакты';
        $content = view(config('settings.theme') . '.contact_content')->render();
        $this->vars = array_add($this->vars, 'content', $content);

        $this->contentLeftBar = view(config('settings.theme') . '.contact_bar')->render();

        return $this->renderOutput();
    }
}




