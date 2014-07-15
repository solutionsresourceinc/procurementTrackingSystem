@extends('layouts.dashboard')

@section('content')

    <?php $user = User::find($id); ?>
    <h1 class="page-header">Edit Profile</h1> 

    <form method="POST" url="editprof"  class = "form-create">
        <fieldset>
            @if ( Session::get('msg') )
                <div class="alert alert-error alert-danger">
                    {{ Session::get('msg'); }} 
                </div>
            @endif

            <input type="hidden" name="id" value="{{{ $id }}}">
         
            <div class="form-group">
                <label for="username">{{{ Lang::get('confide::confide.username') }}}</label>
                <input class="form-control"  type="text" name="username" id="username" value="{{ $user->username }}" disabled>
            </div>

            <div class="form-group">
                <label for="firstname">First Name</label>
                <input class="form-control"  type="test" name="firstname" id="firstname" value="<?php if(NULL!=Input::old('firstname')){echo Input::old('firstname');} else{echo $user->firstname;}?>" disabled>
                @if ( Session::get('firstname_error') )
                    <small><font color="red">{{ Session::get('firstname_error'); }} </font></small>
                @endif
            </div>

            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input class="form-control" type="text" name="lastname" id="lastname" value=
                "<?php if(NULL!=Input::old('lastname')){echo Input::old('lastname');} else{echo $user->lastname;}?>" disabled>

                @if ( Session::get('lastname_error') )
                    <small><font color="red">{{ Session::get('lastname_error'); }} </font> </small>
                @endif
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input class="form-control"  type="text" name="email" id="email" value="<?php if(NULL!=Input::old('email')){echo Input::old('email');} else{echo $user->email;}?>">
                
                @if ( Session::get('email_error') )
                    <small><font color="red">{{ Session::get('email_error'); }}   </font> </small>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Password </label>
                <input class="form-control" type="password" name="password" id="password"  >
                
                @if ( Session::get('password_error') )
                    <small><font color="red">{{ Session::get('password_error'); }}  </font> </small>
                @endif
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password </label>
                <input class="form-control"  type="password" name="password_confirmation" id="password_confirmation"  >
            </div>

            <div class="form-group">
                <label for="role">Role *</label>
                <?php 
                    $assigned = Assigned::where('user_id', $id)->first();

                    if(NULL!=Input::old('role'))
                        $role=Input::old('role');
                    else
                        $role =$assigned->role_id;
                ?>

                <select class="form-control" name="role" id="role" disabled>
                    <option value="3" <?php if($role==3) echo "selected"; ?>>Admin</option>
                    <option value="2" <?php if($role==2) echo "selected"; ?>>Procurement Personel</option>
                    <option value="1" <?php if($role==1) echo "selected"; ?>>Requisitioner</option>
                </select>
            </div>

            <div class="form-group">
                <label for="role">Office </label>
                <?php 
                    if(NULL!=Input::old('office'))
                        $Editoffice_id=Input::old('office');
                    else
                        $Editoffice_id=$user->office_id;
                ?>
                <select class="form-control" name="office" disabled>
                    <option value=0 <?php if($Editoffice_id==0){echo "selected";}?>>none</option>
                    <?php 
                        $offices = new Office; $offices = DB::table('offices')->get();
                    ?>
                    @foreach ($offices as $office)
                        <option value="{{{ $office->id }}}" <?php if($office->id==$Editoffice_id){echo "selected";} ?>>{{ $office->officeName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-actions form-group">
                <button type="submit" class="btn btn-success" name="submit">Save</button>
                {{ link_to( '/user/view', 'Cancel', array('class'=>'btn btn-default') ) }}
            </div>
        </fieldset>
    </form>

    <?php
        // FOR ERRORS
        Session::forget('firstname_error');
        Session::forget('lastname_error');
        Session::forget('password_error');
        Session::forget('email_error');
        Session::forget('msg');
    ?>

@stop