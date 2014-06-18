@extends('layouts.login')

@section('content')

 {{ View::make(Config::get('confide::signup_form'));
 }}  
@stop