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
        return View::make("user_create");
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
        $user->firstname = Input::get( 'firstname' );
        $user->lastname = Input::get( 'lastname' );
        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Input::get( 'password_confirmation' );
        // Save if valid. Password field will be hashed before save




//Validations     
        if(ctype_alnum($user->username))
        {}
        else{
            $errorcheck=1;
            Session::put('username_error', 'Username cannot containt special characters.');}

        
        if(ctype_alpha(str_replace(' ','',$user->firstname)))
        {}
        else{
            $errorcheck=1;
            Session::put('firstname_error', 'First Name cannot containt special characters.');}

        if(ctype_alpha(str_replace(' ','',$user->lastname)))
          {}
        else{
            $errorcheck=1;
            Session::put('lastname_error', 'Last Name cannot containt special characters.');}

        if(filter_var($user->email, FILTER_VALIDATE_EMAIL))
          {}
        else{
            $errorcheck=1;
            Session::put('email_error', 'Invalid Email');}

        if(ctype_alnum($user->password))
        {
            if ($user->password!=$user->password_confirmation){
                $errorcheck=1;
            Session::put('password_error', 'Password did not match with confirm password.');
            }
        }
        else{
            $errorcheck=1;
            Session::put('password_error', 'Password is required to be in alphanumeric form.');
        }
                       

        if ( $errorcheck==0 )
        {
                    $user->save();
                        $notice = "User created successfullly! ";         
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
                        return Redirect::action('viewuser')
                            ->with( 'notice', $notice );
        }
        else
        {

     
                        return Redirect::action('UserController@create')
                            ->withInput(Input::except('password'));
        }
    }



public function edit()
    {
        $id=Input::get( 'id' );
        $user = User::find($id);
   
        $user->email = Input::get( 'email' );
           $password = Input::get( 'password' );
        $user->password = Hash::make($password);
        $user->firstname = Input::get( 'firstname' );
        $user->lastname = Input::get( 'lastname' );
        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.


        $user->password_confirmation = Input::get( 'password_confirmation' );
        // Save if valid. Password field will be hashed before save



$errorcheck=0;

//Validations     
        
        if(ctype_alpha(str_replace(' ','',$user->firstname)))
        {}
        else{
            $errorcheck=1;
            Session::put('firstname_error', 'First Name cannot containt special characters.');}

        if(ctype_alpha(str_replace(' ','',$user->lastname)))
          {}
        else{
            $errorcheck=1;
            Session::put('lastname_error', 'Last Name cannot containt special characters.');}

        if(filter_var($user->email, FILTER_VALIDATE_EMAIL))
          {}
        else{
            $errorcheck=1;
            Session::put('email_error', 'Invalid Email');}

        if(ctype_alnum($password))
        {
            if ($password!=$user->password_confirmation){
                $errorcheck=1;
            Session::put('password_error', 'Password did not match with confirm password.');
            }
        }
        else{
            $errorcheck=1;
            Session::put('password_error', 'Password is required to be in alphanumeric form.');
        }
                       

if($errorcheck==1)
                       return Redirect::back();
        
     else
        {
                  
DB::table('users')
            ->where('id', $id)
            ->update(array( 'email' => $user->email, 'password' => $user->password, 'firstname' => $user->firstname, 'lastname' => $user->lastname));
                        $notice = "successfully edited user. ";         
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
                        return Redirect::action('viewuser')
                            ->with( 'notice', $notice );
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
            return Redirect::to('dashboard');
        }
        else
        {
            return View::make('login');
        }
    }















    /**
     * Attempt to do login
     *
     */
    public function do_login()
    {
        $input = array(
            'email'    => Input::get( 'username' ), 
            'username' => Input::get( 'username' ), 
            'password' => Input::get( 'password' ),
            'remember' => Input::get( 'remember' ),
        );

        $username = Input::get( 'username' ); 

        // Authenticate User
        if ( Confide::logAttempt($input,true) ) 
        {

            $fetched_user = User::whereUsername($username)->get();

            // get username from fetched data on DB
            foreach ($fetched_user as $key ) 
            {
               $fetched_username = $key->username;
            }

            // compare input username on fetched username
            $result = strcmp($fetched_username,$username);
            
            if($result == 0)
            {
                return Redirect::intended('dashboard'); 
            }

            Confide::logout();

            $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            return Redirect::to('login')->withInput(Input::except('password'))->with( 'error', $err_msg );

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
     * @param    string  $code
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

    public function dashboard()
    {
        return View::make('dashboard');
    }
    
    public function viewUser()
    {
        return View::make('viewuser');
    }

    public function getRole()
    {
        
        $admin = new Role();
        $admin->name = 'Requisitioner';
        $admin->save();
     
        $member = new Role();
        $member->name = 'Procurement Personnel';
        $member->save();

        $member = new Role();
        $member->name = 'Administrator';
        $member->save();

        return Redirect::to('login'); 
    }
}
