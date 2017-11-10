<div id="content-page" class="content group">
    <div class="hentry group">

        {!! Form::open(['url' => (isset($article->id)) ? route('admin.articles.update',['articles'=>$article->alias]) :route('admin.articles.store'),'class'=>'contact-form','method'=>'POST','enctype'=>'multipart/form-data']) !!}

        <ul>
            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">{{Lang::get('ru.title_name') }}: </span>
                    <br/>
                    <span class="sublabel">{{Lang::get('ru.title_portfolio') }}</span><br/>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{--первое значение для атрибута name=, 2е - для value=--}}
                    {!! Form::text('title',(isset($article->title)) ? $article->title : old('title'), ['placeholder'=>'Введите название страницы']) !!}
                </div>
            </li>

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">{{Lang::get('ru.keywords') }}: </span>
                    <br/>
                    <span class="sublabel">{{Lang::get('ru.keywords') }}</span><br/>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{--первое значение для атрибута name=, 2е - для value=--}}
                    {!! Form::text('keywords',(isset($article->keywords)) ? $article->keywords : old('keywords'), ['placeholder'=>'Введите ключевые слова']) !!}
                </div>

            </li>

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">{{Lang::get('ru.meta_desc') }}: </span>
                    <br/>
                    <span class="sublabel">{{Lang::get('ru.meta_desc') }}</span><br/>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{--первое значение для атрибута name=, 2е - для value=--}}
                    {!! Form::text('meta_desc',(isset($article->meta_desc)) ? $article->meta_desc : old('meta_desc'), ['placeholder'=>'Введите мета описание']) !!}
                </div>
            </li>

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">{{Lang::get('ru.alias') }}: </span>
                    <br/>
                    <span class="sublabel">{{Lang::get('ru.add_alias') }}</span><br/>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span>
                    {{--первое значение для атрибута name=, 2е - для value=--}}
                    {!! Form::text('alias',(isset($article->alias)) ? $article->alias : old('alias'), ['placeholder'=>'Введите псевдоним']) !!}
                </div>
            </li>

            <li class="textarea-field">
                <label for="message-contact-us">
                    <span class="label">{{Lang::get('ru.description') }}: </span>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                    {{--первое значение для атрибута name=, 2е - для value=--}}
                    {!! Form::textarea('desc',(isset($article->desc)) ? $article->desc : old('desc'), ['id'=>'editor','placeholder'=>'Введите краткое описание']) !!}
                </div>
                <div class="msg-error"></div>
            </li>

            <li class="textarea-field">
                <label for="message-contact-us">
                    <span class="label">{{Lang::get('ru.desc') }}: </span>
                </label>
                <div class="input-prepend"><span class="add-on"><i class="icon-pencil"></i></span>
                    {{--первое значение для атрибута name=, 2е - для value=--}}
                    {!! Form::textarea('text',(isset($article->text)) ? $article->text : old('text'), ['id'=>'editor2','class' => 'form-control','placeholder'=>'Введите описание']) !!}
                </div>
                <div class="msg-error"></div>
            </li>

            @if(isset($article->img->path))
                <li class="textarea-field">

                    <label>
                        <span class="label">{{Lang::get('ru.image_') }}: </span>
                    </label>
                    {!! Html::image(asset(config('settings.theme')).'/images/articles/'.$article->img->path,'',['style'=>'width:30%']) !!}
                    {!! Form::hidden('old_image',$article->img->path) !!}

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

            <li class="text-field">
                <label for="name-contact-us">
                    <span class="label">{{Lang::get('ru.category') }}: </span>
                    <br/>
                    <span class="sublabel">{{Lang::get('ru.category_') }}</span><br/>
                </label>
                <div class="input-prepend">
                    {{--1: имя для тега select (name). 2: массив, который сформирован в контроллере. 3: активная категория  --}}
                    {{--3й параметр только если редактируем существующую категорию--}}
                    {!! Form::select('category_id', $categories, isset($article->category_id) ? $article->category_id : '') !!}
                </div>
            </li>
            {{--Актуально только если редактируем данный материал, так как контроллер использует тип recource, а значит редактирование это PUT--}}
            {{--PUT на прчмую отправить не можем, поэтому используем скрытое поле hidden--}}
            @if(isset($article->id))
            <input type="hidden" name="_method" value="PUT">

            @endif

            <li class="submit-button">
                {!! Form::button(Lang::get('ru.save'), ['class' => 'btn btn-the-salmon-dance-3','type'=>'submit']) !!}
            </li>

        </ul>


        {!! Form::close() !!}

        <script>
            CKEDITOR.replace('editor');
            CKEDITOR.replace('editor2');
        </script>

    </div>
</div>