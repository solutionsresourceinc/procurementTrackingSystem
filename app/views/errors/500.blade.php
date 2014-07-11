<!-- This page is for Not Page Found error by: Edu Iglesias -->

@extends('layouts.login')

@section('content')
    <style type="text/css">
        body {
            background-color: #333333;
        }
    </style>
	<!-- Creates the form -->
    {{ Form::open(array('class' => 'form-signin', 'role' => 'form')) }}
    	<!-- Adds the logo -->
    	{{ HTML::image('img/logo2.png', 'Tarlac Procurement Tracking System', array('id' => 'logo')) }}

    	<center><img src="img/error.png"></img>
    		<h3>No Connection To Database!</h3>
        </center>

		<br/>

	{{ Form::close() }}
@stop
