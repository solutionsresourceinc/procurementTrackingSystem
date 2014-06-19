@extends('layouts.default')

@section('content')
    <h1 class="page-header">Offices</h1>

    <div id="office-create-form" class="well div-form">
	    {{ Form::open(['route'=>'offices.store'], 'POST', array('role' => 'form')) }}
	    	<div class="col-md-3">
	    		{{ Form::label('officename', 'Create New  Requisitioner Office:', array('class' => 'create-label')) }}
	    	</div>
	    	<div class="col-md-6">
		    	{{ Form::text('officeName', null, array('class' => 'form-control', 'placeholder' => 'Office Name')) }}
		    	{{ $errors->first('officename', '<span style="color: red">:message</span>') }}
		    </div>
		    <div class="col-md-3">
		    	{{ Form::submit('Create', array('class' => 'btn btn-success btn-block')) }}
		    </div>
	    {{ Form::close() }}
	</div>

    <div class="table-responsive">
        <table class="table table-striped">
	    	<thead>
				<tr>
			    	<th>Requisitioning Office</th>
			    	<th>Actions</th>
			    </tr>
	    	</thead>
	    	<tbody>
	    		@if($offices->count())
	    			@foreach($offices as $office)
			    		<tr>
			    			<td>{{  $office->officeName }}</td>
			    			<td>
			    				<button type="button" class="btn btn-success" title="Edit Office">
			    					<span class="glyphicon glyphicon-edit"></span>
			    				</button>
			    				<a href="{{ URL::to('offices/delete/'.$office->id) }}" class="btn btn-danger" title="Delete Office" onclick="return confirm('Are you sure you want to delete this?')">
			    					<span class="glyphicon glyphicon-trash"></span>
			    				</a>
			    			</td>
			    		</tr>
			    	@endforeach
			    @else
					<tr><td colspan="2"><span class="error-view">There are no offices.</span></td></tr>
				@endif
	    	</tbody>
	    </table>
	</div>
@stop