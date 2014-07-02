@extends('layouts.default')

@section('header')

{{ HTML::style('ios_overlay/css/iosOverlay.css')}}
{{ HTML::script('ios_overlay/js/jquery.min.js') }}
{{ HTML::script('ios_overlay/js/iosOverlay.js') }}
{{ HTML::script('ios_overlay/js/spin.min.js') }}
{{ HTML::script('ios_overlay/js/prettify.js') }}
{{ HTML::script('ios_overlay/js/custom.js') }}

@stop

@section('content')
<br><br><br><br><br>
POGI AKO
<br><br>
<p class="container">
    <button id="checkMark" class="btn">Loading Then Success</button>
</p> 
@stop

@section('footer')

@stop





