@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">Create User</h1>    

    <form method="POST" action="{{{ (Confide::checkAction('UserController@store')) ?: URL::to('user')  }}}" accept-charset="UTF-8" class="form-create">
        <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
        <fieldset>
     
            @if ( Session::get('msg') )
                <div class="alert alert-error alert-danger">
                    {{ Session::get('msg'); }} 
                </div>
            @endif

            <div class="form-group">
                <label for="username">Username *</label>
                <input class="form-control"  type="text" name="username" id="username" value="{{{ Input::old('username') }}}" >

                @if ( Session::get('username_error') )
                         <small><font color="red"> {{ Session::get('username_error'); }}   </font></small>
                @endif
            </div>

            <div class="form-group">
                <label for="firstname">First Name *</label>
                <input class="form-control"  type="test" name="firstname" id="firstname" value="{{{ Input::old('firstname') }}}" >
                
                @if ( Session::get('firstname_error') )
                    <small><font color="red"> {{ Session::get('firstname_error'); }}   </font></small>
                @endif
            </div>

            <div class="form-group">
                <label for="lastname">Last Name *</label>
                <input class="form-control" type="text" name="lastname" id="lastname" value="{{{ Input::old('lastname') }}}" >
                
                @if ( Session::get('lastname_error') )
                    <small><font color="red">{{ Session::get('lastname_error'); }}</font></small>
                @endif
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input class="form-control"  type="text" name="email" id="email" value="{{{ Input::old('email') }}}" >
                @if ( Session::get('email_error') )
                    <small><font color="red">{{ Session::get('email_error'); }}   </font> </small>
                 @endif
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input class="form-control" type="password" name="password" id="password" >
                @if ( Session::get('password_error') )
                    <small><font color="red">{{ Session::get('password_error'); }}   </font></small>
                @endif
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password *</label>
                <input class="form-control"  type="password" name="password_confirmation" id="password_confirmation" >
            </div>

            <div class="form-group">
                <label for="role">Role *</label>
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
                    <option value=0 
                    <?php if(Input::old('office')==0)  echo "selected"; ?>
                    >none</option>
                    <?php 
                        $office= new Office; $office = DB::table('offices')->get();
                    ?>
                    @foreach ($office as $offices)
                        <option value= {{ $offices->id }} 
                            <?php
                            if(Input::old('office')==$offices->id)
                                echo "selected";
                            ?>
                            > {{ $offices->officeName }}</option>
                    @endforeach
                </select>
            </div>

            <br/>
            <div class="form-actions form-group">
                <button type="submit" class="btn btn-success" name="submit">Create</button>
                {{ link_to( '/user/view', 'Cancel', array('class'=>'btn btn-default') ) }}
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