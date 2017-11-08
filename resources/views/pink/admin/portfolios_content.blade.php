@if($portfolios)
    <div id="content-page" class="content group">
        <div class="hentry group">
            <h2>{{ Lang::get('ru.admin_portfolios') }}</h2>
            <div class="short-table white">
                <table style="width: 100%" cellspacing="0" cellpadding="0">
                    <thead>
                    <tr>
                        <th class="'align-left">ID</th>
                        <th>{{ Lang::get('ru.title') }}</th>
                        <th>{{ Lang::get('ru.text') }}</th>
                        <th>{{ Lang::get('ru.image') }}</th>
                        <th>{{ Lang::get('ru.Customer') }}</th>
                        <th>{{ Lang::get('ru.alias') }}</th>
                        <th>{{ Lang::get('ru.Filter') }}</th>
                        <th>{{ Lang::get('ru.action') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    {{--Установили библиотеку LaravelCollective для работы с Html и Form--}}
                    @foreach($portfolios as $portfolio)
                        <tr>
                            <td class="align-left">{{ $portfolio->id }}</td>
                            {{--чтобы показать имя в виде ссылки на редактирование--}}
                            <td class="align-left">{!! Html::link(route('admin.portfolios.edit',['portfolios'=>$portfolio->alias]),$portfolio->title) !!}</td>
                            <td class="align-left">{!!  str_limit($portfolio->text,200) !!}</td>
                            <td>
                                @if(isset($portfolio->img->mini))
                                    {{--изображение кодируется в формате json в Repository метод check()--}}
                                    {!! Html::image(asset(env('THEME')).'/images/projects/'.$portfolio->img->mini) !!}
                                @endif
                            </td>
                            <td>{{$portfolio->customer}}</td>
                            <td>{{$portfolio->alias}}</td>
                            <td>{{$portfolio->filter_alias}}</td>
                            <td>
                                {!! Form::open(['url' => route('admin.portfolios.destroy',['portfolios' =>$portfolio->alias]),'class' => 'form-horizontal','method' => 'POST']) !!}
                                {{--функция-хелпер, которая ообщает laravel что отправляем запрос delete--}}
                                {{ method_field('DELETE') }}
                                {!! Form::button( Lang::get('ru.delete') ,['class' => 'btn btn-french-5','type' => 'submit', 'onclick' => "return confirm( 'Вы точно хотите удалить портфолио из списка?')"]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            {!! Html::link(route('admin.portfolios.create'), Lang::get('ru.add_portfolio'),['class' => 'btn btn-the-salmon-dance-3']) !!}


        </div>

    </div>

@endif