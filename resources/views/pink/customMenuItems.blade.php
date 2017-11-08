@foreach($items as $item)
    {{--Для подсветки данного элемента меню...активного - если захотим - сделаем--}}
    <li {{ (URL::current() == $item->url()) ? "class=active" : ''}}>
        {{--Возвращает путь конкретного пункта меню--}}
        <a href="{{ $item->url() }}">{{ $item->title }}</a>
        @if($item->hasChildren())

            <ul class="sub-menu">
                {{--children() - возвращает только дочерние пункты меню--}}
                @include(env('THEME').'.customMenuItems',['items' => $item->children()])
            </ul>

        @endif
    </li>

@endforeach
