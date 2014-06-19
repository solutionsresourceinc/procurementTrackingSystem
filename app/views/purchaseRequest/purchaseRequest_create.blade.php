@extends('layouts.default')

@section('content')
	<h2>Create New Purchase Request</h2>
	<hr />
	{{ Form::open(['route'=>'purchaseRequest_submit'],'POST') }}
		<div class="row">
			<div class="col-md-9">	

				@if(Session::get('notice'))
					<div class="alert alert-success"> {{ Session::get('notice') }}</div> 
				@endif

				<div class="form-group">

					<div class="input-group">
					  	<span class="input-group-addon">Project/Purpose *</span>
					  	{{ Form::text('projectPurpose','',array('class'=>'form-control','required','oninput'=>'check2(this)')) }}
					</div><br>					

					<div class="input-group">
					  	<span class="input-group-addon">Source of Fund *</span>
					  	{{ Form::text('sourceOfFund','',array('class'=>'form-control','required','oninput'=>'check2(this)')) }}
					</div><br>

					<div class="input-group">
					  	<span class="input-group-addon">Amount *</span>
					  	{{ Form::text('amount','',array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)','id'=>'num','required','oninput'=>'check(this)')) }}
						
					</div><br>

					<div class="input-group">
					  	<span class="input-group-addon">Office *</span>
					  	<select required class="form-control" name="office">
							<option value="">Please select</option>
							@foreach($office as $key)
								<option value="{{ $key->officeName }} ">{{ $key->officeName }}</option>
							@endforeach
						</select>
					</div><br>

					<div class="input-group" name="requisitioner">
					  	<span class="input-group-addon">Requisitioner *</span>
					  	<select required class="form-control" name="requisitioner">
							<option value="">Please select</option>
								<option value="Requisitioner 1">Requisitioner 1</option>
								<option value="Requisitioner 2">Requisitioner 2</option>
								<option value="Requisitioner 3">Requisitioner 3</option>
						</select>
					</div><br>

					<div class="input-group">
					  	<span class="input-group-addon">Mode of Procurement *</span>
					  	<select required class="form-control" name="modeOfProcurement">
							<option value="">Please select</option>
								<option value="Workflow 1">Workflow 1</option>
								<option value="Workflow 2">Workflow 2</option>
								<option value="Workflow 3">Workflow 3</option>
						</select>
					</div><br>

					<div class="input-group">
					  	<span class="input-group-addon">Control No. *</span>
					  	<input type="text" pattern=".{6,}" oninput="check(this)" name="ControlNo" required  class="form-control" oninput="check(this)" oninvalid="this.setCustomValidity('Control No. is minumum of 6 digits')">
					</div><br>


					


					<div  align="right">
						{{ Form::submit('Create Purchase Requerst',array('class'=>'btn btn-success')) }}
					</div>
				</div>
			</div>	
		</div>

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
					// input is valid -- reset the error message
					 input.setCustomValidity('');
				}
			}

		</script>
    	
    			
	{{ Form::close() }}	

	{{ Session::forget('notice'); }}
@stop