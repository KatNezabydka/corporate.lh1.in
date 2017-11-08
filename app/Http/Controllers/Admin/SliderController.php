<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\SlidersRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use \App\Http\Controllers\IndexController;
use Gate;
use Config;
use App\Slider;
use Illuminate\Support\Facades\Lang;

class SliderController extends AdminController
{

    public function __construct(SlidersRepository $s_rep)
    {
        parent::__construct();

        //Проверяем есть ли у пользователя права на редактирование привилегий
        if (Gate::denies('EDIT_SLIDERS')) {
            abort(403);
        }

        $this->s_rep = $s_rep;
        //каталог и имя шаблона
        $this->template = env('THEME').'.admin.sliders';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->title = Lang::get('ru.sliders_admin');

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
        //Формируем внешний вид
        $this->content = view(env('THEME').'.admin.sliders_content')->with('sliders',$sliders)->render();

        return $this->renderOut();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->title = Lang::get('ru.add_new_sliders');

        $this->content = view(env('THEME') . '.admin.sliders_create_content')->render();
        return $this->renderOut();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->s_rep->аddSlider($request);

        //Если при сохранении в ячейке error что-то будет - нужно вернуть пользователя назад
        if (is_array($result) && !empty($result['error'])) {
            //with - возвращает в сессию информацию
            return back()->with($result);
        }
        //иначе делаем редирект на главную страницу админа с сообщением что ве успешно
        return redirect('/admin')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $slider = Slider::select('title', 'desc', 'id','img')->where('id', $id)->get()->first();
        $this->title = 'Редактирование слайдера - '. $slider->title;

        $this->content = view(env('THEME') . '.admin.sliders_create_content')->with( 'slider',$slider)->render();
        return $this->renderOut();

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = $this->s_rep->updateSlider($request,$id);
        //Если при сохранении в ячейке error что-то будет - нужно вернуть пользователя назад
        if (is_array($result) && !empty($result['error'])) {
            //with - возвращает в сессию информацию
            return back()->with($result);
        }
        //иначе делаем редирект на главную страницу админа с сообщением что ве успешно
        return redirect('/admin')->with($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->s_rep->deleteSlider($id);
        //Если при сохранении в ячейке error что-то будет - нужно вернуть пользователя назад
        if (is_array($result) && !empty($result['error'])) {
            //with - возвращает в сессию информацию
            return back()->with($result);
        }
        //иначе делаем редирект на главную страницу админа с сообщением что ве успешно
        return redirect('/admin')->with($result);
    }
}
