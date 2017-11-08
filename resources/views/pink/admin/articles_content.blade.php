@if($articles)
    <div id="content-page" class="content group">
        <div class="hentry group">
            <h2>{{ Lang::get('ru.admin_articles') }}</h2>
            <div class="short-table white">
                <table style="width: 100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="'align-left">ID</th>
                        <th>{{ Lang::get('ru.title') }}</th>
                        <th>{{ Lang::get('ru.text') }}</th>
                        <th>{{ Lang::get('ru.image') }}</th>
                        <th>{{ Lang::get('ru.category') }}</th>
                        <th>{{ Lang::get('ru.alias') }}</th>
                        <th>{{ Lang::get('ru.action') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    {{--Установили библиотеку LaravelCollective для работы с Html и Form--}}
                    @foreach($articles as $article)
                        <tr>
                            <td class="align-left">{{ $article->id }}</td>
                            {{--чтобы показать имя в виде ссылки на редактирование--}}
                            <td class="align-left">{!! Html::link(route('admin.articles.edit',['articles'=>$article->alias]),$article->title) !!}</td>
                            <td class="align-left">{!!  str_limit($article->text,200) !!}</td>
                            <td>
                                @if(isset($article->img->mini))
                                    {{--изображение кодируется в формате json в Repository метод check()--}}
                                    {!! Html::image(asset(env('THEME')).'/images/articles/'.$article->img->mini) !!}
                                    @endif
                            </td>
                            <td>{{$article->category->title}}</td>
                            <td>{{$article->alias}}</td>
                            <td>
                                {!! Form::open(['url' => route('admin.articles.destroy',['articles' =>$article->alias]),'class' => 'form-horizontal','method' => 'POST']) !!}
                                {{--функция-хелпер, которая ообщает laravel что отправляем запрос delete--}}
                                {{ method_field('DELETE') }}
                                {!! Form::button('Удалить',['class' => 'btn btn-french-5','type' => 'submit', 'onclick' => "return confirm('Вы точно хотите удалить модель из списка?')"]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            {!! Html::link(route('admin.articles.create'), Lang::get('ru.add_article'),['class' => 'btn btn-the-salmon-dance-3']) !!}


        </div>

    </div>

@endif