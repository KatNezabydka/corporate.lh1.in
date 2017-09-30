@if($menu)

    <div class="menu classic">
        <ul id="nav" class="menu">
            {{--roots() - возвращает только родительские пункты меню--}}
            @include(env('THEME').'.customMenuItems',['items' => $menu->roots()])

        </ul>
    </div>

@endif

