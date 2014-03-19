<?php
namespace Api;
use BaseController;
use Request, User, Response, Validator, Lang, Auth, Confide, Config;

class UserController extends BaseController {
	public function index()
	{
		$user = Auth::user();
		if(!$user)
           return Response::json(array('error'=> true, 'message'	=> 'You are not logged in'));
        unset($user->confirmed);
        unset($user->confirmation_code);
        return Response::json(array('error'=> false, 'user'	=> $user->toArray())); 
	}

	public function store()
	{

		$user = new User;

        $user->username = Request::get( 'username' );
        $user->first_name = Request::get( 'first_name' );
        $user->last_name = Request::get( 'last_name' );
        $user->email = Request::get( 'email' );
        $user->password = Request::get( 'password' );

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Request::get( 'password_confirmation' );

        // Save if valid. Password field will be hashed before save
        $user->save();

        if ( $user->id )
        {
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
           return Response::json(array('error'=> false, 'message'	=> Lang::get('confide::confide.alerts.account_created')));
        }
        else
        {
            // Get validation errors (see Ardent package)
        	$error = $user->errors()->all(':message');
        	return Response::json(array('error'=> true, 'message'	=> $error));
        }		
	}

	public function login()
    {
        $input = array(
            'email'    => Request::get( 'email' ), // May be the username too
            'username' => Request::get( 'email' ), // so we have to pass both
            'password' => Request::get( 'password' ),
            'remember' => Request::get( 'remember' ),
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
            return Response::json(array('error'=> false, 'message'	=> 'Logged in successfully')); // change it to '/admin', '/dashboard' or something
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
           	 return Response::json(array('error'=> true, 'message'	=> $err_msg)); 

        }
    }

	public function update()
	{
		$user = Auth::user();
		if(!$user)
           return Response::json(array('error'=> true, 'message'	=> 'You are not logged in'));
        $user->username = Request::get( 'username' ) ? Request::get( 'username' ) : $user->username;
        $user->email = Request::get( 'email' ) ? Request::get( 'email' ) : $user->email;
        $user->first_name = Request::get( 'first_name' ) ? Request::get( 'first_name' ) : $user->first_name;
        $user->last_name = Request::get( 'first_name' ) ? Request::get( 'first_name' ) : $user->last_name;
        if(Request::get( 'password' ))
        	$user->password = Request::get( 'password' );
        $user->updateUniques();
        unset($user->confirmed);
        unset($user->confirmation_code);
        return Response::json(array('error'=> false, 'user'	=> $user->toArray())); 
	}

	
	

}
