<div id="content-page" class="content group">
    <div class="hentry group">

        {!! Form::open(['url' => (isset($slider->id)) ? route('admin.sliders.update',['sliders'=>$slider->id]) :route('admin.sliders.store'),'class'=>'contact-form','method'=>'POST','enctype'=>'multipart/form-data']) !!}

        <ul>
            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">{{Lang::get('ru.title_name') }}: </span>
                    <br/>
                    <span class="sublabel">{{Lang::get('ru.title_slider') }}</span><br/>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{--первое значение для атрибута name=, 2е - для value=--}}
                    {!! Form::text('title',(isset($slider->title)) ? $slider->title :'<br /><span></span>') !!}
                </div>
            </li>

            <li class="textarea-field">
                <label for="message-contact-us">
                    <span class="label">{{Lang::get('ru.description') }}: </span>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                    {{--первое значение для атрибута name=, 2е - для value=--}}
                    {!! Form::textarea('desc',(isset($slider->desc)) ? $slider->desc : old('desc'), ['id'=>'editor','placeholder'=>'Введите краткое описание']) !!}
                </div>
                <div class="msg-error"></div>
            </li>


            @if(isset($slider->img))
                <li class="textarea-field">

                    <label>
                        <span class="label">{{Lang::get('ru.image_') }}: </span>
                    </label>
                    {!! Html::image(asset(env('THEME')).'/'.$slider->img ,'',['style'=>'width:30%']) !!}
                    {!! Form::hidden('old_image',$slider->img) !!}

                </li>
            @endif

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">{{Lang::get('ru.image') }}: </span>
                    <br/>
                    <span class="sublabel">{{Lang::get('ru.image_') }}</span><br/>
                </label>
                <div class="input-prepend">
                    {!! Form::file('image', ['class' => 'filestyle','data-buttonText'=>Lang::get('ru.chose_image'),'data-buttonName'=>"btn-primary",'data-placeholder'=>Lang::get('ru.no_file')]) !!}
                </div>
            </li>

            {{--Актуально только если редактируем данный материал, так как контроллер использует тип recource, а значит редактирование это PUT--}}
            {{--PUT на прчмую отправить не можем, поэтому используем скрытое поле hidden--}}
            @if(isset($slider->id))
                <input type="hidden" name="_method" value="PUT">
            @endif

            <li class="submit-button">
                {!! Form::button(Lang::get('ru.save'), ['class' => 'btn btn-the-salmon-dance-3','type'=>'submit']) !!}
            </li>

        </ul>


        {!! Form::close() !!}

        <script>
            CKEDITOR.replace('editor');
        </script>

    </div>
</div>