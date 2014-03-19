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

{{Form::open(array('url' => 'user/signup-fb'))}}
    {{Form::hidden('uid', $user->uid)}}<br>

    {{Form::label('first_name')}}<br>
    {{Form::text('first_name', $user->first_name)}}<br>
    {{Form::label('last_name')}}<br>
    {{Form::text('last_name', $user->last_name)}}<br>
    {{Form::label('username')}}<br>
    {{Form::text('username', $user->username)}}<br>
    {{Form::label('city')}}<br>
    {{Form::text('city', $user->city)}}<br>
    {{Form::label('birthday')}}<br>
    {{Form::text('birthday', $user->birthday)}}<br>
    {{Form::label('email')}} <small>Confirmation required</small><br>
    {{Form::email('email', $user->email)}}<br>
    {{Form::label('gender')}}<br>
    <select name="gender" id="gender">
        <option value="Мъж">Мъж</option>
        <option value="Жена" @if($user->gender == 'female') selected @endif>Жена</option>
    </select><br>
    {{Form::label('password')}}<br>
    {{Form::password('password')}}<br>
    {{Form::label('password_confirmation')}}<br>
    {{Form::password('password_confirmation')}}<br>
    {{Form::submit('submit')}}<br>

        
        
{{Form::close()}}
