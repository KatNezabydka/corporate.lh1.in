<div id="content-page" class="content group">
    <div class="hentry group">
        <h3 class="title_page">Пользователи</h3>


        <div class="short-table white">
            <table style="width: 100%" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Login</th>
                    <th>Role</th>
                    <th>Удалить</th>
                </tr>
                </thead>

                <tbody>
              @if($users)
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        {{--чтобы показать имя в виде ссылки на редактирование--}}
                        <td>{!! Html::link(route('admin.users.edit',['users'=>$user->id]),$user->name) !!}</td>
                        <td >{{ $user->email }}</td>
                        <td >{{ $user->login }}</td>
                        {{--так как ролец может быть несколько--}}
                        <td>{{ $user->roles->implode('name',', ') }}</td>

                        <td>
                            {!! Form::open(['url' => route('admin.users.destroy',['users' =>$user->id]),'class' => 'form-horizontal','method' => 'POST']) !!}
                            {{--функция-хелпер, которая ообщает laravel что отправляем запрос delete--}}
                            {{ method_field('DELETE') }}
                            {!! Form::button('Удалить',['class' => 'btn btn-french-5','type' => 'submit', 'onclick' => "return confirm('Вы точно хотите удалить пользователя из списка?')"]) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
                  @endif
                </tbody>
            </table>
        </div>

        {!! Html::link(route('admin.users.create'),'Добавить пользователя',['class' => 'btn btn-the-salmon-dance-3']) !!}


    </div>

</div>
