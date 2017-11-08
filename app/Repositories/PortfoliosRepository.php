<?php

namespace App\Repositories;

use App\Portfolio;
use Illuminate\Support\Facades\Gate;
use Image;
use Config;

class PortfoliosRepository extends Repository
{

    public function __construct(Portfolio $portfolios)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $portfolios;
    }

    //переопределяем метод one()

    public function one($alias, $attr = array())
    {
        //вызвали родительский метод, который сформировал модель Portfolio
        $portfolio = parent::one($alias, $attr);

        if ($portfolio && !empty($attr)) {
            //подгрузили таблицу фильтров
            $portfolio->load('filter');


        }

        return $portfolio;

    }

    /**
     * @param $request
     * @return array
     */
    public function аddPortfolio($request)
    {

        //еще раз проверяем есть ли доступ...указываем $this->model потому что данное условие будет проверенно в класе политики безопасности
        // и модель нужноа, чтобы laravel определил какая именно политика будет использоваться
        if (Gate::denies('save', $this->model)) {
            abort(403);
        }

        //массив с инофрмацией, которая отправляется с запросом, exept - все поля кроме
        //'image' - нам не интересно, т.к. установим стороннее расширение для работы с изображениями
        $data = $request->except('_token', 'image');

        //еще одна проверка...
        if (empty($data)) {
            return array('error' => 'Нет данных');
        }
        //Если пользователь не ввел alias нам нужно самим его добавить, и оно должно быть уникальным...и с него делаются роуты
        //transliterate - переводит в латинские буквы из заголовка в данном случае
        if (empty($data['alias'])) {
            $data['alias'] = $this->transliterate($data['title']);
        }
        // выбинает одну запись из бд
        //если результат true - значит такой псевдоним уже существует
        if ($this->one($data['alias'], FALSE)) {
            //если возвращаем пользователя обратно, нужно показать акой именно псевдоним уже используется..
            //но он же его не вводил..так что в объекте $request его нет
            //merge() = он добавит/оббединит массив, который внутри с информацией $request
            $request->merge(array('alias' => $data['alias']));
            //flash() - сохраняет в сессию все данные, которые в $request, чтобы все данные были в форме и пользователь заново не вводил их
            $request->flash();
            //в сессию в ячейку error запишет ошибку
            return ['error' => 'Данный псевдоним уже используется'];
        }

        //Проверка отправил ли пользователь изображение
        if ($request->hasFile('image')) {
            //file() - возвращает только что загруженный файл
            $image = $request->file('image');

            //проверка коррекно ли на сервер загружено изображение
            //isValid() = true если без ошибок все
            if ($image->isValid()) {
                // имя для будущих изображений генерируем рандомно
                $str = str_random(8);

                //stdClass - пустой класс
                $obj = new \stdClass;

                $obj->mini = $str . '_mini.jpg';
                $obj->max = $str . '_max.jpg';
                $obj->path = $str . '.jpg';
                //make - это конструктор, у фасада Image
                //в нем хранится объект, который установлен в расширении Intervention Image
                $img = Image::make($image);
                //уменьшает изображение и масштабирует его (ресайзит) указываем width,height
                //public_path() - возвращает путь к public
                //save - сохраняет данное изображение по теущему пути и имя файла
                $img->fit(Config::get('settings.image')['width'],
                    Config::get('settings.image')['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path_portfolio') . $obj->path);

                $img->fit(Config::get('settings.articles_image')['max']['width'],
                    Config::get('settings.articles_image')['max']['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path_portfolio') . $obj->max);

                $img->fit(Config::get('settings.articles_image')['mini']['width'],
                    Config::get('settings.articles_image')['mini']['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path_portfolio') . $obj->mini);

                //декодируем обьект в строку формата json
                $data['img'] = json_encode($obj);
                //заполняем модель пустую модель Portfolio ячейкаи массива $data

                if ($this->model->fill($data)->save()) {
                    return ['status' => 'Портфолио добавлен'];
                }

            }

        }
    }

    public function updatePortfolio($request, $portfolio)
    {
//    dd($portfolio);
        //еще раз проверяем есть ли доступ...указываем $this->model потому что данное условие будет проверенно в класе политики безопасности
        // и модель нужноа, чтобы laravel определил какая именно политика будет использоваться
        if (Gate::denies('edit', $this->model)) {
            abort(403);
        }
        //массив с инофрмацией, которая отправляется с запросом, exept - все поля кроме
        //'image' - нам не интересно, т.к. установим стороннее расширение для работы с изображениями
        $data = $request->except('_token', 'image', '_method');

        //еще одна проверка...
        if (empty($data)) {
            return array('error' => 'Нет данных');
        }
        //Если пользователь не ввел alias нам нужно самим его добавить, и оно должно быть уникальным...и с него делаются роуты
        //transliterate - переводит в латинские буквы из заголовка в данном случае
        if (empty($data['alias'])) {
            $data['alias'] = $this->transliterate($data['title']);
        }
        // выбинает одну запись из бд
        $result = $this->one($data['alias'], FALSE);
        //$result->id - идентификатор данного материала
        //$article->id - идентификатор модели Article (она формируется путем передачи параметра для данного маршрута )
        //если условие = true значит найдена запись отличная от нашей с таим же псевдонимом
        if (isset($result->id) && ($result->id != $portfolio->id)) {
            //если возвращаем пользователя обратно, нужно показать акой именно псевдоним уже используется..
            //но он же его не вводил..так что в объекте $request его нет
            //merge() = он добавит/оббединит массив, который внутри с информацией $request
            $request->merge(array('alias' => $data['alias']));
            //flash() - созраняет в сессию ве данные, которые в $request, чтобы все данные были в форме и пользователь заново не вводил их
            $request->flash();
            //в сессию в ячейку error запишет ошибку
            return ['error' => 'Данный псевдоним уже используется'];
        }

        //Проверка отправил ли пользователь изображение
        if ($request->hasFile('image')) {
            //file() - возвращает только что загруженный файл
            $image = $request->file('image');

            //проверка коррекно ли на сервер загружено изображение
            //isValid() = true если без ошибок все
            if ($image->isValid()) {
                // имя для будущих изображений генерируем рандомно
                $str = str_random(8);

                //stdClass - пустой класс
                $obj = new \stdClass;

                $obj->mini = $str . '_mini.jpg';
                $obj->max = $str . '_max.jpg';
                $obj->path = $str . '.jpg';
                //make - это конструктор, у фасада Image
                //в нем хранится объект, который установлен в расширении Intervention Image
                $img = Image::make($image);
                //уменьшает изображение и масштабирует его (ресайзит) указываем width,height
                //public_path() - возвращает путь к public
                //save - сохраняет данное изображение по теущему пути и имя файла
                $img->fit(Config::get('settings.image')['width'],
                    Config::get('settings.image')['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path_portfolio') . $obj->path);

                $img->fit(Config::get('settings.articles_image')['max']['width'],
                    Config::get('settings.articles_image')['max']['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path_portfolio') . $obj->max);

                $img->fit(Config::get('settings.articles_image')['mini']['width'],
                    Config::get('settings.articles_image')['mini']['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path_portfolio') . $obj->mini);

                //декодируем обьект в строку формата json
                $data['img'] = json_encode($obj);

            }

        }
        //обновляем модель Article ячейкаи массива $data
        $portfolio->fill($data);

        if ($portfolio->update()) {
            return ['status' => 'Портфолио обновлен'];
        }

    }

    /**
     * Удалять материал только тот пользователь, у которого есть права на удаление и только если это ЕГО статья
     * @param $request
     * @param $article
     * @return array
     */
    public function deletePortfolio($portfolio)
    {
        //еще раз проверяем есть ли доступ...указываем модель $article
        //ArticlePolicy
        if (Gate::denies('delete', $portfolio)) {
            abort(403);
        }

        //если удаление прошло успешно
        if ($portfolio->delete()) {
            return ['status' => 'Портфолио удален'];
        }
    }


}