@extends('layouts.dashboard')

@section('content')
	<!-- Creates the form -->
	<center>
    <h1>Workflows</h1>
    <div>
        <a href="{{ URL::to('workflow/belowFifty') }}" class="btn btn-success">Below P50,000</a>
        <a href="{{ URL::to('workflow/aboveFifty') }}" class="btn btn-success">Between P50,000 and P500,000</a>
        <a href="{{ URL::to('workflow/aboveFive') }}" class="btn btn-success">Above P500,000</a>
        <br><br><br>
	</div>
	</center>
@stop