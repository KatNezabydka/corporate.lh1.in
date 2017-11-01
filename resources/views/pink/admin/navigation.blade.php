@if($menu)
    <div class="menu classic">
        {{--$menu - это билдер asUl - как ненумерованный список аттрибут class строка menu --}}
        {!! $menu->asUl(['class'=>'menu']) !!}

    </div>
@endif
