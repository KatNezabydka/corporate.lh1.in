<div id="content-page" class="content group">
    <div class="hentry group">
        {{--<script>--}}
        {{--jQuery(document).ready(function($){--}}
        {{--$('.sidebar').remove();--}}

        {{--if( !$('#primary').hasClass('sidebar-no') ) {--}}
        {{--$('#primary').removeClass().addClass('sidebar-no');--}}
        {{--}--}}

        {{--});--}}
        {{--</script>--}}

        @if($portfolios)

            <div id="portfolio" class="portfolio-big-image">

                @foreach($portfolios as $portfolio)

                    <div class="hentry work group">
                        <div class="work-thumbnail">
                            <div class="nozoom">
                                <img src="{{asset(config('settings.theme'))}}/images/projects/{{ $portfolio->img->max }}" alt="0061"
                                     title="{{$portfolio->title}}"/>
                                <div class="overlay">
                                    <a class="overlay_img"
                                       href="{{asset(config('settings.theme'))}}/images/projects/{{ $portfolio->img->path }}"
                                       rel="lightbox" title={{$portfolio->title}}></a>
                                    {{-- функция route() - ссылка на страницу просмотра детальной информации по портфолио--}}
                                    {{--portfolios(это псевдоним).show = какой метод отобразит, ['slias' => $portfolio->alias] = параметры--}}
                                    <a class="overlay_project"
                                       href="{{route('portfolios.show',['slias' => $portfolio->alias])}}"></a>
                                    <span class="overlay_title">{{$portfolio->title}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="work-description">
                            <h3>{{$portfolio->title}}</h3>
                            <p>{{str_limit($portfolio->text, 130)}}</p>
                            <div class="clear"></div>
                            <div class="work-skillsdate">
                                <p class="skills"><span class="label">{{Lang::get('ru.Filter')}}
                                        : </span> {{ $portfolio->filter->title }}</p>
                                <p class="workdate"><span class="label">{{Lang::get('ru.Customer')}}
                                        : </span> {{ $portfolio->customer }}</p>

                                {{--Добавляем дату создания--}}
                                @if($portfolio->create_at)
                                    <p class="workdate"><span class="label">{{Lang::get('ru.Year')}}
                                            : </span> {{ $portfolio->ctreated_at->format('Y') }}</p>
                                @endif

                            </div>
                            <a class="read-more "
                               href="{{route('portfolios.show',['slias' => $portfolio->alias])}}">{{Lang::get('ru.read_more')}}</a>
                        </div>
                        <div class="clear"></div>
                    </div>

                @endforeach

                    {{--Делаем постраничную навигацию--}}

                    <div class="general-pagination group">
                        {{--lastPage() - вернет номер последней страницы постраничной навигации--}}
                        @if($portfolios->lastPage() > 1)
                            <ul class="pagination">
                            {{--для отображения стрелочки "Предыдущая страница"--}}
                            @if($portfolios->currentPage() !== 1)
                                {{--Путь прописываем к предыдущей странице относительно той, которая имеется--}}
                                <a href="{{ $portfolios->url(($portfolios->currentPage() - 1 )) }}">{{ Lang::get('pagination.previous') }}</a>
                            @endif

                            @for($i = 1; $i <= $portfolios->lastPage(); $i++)

                                @if($portfolios->currentPage() == $i)
                                    {{--если находимся на текущей странице - делаем ее выбранной и ссылку делаем не активной--}}
                                    <a class="selected disabled">{{ $i }}</a>
                                @else
                                    <a href="{{ $portfolios->url($i) }}">{{ $i }}</a>
                                @endif
                            @endfor

                            {{--для отображения стрелочки "Последующая страница страница"--}}
                            @if($portfolios->currentPage() !== $portfolios->lastPage())
                                <a href="{{ $portfolios->url(($portfolios->currentPage() + 1 )) }}">{{ Lang::get('pagination.next') }}</a>
                            @endif
                            </ul>
                        @endif

                    </div>

            </div>

        @endif

        <div class="clear"></div>
    </div>

    <!-- START COMMENTS -->
    <div id="comments">
    </div>
    <!-- END COMMENTS -->
</div>