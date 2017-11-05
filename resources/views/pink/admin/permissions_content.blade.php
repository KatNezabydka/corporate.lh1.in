<div id="content-page" class="content group">
    <div id="content-page" class="content group">

        <h3 class="title_page">{{ Lang::get('ru.permissions') }}</h3>
        {!! Form::open(['url' =>route('admin.permissions.store'),'method' => 'POST']) !!}
        {{--<form action="{{ route('admin.permissions.store') }}" method="POST">--}}
            {{--{{ csrf_field() }}--}}

            <div class="short-table white">

                <table style="width: 100%">

                    <thead>

                    <th>{{ Lang::get('ru.permissions') }}</th>

                    @if(!$roles->isEmpty())
                        @foreach($roles as $item)
                            <th>{{ $item->name }}</th>
                        @endforeach
                    @endif
                    </thead>

                    <tbody>

                    @if(!$priv->isEmpty())
                        @foreach($priv as $val)
                            <tr>
                                <td>{{ $val->name }}</td>
                                @foreach($roles as $role)
                                    <td>
                                        @if($role->hasPermission($val->name))
                                            {{--[]храним массив со списком значений атрибута value, для всех у которых одинаковый id --}}
                                            {!! Form::checkbox("$role->id[]",$val->id,true) !!}
                                        @else
                                            {!! Form::checkbox("$role->id[]", $val->id,null) !!}
                                        @endif

                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endif

                    </tbody>

                </table>

            </div>
        {!! Form::button(Lang::get('ru.update'),['class' => 'btn btn-the-salmon-dance-3','type' => 'submit']) !!}
            {{--<input class="btn btn-the-salmon-dance-3" type="submit" value="{{ Lang::get('ru.update') }}" />--}}
        {{ Form::close() }}
        {{--</form>--}}


    </div>
</div>