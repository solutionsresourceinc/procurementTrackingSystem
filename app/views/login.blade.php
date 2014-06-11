@extends('layouts.login')

@section('content')
	<!-- Creates the form -->
    {{ Form::open(array('class' => 'form-signin', 'role' => 'form')) }}
    	<!-- Adds the logo -->
    	{{ HTML::image('img/logo2.png', 'Tarlac Procurement Tracking System', array('id' => 'logo')) }}
		{{ Form::text('username', null, array('class' => 'form-control', 'placeholder' => 'Username')) }}
		{{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password')) }}
		<br/>
		{{ Form::submit('Log in', array('class' => 'btn btn-lg btn-success btn-block')) }}
	{{ Form::close() }}
@stop