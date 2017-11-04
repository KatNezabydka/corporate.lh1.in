<?php

namespace App\Repositories;

use App\Article;
use Illuminate\Support\Facades\Gate;
use Image;
use Config;

class ArticlesRepository extends Repository
{

    public function __construct(Article $articles)
    {
        //model будем записывать ту модель, которая работает с конкретной таблицей бд
        $this->model = $articles;
    }

    //переопределяем метод one()

    public function one($alias, $attr = array())
    {
        //вызвали родительский метод, который сформировал модель Article
        $article = parent::one($alias, $attr);

        if ($article && !empty($attr)) {
            //подгрузили таблицу комментариев
            $article->load('comments');
            //далее подгрузили информацию о пользователе, которые оставляют комменты
            $article->comments->load('user');
        }

        return $article;

    }

    /**
     * @param $request
     * @return array
     */
    public function addArticle($request)
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
                    Config::get('settings.image')['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path') . $obj->path);

                $img->fit(Config::get('settings.articles_image')['max']['width'],
                    Config::get('settings.articles_image')['max']['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path') . $obj->max);

                $img->fit(Config::get('settings.articles_image')['mini']['width'],
                    Config::get('settings.articles_image')['mini']['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path') . $obj->mini);

                //декодируем обьект в строку формата json
                $data['img'] = json_encode($obj);

                //заполняем модель пустую модель Article ячейкаи массива $data
                $this->model->fill($data);
//                dd($request->user());
                //user() - вернет пользователя, который делает изменения статьи
                //articles() - предоставляет доступ к связанным моделям Article
                //save() - сохраняем для определенного пользователя модель, которая уже сформирована
                if ($request->user()->articles()->save($this->model)) {
                    return ['status' => 'Материал добавлен'];
                }

            }

        }
    }

    public function updateArticle($request, $article)
    {
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
        if (isset($result->id) && ($result->id != $article->id)) {
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
                    Config::get('settings.image')['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path') . $obj->path);

                $img->fit(Config::get('settings.articles_image')['max']['width'],
                    Config::get('settings.articles_image')['max']['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path') . $obj->max);

                $img->fit(Config::get('settings.articles_image')['mini']['width'],
                    Config::get('settings.articles_image')['mini']['height'])->save(public_path() . '/' . env('THEME') . Config::get('settings.image_path') . $obj->mini);

                //декодируем обьект в строку формата json
                $data['img'] = json_encode($obj);


            }


        }
        //обновляем модель Article ячейкаи массива $data
        $article->fill($data);

        if ($article->update()) {
            return ['status' => 'Материал обновлен'];
        }

    }

}