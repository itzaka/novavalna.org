<?php
/*
|--------------------------------------------------------------------------
| Confide Controller Template
|--------------------------------------------------------------------------
|
| This is the default Confide controller template for controlling user
| authentication. Feel free to change to your needs.
|
*/

class UserController extends BaseController {

    /**
     * Displays the form for account creation
     *
     */
    public function create()
    {
        return View::make('users.create');
    }

    /**
     * Stores new account
     *
     */
    public function store()
    {
        $user = new User;

        $user->username = Input::get( 'username' );
        $user->email = Input::get( 'email' );
        $user->password = Input::get( 'password' );

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Input::get( 'password_confirmation' );

        // Save if valid. Password field will be hashed before save
        $user->save();

        if ( $user->id )
        {
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
                        return Redirect::action('UserController@login')
                            ->with( 'notice', Lang::get('confide::confide.alerts.account_created') );
        }
        else
        {
            // Get validation errors (see Ardent package)
            $error = $user->errors()->all(':message');

                        return Redirect::action('UserController@create')
                            ->withInput(Input::except('password'))
                ->with( 'error', $error );
        }
    }

    /**
     * Displays the login form
     *
     */
    public function login()
    {
        if( Confide::user() )
        {
            // If user is logged, redirect to internal 
            // page, change it to '/admin', '/dashboard' or something
            return Redirect::to('/');
        }
        else
        {
            return View::make(Config::get('confide::login_form'));
        }
    }

    /**
     * Attempt to do login
     *
     */
    public function do_login()
    {
        $input = array(
            'email'    => Input::get( 'email' ), // May be the username too
            'username' => Input::get( 'email' ), // so we have to pass both
            'password' => Input::get( 'password' ),
            'remember' => Input::get( 'remember' ),
        );

        // If you wish to only allow login from confirmed users, call logAttempt
        // with the second parameter as true.
        // logAttempt will check if the 'email' perhaps is the username.
        // Get the value from the config file instead of changing the controller
        if ( Confide::logAttempt( $input, Config::get('confide::signup_confirm') ) ) 
        {
            // Redirect the user to the URL they were trying to access before
            // caught by the authentication filter IE Redirect::guest('user/login').
            // Otherwise fallback to '/'
            // Fix pull #145
            return Redirect::intended('/'); // change it to '/admin', '/dashboard' or something
        }
        else
        {
            $user = new User;

            // Check if there was too many login attempts
            if( Confide::isThrottled( $input ) )
            {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            }
            elseif( $user->checkUserExists( $input ) and ! $user->isConfirmed( $input ) )
            {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            }
            else
            {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

                        return Redirect::action('UserController@login')
                            ->withInput(Input::except('password'))
                ->with( 'error', $err_msg );
        }
    }
    
    /**
     * Attempt to confirm account with code
     *
     * @param  string  $code
     */
    public function confirm( $code )
    {
        if ( Confide::confirm( $code ) )
        {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');
                        return Redirect::action('UserController@login')
                            ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
                        return Redirect::action('UserController@login')
                            ->with( 'error', $error_msg );
        }
    }

    /**
     * Displays the forgot password form
     *
     */
    public function forgot_password()
    {
        return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function do_forgot_password()
    {
        if( Confide::forgotPassword( Input::get( 'email' ) ) )
        {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
                        return Redirect::action('UserController@login')
                            ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
                        return Redirect::action('UserController@forgot_password')
                            ->withInput()
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function reset_password( $token )
    {
        return View::make(Config::get('confide::reset_password_form'))
                ->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     */
    public function do_reset_password()
    {
        $input = array(
            'token'=>Input::get( 'token' ),
            'password'=>Input::get( 'password' ),
            'password_confirmation'=>Input::get( 'password_confirmation' ),
        );

        // By passing an array with the token, password and confirmation
        if( Confide::resetPassword( $input ) )
        {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
                        return Redirect::action('UserController@login')
                            ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
                        return Redirect::action('UserController@reset_password', array('token'=>$input['token']))
                            ->withInput()
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function logout()
    {
        Confide::logout();
        
        return Redirect::to('/');
    }


    /**
     * FACEBOOK CREATE, UPDATE, LOGIN
     *
     */

    public function create_fb()
    {
        if( Auth::user() )
            return Redirect::to('/');
        
        if(Session::get('user') or Session::get('error')) {
            $me = Session::get('user');
            $user = new User;
            $user->uid =  $me['id'];
            $user->first_name = isset($me['first_name']) ? $me['first_name'] : null;
            $user->last_name = isset($me['last_name']) ? $me['last_name'] : null;
            $user->email = isset($me['email']) ? $me['email'] : null;
            $user->city = isset($me['location']['name']) ? $me['location']['name'] : null;
            $user->birthday = isset($me['birthday']) ? $me['birthday'] : null;
            $user->username = isset($me['username']) ? $me['username'] : null;
            $user->gender = isset($me['gender']) ? $me['gender'] : null;
            return View::make('users/fb', compact('user'));
        }

        $facebook = new Facebook(Config::get('facebook'));
        $params = array(
            'scope' => 'email, publish_stream, user_birthday, user_location',
            'redirect_uri' => url('/user/login/fb/callback')
            );
        return Redirect::to($facebook->getLoginUrl($params));
    }
    public function store_fb()
    {
        $birthday = DateTime::createFromFormat('m/d/Y', Input::get( 'birthday' ));
        $user = new User;
        $user->uid = Input::get( 'uid' );
        $user->username = Input::get( 'username' );
        $user->first_name = Request::get( 'first_name' );
        $user->last_name = Request::get( 'last_name' );
        $user->email = Input::get( 'email' );
        $user->city = Input::get( 'city' );
        $user->birthday = $birthday->format('Y-m-d');;
        $user->gender = Input::get( 'gender' );
        $user->password = Input::get( 'password' );
        $user->photo = 'https://graph.facebook.com/'.Input::get( 'uid' ).'/picture?width=400&height=400';
        $user->password_confirmation = Input::get( 'password_confirmation' );
        $user->confirmed = true;
        $user->save();
        if ( $user->id )
        {
            return Redirect::to('/')->with('message', 'Logged in with Facebook');
        }
        else
        {
            $error = $user->errors()->all(':message');
            return Redirect::action('UserController@fb_login')->withInput(Input::except('password'))->with( 'error', $error );
        }
    }

    public function login_fb()
    {
        if( Auth::user() and Auth::user()->uid )
            return Redirect::to('/');
        $facebook = new Facebook(Config::get('facebook'));
        $params = array(
            'scope' => 'email, publish_stream, user_birthday, user_location',
            'redirect_uri' => url('/user/login/fb/callback')
            );
        return Redirect::to($facebook->getLoginUrl($params));
    }
    public function login_fb_callback()
    {
        $code = Input::get('code');
        if (strlen($code) == 0) return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');
        $facebook = new Facebook(Config::get('facebook'));
        $uid = $facebook->getUser();
        if ($uid == 0) return Redirect::to('/')->with('message', 'There was an error');
        $me = $facebook->api('/me');
        $user = User::whereUid($uid)->first();
        if (!$user) {
            if($user = Auth::user())
            {
                $user->uid = $me['id'];
                $user->updateUniques();
                return Redirect::to('/');
            }
            else
                return Redirect::to('user/signup-fb')->with('user', $me);
        }
        else
            Auth::login($user);
            return Redirect::intended('/')->with('message', 'Logged in with Facebook');
    }

}
