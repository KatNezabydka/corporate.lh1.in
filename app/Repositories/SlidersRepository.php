<?php

namespace App\Repositories;

use App\Slider;
use Config;
use Image;

class SlidersRepository extends Repository
{

    public function __construct(Slider $slider)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $slider;
    }


    /**
     * @param $request
     * @return array
     */
    public function аddSlider($request)
    {

        //массив с инофрмацией, которая отправляется с запросом, exept - все поля кроме
        //'image' - нам не интересно, т.к. установим стороннее расширение для работы с изображениями
        $data = $request->except('_token', 'image');
        //еще одна проверка...
        if (empty($data)) {
            return array('error' => 'Нет данных');
        }

        //Проверка отправил ли пользователь изображение
        if ($request->hasFile('image')) {
            //file() - возвращает только что загруженный файл
            $image = $request->file('image');

            $path = Config::get('settings.slider_path');
            $size_path = Config::get('settings.slider_image');

            //проверка коррекно ли на сервер загружено изображение
            if ($image->isValid()) {

                $str = str_random(8) . '.jpg';

                $img = Image::make($image);
                //уменьшает изображение и масштабирует его (ресайзит) указываем width,height
                if ($img->fit($size_path['width'], $size_path['height'])->save(public_path() . '/' . env('THEME') . $path . '/' . $str)) {
                    $data['img'] = $str;
                }
            }
            if ($this->model->fill($data)->save()) {
                return ['status' => 'Слайдер добавлен'];
            }
        }
    }


    public function updateSlider($request, $id)
    {

        $data = $request->only('title', 'desc');
        //Получаем текущую модель, которуб будем обновлять
        $slider = Slider::select('*')->where('id', $id)->get()->first();
        //еще одна проверка...
        if (empty($data)) {
            return array('error' => 'Нет данных');
        }

        //Проверка отправил ли пользователь изображение
        if ($request->hasFile('image')) {
            //file() - возвращает только что загруженный файл
            $image = $request->file('image');

            $path = Config::get('settings.slider_path');
            $size_path = Config::get('settings.slider_image');

            //проверка коррекно ли на сервер загружено изображение
            if ($image->isValid()) {

                $str = str_random(8) . '.jpg';

                $img = Image::make($image);
                //уменьшает изображение и масштабирует его (ресайзит) указываем width,height
                if ($img->fit($size_path['width'], $size_path['height'])->save(public_path() . '/' . env('THEME') . $path . '/' . $str)) {
                    $data['img'] = $str;
                }
            }

            //удаляем старое изображение
            $path = public_path() . '/' . env('THEME') . Config::get('settings.slider_path') . $slider->img;
            \File::Delete($path);

        }

        if ($slider->fill($data)->update()) {
            return ['status' => 'Слайдер обновлен'];
        }

    }

    /**
     * Удалять материал только тот пользователь, у которого есть права на удаление и только если это ЕГО статья
     * @param $request
     * @param $article
     * @return array
     */
    public function deleteSlider($id)
    {
        //удаляем изображение
        $slider = Slider::select('img')->where('id', $id)->get()->first();
        $path = public_path() . '/' . env('THEME') . Config::get('settings.slider_path') . $slider->img;
        \File::Delete($path);

        //если удаление прошло успешно
        if (Slider::destroy($id)) {
            return ['status' => 'Слайдер удален'];
        }
    }

}