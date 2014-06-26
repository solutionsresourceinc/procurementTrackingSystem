@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">Offices</h1>

	@if(Session::get('success'))
		<div class="alert alert-success"> {{ Session::get('success') }}</div> 
	@endif

	@if(Session::get('invalid'))
		<div class="alert alert-danger"> {{ Session::get('invalid') }}</div> 
	@endif

	@if(Session::get('duplicate-error'))
		<div class="alert alert-danger"> {{ Session::get('duplicate-error') }}</div> 
	@endif

    <div id="office-create-form" class="well div-form">
	    {{ Form::open(['route'=>'offices.store'], 'POST', array('role' => 'form')) }}
	    	<div class="col-md-3 create-office">
	    		{{ Form::label('officename', 'Create New  Requisitioner Office:', array('class' => 'create-label')) }}
	    	</div>
	    	<div class="col-md-6">
		    	{{ Form::text('officeName', null, array('class' => 'form-control', 'placeholder' => 'Office Name')) }}
		    	{{ $errors->first('officeName', '<span class="error-message">Invalid input for office name.</span>') }}
		    </div>
		    <div class="col-md-3">
		    	{{ Form::submit('Create', array('class' => 'btn btn-success btn-block')) }}
		    </div>
	    {{ Form::close() }}
	</div>

	{{ $errors->first('ofcname', '<div class="alert alert-danger error-div"><span>Invalid input for office name.</span></div>') }}

    <div class="table-responsive">
        <table class="table table-striped">
	    	<thead>
				<tr>
			    	<th>
			    		@if($offices->count()>1)
			    			Requisitioning Offices
			    		@else
			    			Requisitioning Office
			    		@endif
			    	</th>
			    	<th>Actions</th>
			    </tr>
	    	</thead>
	    	<tbody>
	    		@if($offices->count())
	    			@foreach($offices as $office)
			    		<tr>
			    			<td class="col-md-8">
			    				<span class="current-text mode1">
			    					{{  $office->officeName }}
			    				</span>
			    				{{ Form::open(['url' => "offices/$office->id/edit", 'class' => 'form-inline', 'role' => 'form']) }}
									<input type = "text" name = "ofcname" class = "edit-text form-control mode2"/>
								{{ Form::close() }}
			    			</td>
			    			<td class="col-md-4">

							{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Edit Office', 'data-placement' => 'bottom', 'data-toggle' => 'tooltip']))}}
							{{HTML::decode (link_to("offices/delete/$office->id", '<span class="glyphicon glyphicon-trash"></span>', ['class'=>'btn btn-danger table-actions mode1', 'onclick' => "return confirm('Are you sure you want to delete this?');",'title'=>'Delete Office']))}}
							{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2'])}}
							{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
			    			</td>
			    		</tr>
			    	@endforeach
			    @else
					<tr><td colspan="2"><span class="error-view">No data available.</span></td></tr>
				@endif
	    	</tbody>
	    </table>
	</div>
	<div class="container">
		<div class="center-pagination">
			<?php echo $offices->links(); ?>
		</span>
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
@stop