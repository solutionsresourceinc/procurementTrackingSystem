@extends('layouts.dashboard')

@section('header')

	{{ HTML::style('date_picker/bootstrap-datetimepicker.min.css')}}
	{{ HTML::script('date_picker/bootstrap-datetimepicker.js') }}
	{{ HTML::script('date_picker/bootstrap-datetimepicker.fr.js') }}


	{{ HTML::script('js/lightbox.min.js') }} 
	{{ HTML::style('css/lightbox.css')}}


	{{ HTML::script('js/jquery.chained.min.js') }} 
	{{ HTML::script('js/bootstrap.file-input.js') }} 

	<style>
		.nopadding {
		   padding: 0 !important;
		   margin: 0 !important;
		}
	</style> 

@stop



@section('content')

<!-- Modal Div -->
	<div class="modal fade" id="description" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
		    		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		    		<h4 class="modal-title">Description</h4>
		    	</div>
			    	<center>
			    <div class="modal-body" id="description_body">
			      		<!-- Insert Data Here -->
			    </div>
			    	</center>
			    <div class="modal-footer">
        			<button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
    			</div>
			</div>
		</div>
	</div>


<h1 class="page-header">Create New Purchase Request</h1>
<div class="form-create fc-div">
	{{ Form::open(array('url' => 'newcreate','files' => true), 'POST') }}
	<div class="row">
		<div>	

			@if(Session::get('notice'))
			<div class="alert alert-success"> {{ Session::get('notice') }}</div> 
			@endif

			@if(Session::get('main_error'))
			<div class="alert alert-danger"> {{ Session::get('main_error') }}</div> 
			@endif

			<div class="form-group">
				<div class="row">
					<div class="col-md-6">
						{{ Form::label('modeOfProcurement', 'Mode of Procurement *', array('class' => 'create-label')) }}
						<select  disabled name="modeOfProcurement" id="modeOfProcurement" class="form-control" data-live-search="true">
							<option value="">None</option>
							@foreach($workflow as $wf)
							<option value="{{ $wf->id }}" 
								<?php
								if(Input::old('modeOfProcurement')==$wf->id)
									echo "selected";
								?> >{{$wf->workFlowName}}</option>
								@endforeach

						</select>
						<input type="hidden" name="hide_modeOfProcurement" id="hide_modeOfProcurement">

						@if (Session::get('m6'))
							<font color="red"><i>The mode of procurement is required field</i></font>
						@endif
					</div>

					<div class="col-md-2">
						{{ Form::label('otherType', 'Other Type', array('class' => 'create-label')) }}
						<select name="otherType" class="form-control" onchange="change_OtherType(this.value)">
							<option value="">None</option>
							<option value="shopping">Shopping</option>
							<option value="fuel">Fuel</option>
							<option value="pakyaw">Pakyaw</option>
						</select>
						<p> </p>
					</div>

					</select>
					<input type="hidden" name="Procurement" id="hide_modeOfProcurement">

					<div class="col-md-2">
						{{ Form::label('status', 'Status: ', array('class' => 'create-label')) }}
						<input type="text" value="New" readonly class="form-control">
					</div>

					<div class="col-md-2">
						<?php 
						$cn = 0;
						$purchase = Purchase::orderBy('ControlNo', 'ASC')->get();
						foreach ($purchase as $pur) {

							$cn = (int)$pur->controlNo;
						}
						$cn =$cn+1;
						?>

						{{ Form::label('dispCN', 'Control No. *', array('class' => 'create-label')) }}
						<input type="text"  name="dispCN"  class="form-control" value="{{$cn}}"disabled>
						<input type="hidden" name="controlNo" value="<?php echo $cn; ?>">
					</div>
				</div>

				<div>
					{{ Form::label('projectPurpose', 'Project/Purpose *', array('class' => 'create-label')) }}
					{{ Form::text('projectPurpose','', array('class'=>'form-control')) }}
				</div>
				@if (Session::get('m1'))
					<font color="red"><i>{{ Session::get('m1') }}</i></font>
				@endif
				<br>	

				<div>
					{{ Form::label('sourceOfFund', 'Source of Fund *', array('class' => 'create-label')) }}
					{{ Form::text('sourceOfFund','', array('class'=>'form-control')) }}
				</div>

				@if (Session::get('m2'))
					<font color="red"><i>{{ Session::get('m2') }}</i></font>
				@endif
				<br>

				<div>
					{{ Form::label('amount', 'Amount *', array('class' => 'create-label')) }}
					{{ Form::text('amount','',array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)', 'onkeypress' => 'return isNumberKey(event)','id'=>'num','maxlength'=>'12')) }}
				</div>
				@if (Session::get('m3'))
					<font color="red"><i>{{ Session::get('m3') }}</i></font>
				@endif
				<br>		

				<div class="form-group" id="template">
					{{ Form::label('office', 'Office *', array('class' => 'create-label')) }}
					<select id="office" name="office" class="form-control" data-live-search="true">
						<option value="">Please select</option>
						@foreach($office as $key)
							<option value="{{ $key->id }}" 
								<?php if(Input::old('office')==$key->id)
								echo "selected" ?>
								>{{{ $key->officeName }}}
							</option>
						@endforeach
					</select>
					@if (Session::get('m4'))
						<font color="red"><i>{{ Session::get('m4') }}</i></font>
					@endif
				
				</div>

				<div class="form-group" id="template">
					{{ Form::label('requisitioner', 'Requisitioner *', array('class' => 'create-label')) }}
					<select class="form-control" id="requisitioner" name="requisitioner"  data-live-search="true" >
						<option value="">Please select</option>
						@foreach($users as $key2)
							{{{ $fullname = $key2->lastname . ", " . $key2->firstname }}}
							@if($key2->confirmed == 0)
								continue;
							@else
							<option value="{{ $key2->id }}" class="{{$key2->office_id}}"
								<?php if(Input::old('requisitioner')==$key2->id)
								echo "selected" ?>
								>{{ $fullname }}
							</option>
							@endif
						@endforeach
					</select>
					@if (Session::get('m5'))
						<font color="red"><i>{{ Session::get('m5') }}</i></font>
					@endif
				
				</div>
			

				<div class="form-group">
					{{ Form::label('dateTime', 'Date Requested ', array('class' => 'create-label')) }}
					<div class="input-group date form_datetime col-md-12" data-date="{{ date('Y-m-d') }}T{{ date('H:i:s') }}Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
						<input id="disabled_datetime" onchange="fix_format()" class="form-control" size="16" type="text" value="{{{ Input::old('dateRequested') }}}" readonly>
						<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
						<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
					</div>
					<input type="hidden" id="dtp_input1" name="dateRequested" value="{{{ Input::old('dateRequested') }}}" />
					@if (Session::get('m7'))
						<font color="red"><i>{{ Session::get('m7') }}</i></font>
					@endif
					<br>
				</div>

				<!--  
				Image Module
				-->

				<label class="create-label">Related files:</label>
				<div class="panel panel-default fc-div">
					<div class="panel-body" style="padding: 5px 20px;">
						<!--h3>Attachments</h3>
						<br-->
						@if(Session::get('imgsuccess'))
							<div class="alert alert-success"> {{ Session::get('imgsuccess') }}</div> 
						@endif

						@if(Session::get('imgerror'))
							<div class="alert alert-danger"> {{ Session::get('imgerror') }}</div> 
						@endif

						<br/>

						<?php
							$id = 0;
							$purchase = Purchase::orderBy('id', 'ASC')->get(); 
						?>
						@foreach ($purchase as $purchases) 
							<?php	$id = $purchases->id; ?>
						@endforeach

						<?php
							$doc_id = 0;
							$document = Document::orderBy('id', 'ASC')->get();
						?>
						@foreach ($document as $docs) 
							<?php	$doc_id = $docs->id; ?>
						@endforeach
						<?php $doc_id=$doc_id+1; ?>

						{{ Form::open(array('url' => 'addimage', 'files' => true)) }}

						<input name="file[]" type="file"  multiple title="Select images to attach" data-filename-placement="inside"/>
						<input name="doc_id" type="hidden" value="{{ $doc_id }}">

						<br>
						<br>

						{{ Form::close() }}
					</div>
				</div>


<!-- End Image Module-->

				<div><br>
					{{ Form::submit('Create Purchase Request',array('class'=>'btn btn-success')) }}
					{{ link_to( 'purchaseRequest/view', 'Cancel', array('class'=>'btn btn-default') ) }}

				</div>
			</div>
		</div>	
	</div>		
	{{ Form::close() }}	
	<!--Image Display
	<div id="img-section">

		<?php
  			$attachmentc = DB::table('attachments')->where('doc_id', $doc_id)->count();
         	if ($attachmentc!=0)
            echo "<h3>"."Attachments"."</h3>";
			$attachments = DB::table('attachments')->where('doc_id', $doc_id)->get();	
			$srclink="uploads\\";
		?>
		@foreach ($attachments as $attachment) 
			<div class="image-container">
				<a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="roadtrip">
					<img class="img-thumbnail" src="{{asset('uploads/'.$attachment->data)}}" style="width: 100px; height: 100px;" />
				</a>
				{{ Form::open(array('method' => 'post', 'url' => 'delimage')) }}
				<input type="hidden" name="hide" value="{{$attachment->id}}">
				<button class="star-button"><img src="{{asset('img/Delete_Icon.png')}}"></button>
				{{Form::close()}}
			</div>
		@endforeach

	</div>
	End Image Display-->

	{{ Session::forget('notice'); }}
	{{ Session::forget('main_error'); }}
	{{ Session::forget('m1'); }}
	{{ Session::forget('m2'); }}
	{{ Session::forget('m3'); }}
	{{ Session::forget('m4'); }}
	{{ Session::forget('m5'); }}
	{{ Session::forget('m6'); }}
	{{ Session::forget('m7'); }}
	{{ Session::forget('imgsuccess'); }}
	{{ Session::forget('imgerror'); }}
</div>
@stop

<!-- script for the formatting of amount field -->
@section('footer')

	<script type="text/javascript">
	$('input[type=file]').bootstrapFileInput();
    $('.file-inputs').bootstrapFileInput();
	function isNumberKey(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode
		if(charCode == 44 || charCode == 46)
			return true;

		if (charCode > 31 && (charCode < 48 || charCode > 57))
			return false;


		return true;
	}


	function numberWithCommas(amount) 
	{
		amount = amount.replace(',','');	
		var its_a_number = amount.match(/^[0-9,.]+$/i);
		if (its_a_number != null)
		{
			decimal_amount = parseFloat(amount).toFixed(2);
			if(decimal_amount == 0 || decimal_amount == "0.00")
			{
				document.getElementById("num").value = "0.00";
				window.old_amount = 0.00; 
			}
			else
			{
				var parts = decimal_amount.toString().split(".");
				parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				parts =  parts.join(".");
				if(parts == "NaN")
				{
					document.getElementById("num").value = "0.00";
					window.old_amount = 0.00; 
				}
				else
				{
					document.getElementById("num").value = parts;
					window.old_amount = parts;
				}
				 
			}
		}
		else if(!window.old_amount)
		{
			document.getElementById("num").value = "0.00";
			window.old_amount = 0.00; 
		}
		else
		{
			document.getElementById("num").value = window.old_amount;
		}
		

		newamount =	amount; 

		/* 
		if (newamount<50000)
			document.getElementById("modeOfProcurement").selectedIndex = 1;
		else if (newamount>=50000 )
		{
			if (newamount<=500000)
				document.getElementById("modeOfProcurement").selectedIndex = 2;

			else if (newamount>500000)
				document.getElementById("modeOfProcurement").selectedIndex = 3;

		}
		else
			document.getElementById("modeOfProcurement").selectedIndex = 0; 
		*/

		if (newamount >= 0 && newamount < 50000)
			document.getElementById("modeOfProcurement").selectedIndex = 1;
		else if(newamount >= 50000 && newamount < 500000)
			document.getElementById("modeOfProcurement").selectedIndex = 2;
		else if(newamount >= 500000 )
			document.getElementById("modeOfProcurement").selectedIndex = 3;
		else
			document.getElementById("modeOfProcurement").selectedIndex = 0;

		document.getElementById('hide_modeOfProcurement').value = document.getElementById('modeOfProcurement').value;
	}

	$(window).on('load', function () {

		$('.selectpicker').selectpicker({
			'selectedText': 'cat'
		});

	            //$('.selectpicker').selectpicker('hide');
	        });

	$("#requisitioner").chainedTo("#office");
	</script>

	<!-- Script for date and time picker -->
	<script type="text/javascript">
	$('.form_datetime').datetimepicker({
		        //language:  'fr',
		        weekStart: 1,
		        todayBtn:  1,
		        autoclose: 1,
		        todayHighlight: 1,
		        startView: 2,
		        forceParse: 0,
		        showMeridian: 1
		    });
	$('.form_date').datetimepicker({
		language:  'fr',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});
	$('.form_time').datetimepicker({
		language:  'fr',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
	});

	function fix_format()
	{
		document.getElementById('disabled_datetime').value = document.getElementById('dtp_input1').value;
	}

	</script>

	<script>
		function change_OtherType(value)
		{
			//alert(value);
			if(value == "pakyaw")
			{
				window.last_selected = document.getElementById('modeOfProcurement').selectedIndex 
				document.getElementById('modeOfProcurement').selectedIndex = 4;
				document.getElementById('modeOfProcurement').onchange = 4;
				document.getElementById("num").onchange = new Function("numberWithCommas2(this.value)");
				document.getElementById('hide_modeOfProcurement').value = document.getElementById('modeOfProcurement').value;
				
			}
			else
			{
				var amount = document.getElementById('num').value;
				amount = amount.split(',').join('');
				//amount = amount.split('.').join('');
				//String result = amount.split(".")[0];
				//alert(result);
				//document.getElementById('modeOfProcurement').selectedIndex = window.last_selected;

				document.getElementById("num").onchange = new Function("numberWithCommas(this.value)");


				if (amount >= 0 && amount < 50000)
					document.getElementById("modeOfProcurement").selectedIndex = 1;
				else if(amount >= 50000 && amount < 500000)
					document.getElementById("modeOfProcurement").selectedIndex = 2;
				else if(amount >= 500000 )
					document.getElementById("modeOfProcurement").selectedIndex = 3;
				else
					document.getElementById("modeOfProcurement").selectedIndex = 0;
					document.getElementById('hide_modeOfProcurement').value = document.getElementById('modeOfProcurement').value;

			}


		}
	</script>

	<script>
		function numberWithCommas2(amount) 
		{
			amount = amount.replace(',','');	
			var its_a_number = amount.match(/^[0-9,.]+$/i);
			if (its_a_number != null)
			{
				decimal_amount = parseFloat(amount).toFixed(2);
				if(decimal_amount == 0 || decimal_amount == "0.00")
				{
					document.getElementById("num").value = "0.00";
					window.old_amount = 0.00; 
				}
				else
				{
					var parts = decimal_amount.toString().split(".");
					parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					parts =  parts.join(".");
					if(parts == "NaN")
					{
						document.getElementById("num").value = "0.00";
						window.old_amount = 0.00; 
					}
					else
					{
						document.getElementById("num").value = parts;
						window.old_amount = parts;
					}
					 
				}
			}
			else if(!window.old_amount)
			{
				document.getElementById("num").value = "0.00";
				window.old_amount = 0.00; 
			}
			else
			{
				document.getElementById("num").value = window.old_amount;
			}
			document.getElementById('hide_modeOfProcurement').value = document.getElementById('modeOfProcurement').value;
			
		}
	</script>

	<!-- js for chained dropdown -->
	

@stop