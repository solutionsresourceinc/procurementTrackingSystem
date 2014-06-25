@extends('layouts.default')

@section('header')
	<script type="text/javascript">
	  $(document).ready(function() {
	 
	    $('#btn-add').click(function(){
	        $('#select-from option:selected').each( function() {
	                $('#select-to').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
	            $(this).remove();
	        });
	    });
	    $('#btn-remove').click(function(){
	        $('#select-to option:selected').each( function() {
	            $('#select-from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
	            $(this).remove();
	        });
	    });
	 
	});
	</script>

	{{ HTML::script('drop_search/bootstrap-select.js')}}
	{{ HTML::style('drop_search/bootstrap-select.css')}}
@stop

@section('content')
    <h1 class="page-header">Designation Members</h1>

    {{ Form::open(['route'=>'designation_members_save'], 'POST') }}
    <div class="table-responsive" align="right">
      	{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
      	{{ Form::submit('Save',array('class'=>'btn btn-success', 'onclick'=>'change()')) }}
      	<br><br>
	</div>

	<div id="designation-create-form" class="well div-form">
	    	<div class="col-md-4">
	    		<strong>Available to Add</strong>
	    		<select name="selectfrom" id="select-from" multiple size="15" class="form-control" data-live-search="true">
		         	@foreach($notselected_users as $key)
			        	{{{ $fullname = $key->lastname . ", " . $key->firstname }}}
						<option value="{{ $key->id }}" >{{ $fullname }}</option>
					@endforeach
		        </select>
	    	</div>
	    	<div class="col-md-4" align="center">
	    		<br><br>
				<a href="JavaScript:void(0);" id="btn-add" class="btn btn-success">&nbsp;&nbsp;&nbsp;Add <span class="glyphicon glyphicon-chevron-right"></span></a>
		        <a href="JavaScript:void(0);" id="btn-remove" class="btn btn-danger"><span class="glyphicon glyphicon-chevron-left"></span> Remove&nbsp;&nbsp;</a>
		        <br><br>
		    </div>
		    <div class="col-md-4">
		    	<strong>Currently Selected</strong>
		    	<select name="selectto" id="select-to" multiple size="15" class="form-control" >
		          	@foreach($selected_users as $key2)
			        	{{{ $fullname2 = $key2->lastname . ", " . $key2->firstname }}}
						<option value="{{ $key2->user_id }}" >{{ $fullname2  }}</option>
					@endforeach
		        </select>
		        {{ Form::hidden('designation_id', "$designation_id"); }}
		        {{ Form::hidden('members_selected', "", ['id'=>'members_selected']); }}
		    </div>
	    {{ Form::close() }}
	</div>

	{{ $errors->first('ofcname', '<div class="alert alert-danger error-div"><span>Invalid input for designation name.</span></div>') }}

	
@stop

@section('footer')
	<script type="text/javascript">
        $(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            //$('.selectpicker').selectpicker('hide');
        });
    </script>

    <script type="text/javascript">
    	function change()
    	{
    		var select1 = document.getElementById('select-to');
			var values = new Array();

			for(var i=0; i < select1.options.length; i++)
			{
			    values.push(select1.options[i].value);
			}

			//alert(values);
			document.getElementById('members_selected').value = values;

    	}
    </script>
@stop