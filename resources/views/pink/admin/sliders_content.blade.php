<div id="content-page" class="content group">
    <div class="hentry group">
        <h3 class="title_page">{{ Lang::get('ru.admin_sliders') }}</h3>


        <div class="short-table white">
            <table style="width: 100%" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th >ID</th>
                    <th>{{ Lang::get('ru.title') }}</th>
                    <th>{{ Lang::get('ru.description') }}</th>
                    <th>{{ Lang::get('ru.image') }}</th>
                    <th>{{ Lang::get('ru.action') }}</th>
                </tr>
                </thead>

                <tbody>
                @if($sliders)
                    @foreach($sliders as $slider)
                        <tr>
                            <td class="align-left">{{ $slider->id}}</td>
                            <td class="align-left">{!! Html::link(route('admin.sliders.edit',['sliders'=>$slider->id]),$slider->title) !!}</td>
                            <td class="align-left">{{ $slider->desc }}</td>
                            <td >
                            @if(isset($slider->img))
                                {{--изображение кодируется в формате json в Repository метод check()--}}
                                {!! Html::image(asset(config('settings.theme')).$slider->img ) !!}
                            @endif
                            </td >
                            <td>
                                {!! Form::open(['url' => route('admin.sliders.destroy',['sliders' =>$slider->id]),'class' => 'form-horizontal','method' => 'POST']) !!}
                                {{--функция-хелпер, которая ообщает laravel что отправляем запрос delete--}}
                                {{ method_field('DELETE') }}
                                {!! Form::button('Удалить',['class' => 'btn btn-french-5','type' => 'submit', 'onclick' => "return confirm('Вы точно хотите удалить сдайдер из списка?')"]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        {!! Html::link(route('admin.sliders.create'),'Добавить слайдер',['class' => 'btn btn-the-salmon-dance-3']) !!}


    </div>

</div>
