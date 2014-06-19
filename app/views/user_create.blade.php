@extends('layouts.login')

@section('content')

<div>
    <h2>Create User</h2>
</div>    

<form method="POST" action="{{{ (Confide::checkAction('UserController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8" class = 'form-signin'>
    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
    <fieldset>
 
    @if ( Session::get('msg') )
    <div class="alert alert-error alert-danger">
          {{ Session::get('msg'); }} 
          </div>
    @endif

        <div class="form-group">
            <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
            <input class="form-control"  type="text" name="username" id="username" value="{{{ Input::old('username') }}}" required>

            @if ( Session::get('username_error') )

                     <small> {{ Session::get('username_error'); }}   </small>
      
    
            @endif

        </div>

         <div class="form-group">
            <label for="firstname">First Name</label>
            <input class="form-control"  type="test" name="firstname" id="firstname" value="{{{ Input::old('firstname') }}}" required>
            @if ( Session::get('firstname_error') )
             <small> {{ Session::get('firstname_error'); }}   </small>
      
           
            @endif
        </div>
           <div class="form-group">
            <label for="lastname">Last Name</label>
            <input class="form-control" type="text" name="lastname" id="lastname" value="{{{ Input::old('lastname') }}}" required>
            @if ( Session::get('lastname_error') )
             <small>{{ Session::get('lastname_error'); }}
           </small>
      
            @endif
        </div>

        <div class="form-group">
            <label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
            <input class="form-control"  type="text" name="email" id="email" value="{{{ Input::old('email') }}}" required>
            @if ( Session::get('email_error') )
              <small>{{ Session::get('email_error'); }}    </small>
      
  
            @endif
        </div>
        <div class="form-group">
            <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
            <input class="form-control" type="password" name="password" id="password" required>
             @if ( Session::get('password_error') )
           <small>{{ Session::get('password_error'); }}   </small>
            @endif
        </div>
        <div class="form-group">
            <label for="password_confirmation">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
            <input class="form-control"  type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
     <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" name="role">
            	{{ $role=Input::old('role'); }}
                <option value="1" <?php if($role==1) echo "selected"; ?> >Admin</option>
                <option value="2" <?php if($role==2) echo "selected"; ?> >Procurement Personel</option>
                <option value="3" <?php if($role==3) echo "selected"; ?> >Requisitioner</option>
            </select>
           
        </div>
             <div class="form-group">
            <label for="role">Office</label>
            <select class="form-control" name="office">

                <option value=0 >none</option>
                <?php 
$office= new Office; $office = DB::table('offices')->get();

?>
@foreach ($office as $offices)
<option value= {{ $offices->id }}> {{ $offices->officeName }}</option>


@endforeach
         
            </select>
        </div>

      
        <div class="form-actions form-group">
          <button type="submit" class="btn btn-default" name="submit">{{{ Lang::get('confide::confide.signup.submit') }}}</button>
        </div>

    </fieldset>
</form>
<?php
Session::forget('username_error');
Session::forget('firstname_error');
Session::forget('lastname_error');
Session::forget('password_error');
Session::forget('email_error');
Session::forget('msg');
?>




@stop