@if($menu)

    <div class="menu classic">
        <ul id="nav" class="menu">
            {{--roots() - возвращает только родительские пункты меню--}}
            @include(config('settings.theme').'.customMenuItems',['items' => $menu->roots()])

        </ul>
    </div>

@endif

