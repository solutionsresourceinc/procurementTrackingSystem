@extends('layouts.login')

@section('content')
<?php $user = User::find($id); ?>

<div>
    <h2>Edit User</h2>
</div>    


<form method="POST" action="edit"  class = 'form-signin'>
    <fieldset>

    @if ( Session::get('msg') )
    <div class="alert alert-error alert-danger">
          {{ Session::get('msg'); }} 
          </div>
    @endif

        <input type="hidden" name="id" value="{{{ $id }}}">
         

        <div class="form-group">
            <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
            <input class="form-control"  type="text" name="username" id="username" value="{{{$user->username}}}" disabled>
        </div>

        <div class="form-group">
            <label for="firstname">First Name</label>
            <input class="form-control"  type="test" name="firstname" id="firstname" value="{{{ $user->firstname }}}" required>
            @if ( Session::get('firstname_error') )
             <small>{{ Session::get('firstname_error'); }} </small>
            @endif
        </div>

        <div class="form-group">
            <label for="lastname">Last Name</label>
            <input class="form-control" type="text" name="lastname" id="lastname" value="{{{ $user->lastname }}}" required>
            @if ( Session::get('lastname_error') )
            <small>{{ Session::get('lastname_error'); }}  </small>
            @endif
        </div>

        <div class="form-group">
            <label for="email">{{{ Lang::get('confide::confide.e_mail') }}}</label>
            <input class="form-control"  type="text" name="email" id="email" value="{{{ $user->email }}}" required>
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
<?php 
$assigned = Assigned::where('user_id', $id)->first(); 
$role =$assigned->role_id;
?>
            <select class="form-control" name="role" >
                <option value="1" <?php if($role==3) echo "selected"; ?>>Admin</option>
                <option value="2" <?php if($role==2) echo "selected"; ?>>Procurement Personel</option>
                <option value="3" <?php if($role==1) echo "selected"; ?>>Requisitioner</option>
            </select>
           
        </div>



       <div class="form-group">
          <select class="form-control" name="office">
                <option value=0 >none</option>
                <?php 
                echo "fghj,mn";
$office= new Office; $office = DB::table('offices')->get();
?>
@foreach ($office as $offices)
<option value= {{ $offices->id }} > {{ $offices->officeName }}</option>
@endforeach
         
            </select>
        </div>

      
        <div class="form-actions form-group">
          <button type="submit" class="btn btn-default" name="submit">Save</button>
        </div>

    </fieldset>
</form>

<?php
Session::forget('firstname_error');
Session::forget('lastname_error');
Session::forget('password_error');
Session::forget('email_error');
Session::forget('msg');
?>

@stop