<@extends('layouts.default')

@section('content')
	<h2>Create New Purchase Request</h2>
	<hr />
	{{ Form::open(['route'=>'purchaserRequest_submit'],'POST') }}
		<div class="row">
			<div class="col-md-9">	
				<div class="form-group">

					<div class="input-group">
					  	<span class="input-group-addon">Project/Purpose *</span>
					  	{{ Form::text('project_purpose','',array('class'=>'form-control','required','oninput'=>'check2(this)')) }}
					</div><br>					

					<div class="input-group">
					  	<span class="input-group-addon">Source of Fund *</span>
					  	{{ Form::text('source','',array('class'=>'form-control','required','oninput'=>'check2(this)')) }}
					</div><br>

					<div class="input-group">
					  	<span class="input-group-addon">Amount *</span>
					  	{{ Form::text('amount','',array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)','id'=>'num','required','oninput'=>'check(this)')) }}
						
					</div><br>

					<div class="input-group">
					  	<span class="input-group-addon">Office *</span>
					  	<select required class="form-control">
							<option value="">Please select</option>
							@foreach($office as $key)
								<option value="{{ $key->officeName }} ">{{ $key->officeName }}</option>
							@endforeach
						</select>
					</div><br>

					<div class="input-group">
					  	<span class="input-group-addon">Requisitioner *</span>
					  	<select required class="form-control">
							<option value="">Please select</option>
								<option value="Requisitioner 1">Requisitioner 1</option>
								<option value="Requisitioner 2">Requisitioner 2</option>
								<option value="Requisitioner 3">Requisitioner 3</option>
						</select>
					</div><br>

					<div class="input-group">
					  	<span class="input-group-addon">Control No. *</span>
					  	<input pattern=".{6,}" oninput="check(this)" required id="control" class="form-control" oninput="check(this)" oninvalid="this.setCustomValidity('Control No. is minumum of 6 digits')">
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
@stop