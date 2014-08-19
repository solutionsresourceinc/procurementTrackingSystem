<?php


class UserController extends BaseController {

    public function disable() // FOR DISABLING USER ACCOUNTS
    {
        $errors="Account Deactivated.";
        $id=Input::get('hide');

        DB::table('users')->where('id', $id)->update(array('confirmed' => 0));
        
        $taskcount=DB::table('taskdetails')->count();
        if($taskcount!=0){
            $taskcountcheck=DB::table('taskdetails')->where('status', 'Active')->where('assignee_id', $id)->count();
            if($taskcountcheck!=0)
                DB::table('taskdetails')->where('status', 'Active')->where('assignee_id', $id)->update(array( 'assignee_id' => '0', 'status' => "New"));
        }
        return Redirect::to('user/view');
    }

    public function activate() // FOR ACTIVATION OF DISABLED USER ACCOUNTS
    {
        $errors="Account Activated.";
        $id=Input::get('hide');

        DB::table('users')->where('id', $id)->update(array('confirmed' => 1));
        
        return Redirect::to('user/view');
    }

    public function create() // MAKE CREATE USER VIEW
    {
        return View::make("user_create");
    }

    public function store() // SAVING FOR USER ACCOUNT CREATION
    {
        $user = new User;

        $user->username = trim(Input::get( 'username' ));
        $checkusername = User::where('username', $user->username)->first();

        $user->email = trim(Input::get( 'email' ));

        $user->password = trim(Input::get( 'password' ));
        $user->firstname = trim(Input::get( 'firstname' ));
        $user->lastname = trim(Input::get( 'lastname' ));
        $user->office_id = Input::get( 'office' );
        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Input::get( 'password_confirmation' );
        // Save if valid. Password field will be hashed before save

        $errorcheck=0;
        $checkusername=0;
        $users= new User; $users = DB::table('users')->get();

        foreach ($users as $userx)
        {
            if (strtoupper($userx->username)==strtoupper($user->username))
            { $checkusername=1; $errorcheck=1; }
        }
        
        if ($checkusername!=0)
        {
            Session::put('username_error', 'Username is already in use.');
        }
        
        $checkemail=0;

        $users= new User; $users = DB::table('users')->get();

        foreach ($users as $userx)
        {
            if (strtoupper($userx->email)==strtoupper($user->email))
            { $checkemail=1; $errorcheck=1; }
        }
        
        if ($checkemail!=0)
        {
            Session::put('email_error', 'Email is already in use.');
        }

        //Validations     
        if(ctype_alnum($user->username)&&(strlen($user->username)>=6)){}
        else
        {
            $errorcheck=1;
            Session::put('username_error', 'Invalid username.');
        }

        if(ctype_alpha(str_replace(array(' ', '-', '.'),'',$user->firstname))){}
        else
        {
            $errorcheck=1;
            Session::put('firstname_error', 'Invalid first name.');
        }

        if(ctype_alpha(str_replace(array(' ', '-', '.'),'',$user->lastname))){}
        else
        {
            $errorcheck=1;
            Session::put('lastname_error', 'Invalid last name.');
        }

        if(filter_var($user->email, FILTER_VALIDATE_EMAIL)){}
        else
        {
            $errorcheck=1;
            Session::put('email_error', 'Invalid email.');
        }

        if(ctype_alnum($user->password))
        {
            if ($user->password!=$user->password_confirmation)
            {
                $errorcheck=1;
                Session::put('password_error', 'Password did not match with confirm password.');
            }
        }
        else
        {
            $errorcheck=1;
            Session::put('password_error', 'Invalid password.');
        }

        if ( $errorcheck==0 )
        {
            $user->save();
            $username=$user->username;
     
            $assign= new Assigned;
            $assign->role_id=Input::get('role');
            $assign->user_id=$user->id;
            $assign->save();
            $desig = new UserHasDesignation;
            $desig->users_id= $user->id;
            $desig->designation_id=0;
            $desig->save();
            $notice = "User created successfully. "; 
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
            return Redirect::action('UserController@viewUser')->with( 'notice', $notice );
        }
        else
        {
            Session::put('msg', 'Failed to create user.');

            return Redirect::action('UserController@create')->withInput(Input::except('password'));
        }
    }

    public function editprof_view($id) // MAKE EDIT PROFILE VIEW
    {
        return View::make('user.editprofile')->with('id',$id);
    }

