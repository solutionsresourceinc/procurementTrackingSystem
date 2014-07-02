@extends('layouts.dashboard')

@section('header')
	<script type="text/javascript">
	  $(document).ready(function() {
	 
	    $('#btn-add').click(function(){
	        $('#select-from option:selected').each( function() {
	                $('#select-to').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
	            $(this).remove();
	        });

	        check_empty();

	    });
	    $('#btn-remove').click(function(){
	        $('#select-to option:selected').each( function() {
	            $('#select-from').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
	            $(this).remove();
	        });
	        check_empty();

	    });
	 
	});

	  function check_empty()
	  {
	  	if ($('#select-from option').length <= 0) 
    	{
    		document.getElementById("btn-add").disabled=true;
		}
		else
		{
			document.getElementById("btn-add").disabled=false;
		}

		if ($('#select-to option').length <= 0) 
    	{
    		document.getElementById("btn-remove").disabled=true;
		}
		else
		{
			document.getElementById("btn-remove").disabled=false;
		}
	  }


	</script>

	{{ HTML::script('drop_search/bootstrap-select.js')}}
	{{ HTML::style('drop_search/bootstrap-select.css')}}
@stop

@section('content')
    <h1 class="page-header">Designation Members</h1>
	 <div class="alert alert-info">  Select an Item to Add/Remove </div>

    {{ Form::open(['route'=>'designation_members_save'], 'POST') }}
	<div id="designation-create-form" class="well div-form">
	    	<div class="col-md-4">
	    		<strong>Available to Add </strong>
	    		<select name="selectfrom" id="select-from" multiple size="15" class="form-control" data-live-search="true">
		         	{{ $count = 1 }}
		         	@foreach($notselected_users as $key)
		         		@if($count == 1)
		         		{
		         			{{{ $fullname = $key->lastname . ", " . $key->firstname }}}
							<option selected value="{{ $key->id }}" >{{ $fullname }}</option>
		         			{{ $count++; }}
		         		}
		         		@else
		         		{
		         			{{{ $fullname = $key->lastname . ", " . $key->firstname }}}
							<option value="{{ $key->id }}" >{{ $fullname }}</option>
		         		}
		         		@endif
							
					@endforeach
		        </select>
	    	</div>
	    	<div class="col-md-4" align="center">
	    		<br><br>
	    		<button type="button" class="btn btn-success" id="btn-add" onclick="select()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;&nbsp; <span class="glyphicon glyphicon-chevron-right"></span></button><br><br>
	    		<button type="button" class="btn btn-danger" id="btn-remove"><span class="glyphicon glyphicon-chevron-left"></span> Remove&nbsp;&nbsp;</button>
		        <br><br>
		    </div>
		    <div class="col-md-4">
		    	<strong>Currently Selected</strong>
		    	<select name="selectto" onchange"select()" id="select-to" multiple size="15" class="form-control" >
		          	@foreach($selected_users as $key2)
			        	{{{ $fullname2 = $key2->lastname . ", " . $key2->firstname }}}
						<option value="{{ $key2->users_id }}" >{{ $fullname2  }}</option>
					@endforeach
		        </select>
		        {{ Form::hidden('designation_id', "$designation_id"); }}
		        {{ Form::hidden('members_selected', "", ['id'=>'members_selected']); }}
		    </div>
	</div>

	<div class="table-responsive" align="right">
      	<a href="{{ URL::to('designation') }}" class="btn btn-default">Cancel</a>
      	{{ Form::submit('Save',array('class'=>'btn btn-success', 'onclick'=>'change()')) }}
      	<br>
	</div>
{{ Form::close() }}


	
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

		$( document ).ready(function() {
		 	if ($('#select-from option').length <= 0) 
	    	{
	    		document.getElementById("btn-add").disabled=true;
			}
			else
			{
				document.getElementById("btn-add").disabled=false;
			}

			if ($('#select-to option').length <= 0) 
	    	{
	    		document.getElementById("btn-remove").disabled=true;
			}
			else
			{
				document.getElementById("btn-remove").disabled=false;
			}
		    
		 
		});
    </script>
@stop