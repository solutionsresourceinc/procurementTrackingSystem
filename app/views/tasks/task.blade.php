@extends('layouts.dashboard')

@section('content')
	<h1 class="pull-left">Task Details</h1>
	<div class="pull-right options">
		<a href="{{ URL::previous() }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
	</div>

	<hr class="clear" />

	<div class="panel panel-default">
  		<div class="panel-body task-body">
		    <h3 class="pull-left">Task Name</h3>
		    <div class="pull-right" style="margin-top: 10px;">
				{{Form::button('Accept Task', ['class' => 'btn btn-primary', 'style' => 'margin-bottom: 10px'])}}
			</div>
		    <hr class="clear" />
		    <span style="font-weight: bold">Description: </span><br/>
			<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
			<br/>
			<span style="font-weight: bold">Purchase Request ID-Name: </span><br/>
			<p><a href="#">1-Purchase Request Number 1</a></p>
			<br/>
			<span style="font-weight: bold">Due Date: </span><br/>
			<p>July 31, 2014</p>
			<br/>
			<p style="font-weight: bold">Remarks: </p>
			{{ Form::open() }}
			    {{ Form::textarea('remarks','', array('class'=>'form-control', 'rows'=>'5')) }}
			    <div class="pull-right">
				    {{ link_to( 'task/active', 'Cancel', array('class'=>'btn btn-sm btn-default remarks-btn') ) }}
				    {{ Form::submit('Submit',array('class'=>'btn btn-sm btn-success remarks-btn')) }}
				</div>
			{{ Form::close() }}

			<hr class="clear" />

			{{ link_to( '#', 'Done', array('class'=>'btn btn-primary remarks-btn') ) }}
			<!-- For Review Tasks
			{{ link_to( '#', 'Approve', array('class'=>'btn btn-primary remarks-btn') ) }}
			{{ link_to( '#', 'Reject', array('class'=>'btn btn-danger remarks-btn') ) }}
			-->
		</div>
	</div>
@stop