<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Validator;

class ContactsController extends SiteController
{
    public function __construct()
    {
        parent::__construct(new \App\Repositories\MenusRepository(new \App\Menu));


        //показываем что на главной странице, что там есть правый бар
        $this->bar = 'left';
        //указываем имя страницы
        $this->template = env('THEME') . '.contacts';
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
            ]/*, $messages*/);
//            dd($request); - нет валидации
//            //все данные массива сохраняем в переменную data
            $data = $request->all();
//            dd($data);
            //отправка сообщений
            //use ($data) - пишем, если хотим использовать значение этой переменной - данные с отправляемой формы
            $result = Mail::send(env('THEME') . '.email', ['data' => $data], function ($m) use ($data) {

                $mail_admin = env('MAIL_ADMIN');

                $m->from($data['email'], $data['name']);
                $m->to($mail_admin, 'Mr. Admin')->subject('Question');
            });

            if ($result) {
//                return redirect()->back();
                //with('status', 'Email is send') - записываем в сессию в ячейку status значение Email is send
                return redirect()->route('contacts')->with('status', 'Email is send');
            }
        }

        $this->title = 'Контакты';
        $content = view(env('THEME') . '.contact_content')->render();
        $this->vars = array_add($this->vars, 'content', $content);

        $this->contentLeftBar = view(env('THEME') . '.contact_bar')->render();

        return $this->renderOutput();
    }
}