    public function editprof() // SAVING FOR OWN ACCOUNT EDITING
    {
        $id=Auth::user()->id;
        $user = User::find($id);

        $user->email = Input::get('email');
        $password = " ".Input::get( 'password' );
        $cpassword = " ".Input::get( 'password_confirmation' );

        $passnotchange=0;
        $errorcheck=0;
        if($password == $cpassword)
        {
            if($password==" " && $cpassword == " ")
            {
                $passnotchange=1;           
            }
            else
            {
                $password = substr($password, 1);
                $user->password = Hash::make($password);
                $user->password_confirmation = Input::get( 'password_confirmation' );
            }
        }
        
        if(filter_var($user->email, FILTER_VALIDATE_EMAIL)){}
        else
        {
            $errorcheck=1;
            Session::put('email_error', 'Invalid email.');
        }

        if($passnotchange==1)
        {

        }
        elseif(ctype_alnum($password)&&(strlen($password)>=6))
        {
            if ($password != $user->password_confirmation)
            {
                $errorcheck=1;
                Session::put('password_error', 'Password did not match with confirm password.');
            }
        }
        else
        {
            $errorcheck=1;
            Session::put('password_error', 'Invalid password.');
        }

        if($errorcheck==1)
        {
            Session::put('msg', 'Failed to edit user.');
            return Redirect::back()->withInput();
        }
        else
        {
            $roles=input::get('role');
            if($roles=="3")
                $role=1;
            elseif($roles=="2")
                $role=2;
            else
                $role=3;
                    
            DB::table('assigned_roles')->where('user_id', $id)->update(array( 'role_id' => $role));

            DB::table('users')->where('id', $id)->update(array( 'email' => $user->email, 'password' => $user->password));
            
            $notice = "Successfully edited profile. ";         
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
            return Redirect::action('UserController@viewUser')->with( 'notice', $notice );
        }
    }

    public function edit_view($id) // MAKE EDIT USER VIEW
    {
        return View::make('useredit')->with('id',$id);
    }

    public function edit() // SAVING FOR USER ACCOUNT EDITING
    {
        $id=Input::get( 'id' );
        $user = User::find($id);

        $user->email = trim(Input::get( 'email' ));

        $password = " ".Input::get( 'password' );
        $cpassword = " ".Input::get( 'password_confirmation' );

        $passnotchange=0;
        if($password == $cpassword)
        {
            if($password==" " && $cpassword == " ")
            {
                $passnotchange=1;           
            }
            else
            {
                $password = substr($password, 1);
                $user->password = Hash::make($password);
                $user->password_confirmation = Input::get( 'password_confirmation' );
            }
        }

        $user->firstname = trim(Input::get( 'firstname' ));
        $user->lastname = trim(Input::get( 'lastname' ));
        $user->office_id= Input::get('office');
        $user->password_confirmation = Input::get( 'password_confirmation' );

        $errorcheck=0;

        //Validations     
        if(ctype_alpha(str_replace(' ','',$user->firstname))){}
        else
        {
            $errorcheck=1;
            Session::put('firstname_error', 'Invalid first name.');
        }

        if(ctype_alpha(str_replace(' ','',$user->lastname))){}
        else
        {
            $errorcheck=1;
            Session::put('lastname_error', 'Invalid last name.');
        }
    
        if(filter_var($user->email, FILTER_VALIDATE_EMAIL)){}
        else
        {
            $errorcheck=1;
            Session::put('email_error', 'Invalid email.');
        }

        if($passnotchange==1){}
        elseif(ctype_alnum($password)&&(strlen($password)>=6))
        {
            if($password!=$user->password_confirmation)
            {
                $errorcheck=1;
                Session::put('password_error', 'Password did not match with confirm password.');
            }
        }
        else
        {
            $errorcheck=1;
            Session::put('password_error', 'Invalid password.');
        }

        if($errorcheck==1)
        {
            Session::put('msg', 'Failed to edit user.');
            return Redirect::back()->withInput();
        }
        else
        {
            $roles=input::get('role');
            if($roles=="3")
                $role=3;
            elseif($roles=="2")
                $role=2;
            else
                $role=1;
                    
        DB::table('assigned_roles')->where('user_id', $id)->update(array( 'role_id' => $role));

        DB::table('users')->where('id', $id)->update(array( 'firstname'=>$user->firstname, 'lastname'=>$user->lastname, 'email' => $user->email, 'password' => $user->password, 'office_id' => $user->office_id,));
        
        $notice = "Successfully edited user. ";         
        // Redirect with success message, You may replace "Lang::get(..." for your custom message.
        return Redirect::action('UserController@viewUser')->with( 'notice', $notice );
        }
    }

    public function login() // DISPLAYS LOGIN FORM
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

    public function do_login() // LOGIN FUNCTION
    {
        $username = Input::get( 'username' ); 
        $input = array(
            'email'    => Input::get( 'username' ), 
            'username' => Input::get( 'username' ), 
            'password' => Input::get( 'password' ),
            'remember' => Input::get( 'remember' ),
            );

        // get username from fetched data on DB
        $fetched_user = User::whereUsername($username)->get();

        foreach ($fetched_user as $key ) 
        {
         $fetched_username = $key->username;
         $status = $key->confirmed;
        }

        // Authenticate User
        if ( Confide::logAttempt($input) ) 
        {

            // compare input username on fetched username
            $result = strcmp($fetched_username,$username);

            if($result == 0)
            {
                if($status == 0)
                {
                    Confide::logout();
                    $err_msg2 = "Your account has been deactivated. Please contact authorized Personnel";
                    return Redirect::to('login')->withInput(Input::except('password'))->with( 'deactivated', $err_msg2 );
                }
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

    public function logout()
    {
        Confide::logout();
        
        return Redirect::to('/');
    }

    public function dashboard()
    {
        $purchases = Purchase::all();
        $date_today =date('Y-m-d H:i:s');

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
