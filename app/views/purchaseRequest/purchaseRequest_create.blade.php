@extends('layouts.default')

@section('header')
	{{ HTML::script('drop_search/bootstrap-select.js')}}
	{{ HTML::style('drop_search/bootstrap-select.css')}}
	
    {{ HTML::script('js/jquery.chained.min.js') }} 


	
	</script>

@stop

@section('content')
	<h1 class="page-header">Create New Purchase Request</h1>
	
	<div class="form-create fc-div">
		{{ Form::open(['route'=>'purchaseRequest_submit'], 'POST') }}
			<div class="row">
				<div>	

					@if(Session::get('notice'))
						<div class="alert alert-success"> {{ Session::get('notice') }}</div> 
					@endif

					@if(Session::get('main_error'))
						<div class="alert alert-danger"> {{ Session::get('main_error') }}</div> 
					@endif

					<div class="form-group">

						<div>
						  	{{ Form::label('projectPurpose', 'Project/Purpose *', array('class' => 'create-label')) }}
						  	{{ Form::text('projectPurpose','',array('class'=>'form-control','required','oninput'=>'check2(this)')) }}
						</div>

						@if (Session::get('m1'))
							<font color="red"><i>{{ Session::get('m1') }}</i></font>
						@endif
						<br>			

						<div>
						  	{{ Form::label('sourceOfFund', 'Source of Fund *', array('class' => 'create-label')) }}
						  	{{ Form::text('sourceOfFund','',array('class'=>'form-control','required','oninput'=>'check2(this)')) }}
						</div>

						@if (Session::get('m2'))
							<font color="red"><i>{{ Session::get('m2') }}</i></font>
						@endif
						<br>

						<div>
						  	{{ Form::label('amount', 'Amount *', array('class' => 'create-label')) }}
						  	{{ Form::text('amount','',array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)','id'=>'num','required','oninput'=>'check(this)')) }}
						</div>

						@if (Session::get('m3'))
							<font color="red"><i>{{ Session::get('m3') }}</i></font>
						@endif
						<br>

						<div class="form-group" id="template">
							{{ Form::label('office', 'Office *', array('class' => 'create-label')) }}
		                	<select id="office" name="office" class="form-control selectpicker" data-live-search="true">
		                		<option value="">Please select</option>
								@foreach($office as $key)
									<option value="{{ $key->id }}">{{{ $key->officeName }}}</option>
								@endforeach
		            		</select>
			            		@if (Session::get('m4'))
									<font color="red"><i>{{ Session::get('m4') }}</i></font>
								@endif
								<br>
		            	</div>

						

			

						<div class="form-group" id="template">
		                	{{ Form::label('requisitioner', 'Requisitioner *', array('class' => 'create-label')) }}
			                <select class="form-control" id="requisitioner" name="requisitioner"   >
			                	<option value="">Please select</option>
			                	@foreach($users as $key2)
			                		{{{ $fullname = $key2->lastname . ", " . $key2->firstname }}}
									<option value="{{ $key2->id }}" class="{{$key2->office_id}}">{{ $fullname }}</option>
								@endforeach
			                	
			                </select>
			                @if (Session::get('m5'))
								<font color="red"><i>{{ Session::get('m5') }}</i></font>
							@endif
							<br>
		            	</div>

						<div>
						  	{{ Form::label('modeOfProcurement', 'Mode of Procurement *', array('class' => 'create-label')) }}
						  	<select  name="modeOfProcurement" id="modeOfProcurement" class="form-control selectpicker" data-live-search="true">
								<option value="">Please select</option>
									<option value="Workflow 1">Workflow 1</option>
									<option value="Workflow 2">Workflow 2</option>
									<option value="Workflow 3">Workflow 3</option>
							</select>
							@if (Session::get('m6'))
								<font color="red"><i>{{ Session::get('m6') }}</i></font>
							@endif
							<br>
						</div>

						<div>
						  	{{ Form::label('ControlNo', 'Control No. *', array('class' => 'create-label')) }}
						  	<input type="text"  onchange="check3(this)"  name="ControlNo" required  class="form-control">
						</div>

						@if (Session::get('m7'))
							<font color="red"><i>{{ Session::get('m7') }}</i></font>
						@endif
						<br>




			<div><br>
							{{ Form::submit('Create Purchase Request',array('class'=>'btn btn-success')) }}
							{{ link_to( '/', 'Cancel Create', array('class'=>'btn btn-default') ) }}
						</div>
					</div>
				</div>	
			</div>		
		{{ Form::close() }}	



<div>
<?php
$id = 0;
	$purchase = Purchase::orderBy('id', 'ASC')->get();
?>
@foreach ($purchase as $purchases) 
<?php	$id = $purchases->id; 

?>
@endforeach


<a href="/attach/{{$id}}">
<button class="btn btn-default">
Attach Image
</button></a>
</div>
	
	</div>




	{{ Session::forget('notice'); }}
	{{ Session::forget('main_error'); }}
	{{ Session::forget('m1'); }}
	{{ Session::forget('m2'); }}
	{{ Session::forget('m3'); }}
	{{ Session::forget('m4'); }}
	{{ Session::forget('m5'); }}
	{{ Session::forget('m6'); }}
	{{ Session::forget('m7'); }}
@stop

<!-- script for the formatting of amount field -->
@section('footer')
	<script type="text/javascript">
		function numberWithCommas(x) 
		{
			x = parseFloat(x).toFixed(2);
			var parts = x.toString().split(".");
			parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			parts =  parts.join(".");
			document.getElementById("num").value = parts;
		}

		function check(input) 
		{

			if(!input.value.match(/^\d+/)) {
				input.setCustomValidity('Invalid Input.');
			} 
			else {
				// input is valid -- reset the error message
				 input.setCustomValidity('');
			}
		}

		function check2(input) 
		{

			if(!input.value.match(/^[a-z0-9ñÑ ]+$/i)) {
				input.setCustomValidity('letters, numbers and spaces only');
			} 
			else {
				 input.setCustomValidity('');
			}
		}

		function check3(input) 
		{
			var num = input.value;
			var len = num.length;

			if(!input.value.match(/^\d+$/)) {
				input.setCustomValidity('Invalid Input');
			}

			else if(len < 6)
			{
				input.setCustomValidity('Control No. should contain 6 digits');
			}

			else 
			{
				 input.setCustomValidity('');
			}			

	
		}

	</script>

	<script type="text/javascript">
        $("#requisitioner").chainedTo("#office");
    </script>

    <script type="text/javascript">
        $(window).on('load', function () {

            $('.selectpicker').selectpicker({
                'selectedText': 'cat'
            });

            //$('.selectpicker').selectpicker('hide');
        });
    </script>
@stop