        @if ( Session::get('error') )
            <div class="alert alert-error alert-danger">
                @if ( is_array(Session::get('error')) )
                    {{ head(Session::get('error')) }}
                @endif
            </div>
        @endif

        @if ( Session::get('notice') )
            <div class="alert">{{ Session::get('notice') }}</div>
        @endif


{{Form::open(array('url' => 'user'))}}

    {{Form::label('username')}}<br>
    {{Form::text('username')}}<br>
    {{Form::label('email')}}<small>Confirmation required</small><br>
    {{Form::email('email')}}<br>
    {{Form::label('password')}}<br>
    {{Form::password('password')}}<br>
    {{Form::label('password_confirmation')}}<br>
    {{Form::password('password_confirmation')}}<br>
    {{Form::submit('submit')}}<br>

        
        
{{Form::close()}}
            <a href="{{url('user/signup-fb')}}" > Вход чрез facebook профил</a>
