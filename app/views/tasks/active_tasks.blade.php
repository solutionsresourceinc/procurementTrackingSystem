@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">Active Tasks</h1>

    <div class="list-group">
    	<?php
    		for ($x=0; $x<=10; $x++){
    			echo '<a href="#" class="list-group-item">
		        	<span class="list-group-item-heading"><span class="glyphicon glyphicon-unchecked" style="color: rgb(200, 213, 200);"></span> &nbsp;&nbsp;Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span>
		        </a>';
    		}
        ?>
    </div>
@stop    