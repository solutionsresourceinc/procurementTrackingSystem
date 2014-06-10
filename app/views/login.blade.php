@extends('layouts.login')

@section('content')
	<!--form class="form-signin" role="form">
        <h2 class="form-signin-heading">Please sign in</h2>
    </form-->
    {{ Form::open(array('class' => 'form-signin', 'role' => 'form')) }}
    	<h2 class="form-signin-heading">Please log in</h2>
		{{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username')) }}
		{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password')) }}
		{{ Form::submit('Log in', array('class' => 'btn btn-lg btn-primary btn-block')) }}
	{{ Form::close() }}
@stop