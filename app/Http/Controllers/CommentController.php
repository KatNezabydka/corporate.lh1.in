<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Validator;
use Auth;
use App\Comment;
use App\Article;

class CommentController extends SiteController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //информация с полей формы комментарий доступна в переменной request и мы ее сохраняем
        //все поля кроме
        $data = $request->except('_token', 'comment_post_ID','comment_parent');

        $data['article_id'] = $request->input('comment_post_ID');
        $data['parent_id'] = $request->input('comment_parent');


        $validator = Validator::make($data, [
            'article_id'=> 'integer|required',
            'parent_id'=> 'integer|required',
            'text' => 'string|required'

        ]);
        // Позволяет сделать валидацию определенных полей при определенных условиях
        $validator->sometimes(['name'], 'required|max:255', function ($input) {
            //данные поля нужно проверять только если пользователь не футентифицированный
            return !Auth::check();
        });

        $validator->sometimes(['email'], 'required|max:255|email', function ($input) {
            //данные поля нужно проверять только если пользователь не аутентифицированный
            return !Auth::check();
        });


        // вернет true если какое то поле не прошло проверку
        if($validator->fails()) {
            // фасад Response - абстракция отправляемого ответа
            //$validator->errors()->all() - преобразует объект в массив метод all()
            return \Response::json(['error' => $validator->errors()->all()]);
        }
        //вернет объект модели аутентифицированного пользователя
        $user = Auth::user();

        //передаем в модель Comment наши данные с формы
        $comment = new Comment($data);

        // смотрим если пользователь зарегистрирован - передаем его id
        if ($user) {
            $comment->user_id = $user->id;
        }

        $post = Article::find($data['article_id']);
        //сохраняем новый коммент в бд
        $post->comments()->save($comment);



        //подгрузим информацио о пользователе, если коммент оставил зарегистрированный пользователь
        $comment->load('user');

        $data['id'] = $comment->id;

        $data['email'] = (!empty($data['email'])) ? $data['email'] : $comment->user->email;
        $data['name'] = (!empty($data['name'])) ? $data['name'] : $comment->user->name;
        //аватарка в комменте
        $data['hash'] = md5($data['email']);

        $view_comment = view(env('THEME').'.content_one_comment')->with('data', $data)->render();

        return \Response::json(['success' => TRUE, 'comment' => $view_comment, 'data' => $data]);

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
