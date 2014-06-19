@extends('layouts.login')

@section('content')
<?php $user = User::find($id); ?>

<div>
    <h2>Edit User</h2>
</div>    

<form method="POST" action="edit"  class = 'form-signin'>

    <fieldset>
        <input type="hidden" name="id" value="{{{ $id }}}">
         

        <div class="form-group">
            <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
            <input class="form-control"  type="text" name="username" id="username" value="{{{$user->username}}}" disabled>

         

        </div>

         <div class="form-group">
            <label for="firstname">First Name</label>
            <input class="form-control"  type="test" name="firstname" id="firstname" value="{{{ $user->firstname }}}" required>
            @if ( Session::get('firstname_error') )
            <div class="alert alert-error alert-danger">
                   <small> {{ Session::get('firstname_error'); }}</small>
      
            </div>
            @endif
        </div>
           <div class="form-group">
            <label for="lastname">Last Name</label>
            <input class="form-control" type="text" name="lastname" id="lastname" value="{{{ $user->lastname }}}" required>
            @if ( Session::get('lastname_error') )
            <div class="alert alert-error alert-danger">
                   <small> {{ Session::get('lastname_error'); }}</small>
            
            </div>
            @endif
        </div>

        <div class="form-group">
            <label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
            <input class="form-control"  type="text" name="email" id="email" value="{{{ $user->email }}}" required>
            @if ( Session::get('email_error') )
            <div class="alert alert-error alert-danger">
                   <small> {{ Session::get('email_error'); }}</small>
      
            </div>
            @endif
        </div>
        <div class="form-group">
            <label for="password">{{{ Lang::get('confide::confide.password') }}}</label>
            <input class="form-control" type="password" name="password" id="password" required>
             @if ( Session::get('password_error') )
            <div class="alert alert-error alert-danger">
                   <small> {{ Session::get('password_error'); }}</small>
      
            </div>
            @endif
        </div>
        <div class="form-group">
            <label for="password_confirmation">{{{ Lang::get('confide::confide.password_confirmation') }}}</label>
            <input class="form-control"  type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
     <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" name="role">
                <option value="1">Admin</option>
                <option value="2">Procurement Personel</option>
                <option value="3">Requisitioner</option>
            </select>
           
        </div>
             <div class="form-group">
            <label for="role">Office</label>
            <select class="form-control" name="role">
                <option value="Office">Office</option>
                <option value="Office1">Office1</option>
            </select>
        </div>

      
        <div class="form-actions form-group">
          <button type="submit" class="btn btn-default" name="submit">Save</button>
        </div>

    </fieldset>
</form>
<?php
Session::forget('username_error');
Session::forget('firstname_error');
Session::forget('lastname_error');
Session::forget('password_error');
Session::forget('email_error');
?>




@stop