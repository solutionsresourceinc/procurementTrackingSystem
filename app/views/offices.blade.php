@extends('layouts.dashboard')

@section('content')
	
    <h1 class="page-header">Offices</h1>

    <div id="message">
		@if(Session::get('success'))
			<div class="alert alert-success"> {{ Session::get('success') }}</div> 
		@endif

		@if(Session::get('invalid'))
			<div class="alert alert-danger"> {{ Session::get('invalid') }}</div> 
		@endif
	</div>

	<div id="other_message"></div> 

	@if(Session::get('duplicate-error'))
		<div class="alert alert-danger" id="duplicate"> {{ Session::get('duplicate-error') }}</div> 
	@endif

    <div id="office-create-form" class="well div-form">
    	{{ Form::open(['route'=>'offices.store'], 'POST', array('role' => 'form')) }}
	    	<div class="col-md-8">
		    	{{ Form::text('officeName', null, array('class' => 'form-control', 'placeholder' => 'Office Name','maxlength'=>'100')) }}
		    </div>
		    <div class="col-md-3">
		    	{{ Form::submit('Add', array('class' => 'btn btn-success btn-block create-btn')) }}
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
			    			Offices
			    		@else
			    			Office
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
			    				<div id="display_{{$office->id}}">
				    				<span  class="current-text mode1" id="insert_{{$office->id}}">
				    					{{  $office->officeName }}
				    				</span>
			    				</div>
			    				<form class="form ajax" action="offices/{{$office->id}}/edit" method="post" role="form" class="form-inline">
									<input type = "text" name = "ofcname" class = "edit-text form-control mode2" maxlength="100" />
								{{ Form::close() }}
			    			</td>
			    			<td class="col-md-4">

							{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Edit', 'data-toggle' => 'tooltip']))}}
							<form method="POST" action="offices/delete/{{{$office->id}}}" id="myForm_{{ $office->id }}" name="myForm" style="display: -webkit-inline-box;">
								<input type="hidden" name="hide" value="{{ $office->id }}">
								<center>
									<button class="btn btn-danger table-actions mode1" type="button" data-toggle="modal" data-target="#confirmDelete" data-target='#invalid' onclick="hello( {{ $office->id }})"  data-title="Delete" data-message="Are you sure you want to disable account?"><span class="glyphicon glyphicon-trash"></span></button>
								</center>
							</form>
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
	<div class="center-pagination">
		{{ $offices->links(); }}
	</div>

	<!-- CODES FOR MODAL -->
	<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title">Delete Office</h4>
	      </div>
	      <div class="modal-body">
	        <p>Are you sure you want to delete this office?</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	        <button type="button" class="btn btn-danger" id="confirm">Delete</button>
	      </div>
	    </div>
	  </div>
	</div>
@stop

@section('footer')
	{{ HTML::script('js/bootstrap-ajax.js');}}
	<script type = "text/javascript">
		$(document).ready(function() {
			$(".mode2").hide();
			$(".allow-edit").on("click", function() {
				// get closest span
				var current = $(this).closest("tr").find(".current-text");
				// get closest textfield
				var textfield = $(this).closest("tr").find(".edit-text");
				// Trim the data in span
				var text = current.text().trim();
				// hide the span
				current.hide();
				//var txt = document.getElementById('')
				
				textfield.val(text);
				//textfield.attr({"placeholder": text, "value": text}).show().focus();
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

		  $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
		     //$(this).data('form').submit();
		      var name = "myForm_" + window.my_id; 
		      document.getElementById(name).submit();
		     //alert(name);
		  });
		  function hello(pass_id)
		  {
		      window.my_id = pass_id;
		     // alert(window.my_id);
		  }
	</script>
@stop