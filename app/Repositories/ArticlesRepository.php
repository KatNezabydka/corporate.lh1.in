<?php

namespace App\Repositories;

use App\Article;
use Illuminate\Support\Facades\Gate;
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

            $path = Config::get('settings.image_path_article');
            $size_path = Config::get('settings.image');
            $size_min_max = Config::get('settings.articles_image');

            //Добавляем изображение
            //возвращает или json-ячейку img или false
            $result_img_json = parent::addImage($image, $path, $size_path, $size_min_max);

            //сохранили json-ячейку img
            $data['img'] = $result_img_json;

            //заполняем модель пустую модель Article ячейкаи массива $data
            $this->model->fill($data);
            //user() - вернет пользователя, который делает изменения статьи
            //articles() - предоставляет доступ к связанным моделям Article
            //save() - сохраняем для определенного пользователя модель, которая уже сформирована
            if ($request->user()->articles()->save($this->model)) {
                return ['status' => 'Материал добавлен'];
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

            $path = Config::get('settings.image_path_article');
            $size_path = Config::get('settings.image');
            $size_min_max = Config::get('settings.articles_image');

            //Добавляем изображение
            //возвращает или json-ячейку img или false
            $result_img_json = parent::addImage($image, $path, $size_path, $size_min_max);

            //декодируем обьект в строку формата json
            $data['img'] =  $result_img_json;

            //удаляем старое изображение
            parent::deleteImage($article, $path);

        }
        //обновляем модель Article ячейкаи массива $data
        $article->fill($data);

        if ($article->update()) {
            return ['status' => 'Материал обновлен'];
        }

    }

    /**
     * Удалять материал только тот пользователь, у которого есть права на удаление и только если это ЕГО статья
     * @param $request
     * @param $article
     * @return array
     */
    public function deleteArticle($article)
    {

        //еще раз проверяем есть ли доступ...указываем модель $article
        //ArticlePolicy
        if (Gate::denies('destroy', $article)) {
            abort(403);
        }
        //удаляем изображение
        parent::deleteImage($article, Config::get('settings.image_path'));

        //Удаляем комментарии
        // $article->comments() - это метод в модели Article для доступа к комментариям, что привязвнны к статьи
        // и возвращает обьект конструктора запроса - обьект, который представляет конкретную связь
        //удаляет коментарии
        $article->comments()->delete();

        if ($article->delete()) {
            return ['status' => 'Материал удален'];
        }
    }

}