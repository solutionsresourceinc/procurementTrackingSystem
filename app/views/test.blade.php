@extends('layouts.default')

@section('header')

@stop

@section('content')

	<div class="hero-unit">
        <div id="hero-content">

	    </div>
  </div>
   <!-- <a> tag to call ajax -->
   <!--
	<a href="replace" class="btn ajax" data-method="post" data-append="#hero-content">
		<i class="icon icon-plus"></i> Append Content to HERO unit &raquo;
	</a>
	-->
		
	<!-- <button> tag to open a modal -->
	<!-- <button class="iframe btn" style="background-color:transparent" type="button" data-toggle="modal" data-target="#confirmDelete" onclick="hello(1)"  data-title="Delete User" data-message="Are you sure you want to disable account?">Disable</button> -->

	<!-- <a> tag to call ajax combined with button to call modal -->
	@foreach($users as $key)
	<a href="replace/{{$key->id}}" data-method="post" data-replace="#description_body"  class="btn ajax" data-toggle="modal" data-target="#description"  onclick="empty_div()">{{ $key->username }} </a><br>
	@endforeach
	<!-- Modal Div -->
	<div class="modal fade" id="description" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
			    <center>
			      	<div class="modal-body" id="description_body">
			      		<!-- Insert Data Here -->
			      	</div>
			    </center>
			</div>
		</div>
	</div>
@stop

@section('footer')
{{ HTML::script('js/bootstrap-ajax.js');}}
<script>
	function empty_div()
	{
		document.getElementById("description_body").innerHTML = "";
	}
	
</script>
@stop

