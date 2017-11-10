@extends(config('settings.theme').'.layouts.site')


@section('content')

    <div id="content-page" class="content group">
        <div class="hentry group">

            <form id="contact-form-contact-us" class="contact-form" method="POST" action="">
                {{ csrf_field() }}
                <fieldset>
                    <ul>
                        <li class="text-field">
                            <label for="login">
                                <span class="label">{{Lang::get('ru.name')}}</span>
                                <br />					<span class="sublabel">{{Lang::get('ru.name_field')}}</span><br />
                            </label>
                            <div class="input-prepend"><span class="add-on"><i class="icon-user"></i></span><input type="text" name="login" id="login" class="required" value="" /></div>
                            @if ($errors->has('login'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('login') }}</strong>
                                </span>
                            @endif
                        </li>
                        <li class="text-field">
                            <label for="password">
                                <span class="label">{{Lang::get('ru.password')}}</span>
                                <br />					<span class="sublabel">{{Lang::get('ru.password_field')}}</span><br />
                            </label>
                            <div class="input-prepend"><span class="add-on"><i class="icon-envelope"></i></span><input type="password" name="password" class="required email-validate" value="" /></div>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </li>
                        <li class="submit-button">
                            <input type="submit" name="yit_sendmail" value="{{Lang::get('ru.enter')}}" class="sendmail alignright" />
                        </li>
                    </ul>
                </fieldset>
            </form

@endsection

