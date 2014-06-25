@extends('layouts.default')

@section('content')
    <h1 class="page-header">Designations</h1>

	@if(Session::get('success'))
		<div class="alert alert-success"> {{ Session::get('success') }}</div> 
	@endif

	@if(Session::get('success_members'))
		<div class="alert alert-success"> {{ Session::get('success_members') }}</div> 
	@endif

	@if(Session::get('invalid'))
		<div class="alert alert-danger"> {{ Session::get('invalid') }}</div> 
	@endif

	@if(Session::get('duplicate-error'))
		<div class="alert alert-danger"> {{ Session::get('duplicate-error') }}</div> 
	@endif

	<div id="designation-create-form" class="well div-form">
	    {{ Form::open(['route'=>'designation.store'], 'POST', array('role' => 'form')) }}
	    	<div class="col-md-3">
	    		{{ Form::label('designationname', 'Create New Designation:', array('class' => 'create-label')) }}
	    	</div>
	    	<div class="col-md-6">
		    	{{ Form::text('designationName', null, array('class' => 'form-control', 'placeholder' => 'Designation Name')) }}
		    	@if(Session::get('main_error'))
						<span class="error-message">Invalid input for designation name.</span>
				@endif
		    </div>
		    <div class="col-md-3">
		    	{{ Form::submit('Create', array('class' => 'btn btn-success btn-block')) }}
		    </div>
	    {{ Form::close() }}
	</div>

	{{ $errors->first('ofcname', '<div class="alert alert-danger error-div"><span>Invalid input for designation name.</span></div>') }}

	<div class="table-responsive">
        <table class="table table-striped">
	    	<thead>
				<tr>
			    	<th>
			    		@if($designations->count()>1)
			    			Designations
			    		@else
			    			Designation
			    		@endif
			    	</th>
			    	<th>Actions</th>
			    </tr>
	    	</thead>
	    	<tbody>
	    		@if($designations->count())
	    			@foreach($designations as $designation)
			    		<tr>
			    			<td class="col-md-8">
			    				<span class="current-text mode1">
			    					{{  $designation->designation }}
			    				</span>
			    				{{ Form::open(['url' => "designation/$designation->id/edit", 'class' => 'form-inline', 'role' => 'form']) }}
									<input type = "text" name = "dsgntn-name" class = "edit-text form-control mode2"/>
								{{ Form::close() }}
			    			</td>
			    			<td class="col-md-4">

							{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Edit Designation', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip']))}}
							{{HTML::decode (link_to("designation/delete/$designation->id", '<span class="glyphicon glyphicon-trash"></span>', ['class'=>'btn btn-danger table-actions mode1', 'onclick' => "return confirm('Are you sure you want to delete this?');",'title'=>'Delete Designation']))}}
							{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2'])}}
							{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
			    			<a href="{{ URL::to('designation/'. $designation->id . '/members') }}" class="btn btn-primary"><span class="glyphicon glyphicon-user"></span></a>
			    			</td>
			    		</tr>
			    	@endforeach
			    @else
					<tr><td colspan="2"><span class="error-view">No data available.</span></td></tr>
				@endif
	    	</tbody>
	    </table>
	</div>
@stop

@section('footer')
	<script type = "text/javascript">
		$(document).ready(function() {
			$(".mode2").hide();
			$(".allow-edit").on("click", function() {
				var current = $(this).closest("tr").find(".current-text");
				var textfield = $(this).closest("tr").find(".edit-text");
				var text = current.text().trim();
				current.hide();
				textfield.val(text);
				textfield.attr({"placeholder": text, "value": text}).show().focus();
				$(this).closest("tr").find(".mode1").hide();
				$(this).closest("tr").find(".mode2").show();
			});

			$(".save-edit").on("click", function(event) {
				var current = $(this).closest("tr").find(".current-text");
				var textfield = $(this).closest("tr").find(".edit-text");
				var text = textfield.val();
				textfield.hide();
				current.text(text);
				current.show();
				textfield.parent().submit();
				$(this).closest("tr").find(".mode1").show();
				$(this).closest("tr").find(".mode2").hide();
			});
			
			$(".cancel-edit").on("click", function() {
				$(this).closest("tr").find(".mode2").hide();
				$(this).closest("tr").find(".mode1").show();
			});
		});
	</script>
	{{ Session::forget('main_error'); }}
	{{ Session::forget('success_members'); }}
@stop