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
	<?php 
		date_default_timezone_set("Asia/Manila");
		$date_today = date('Y-m-d H:i:s');
	?>

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
							{{ Form::label('modeOfProcurement', 'Mode of Procurement', array('class' => 'create-label')) }}
							<select  disabled name="modeOfProcurement" id="modeOfProcurement" class="form-control" data-live-search="true">
								<option value="">None</option>
								@foreach($workflow as $wf)
								<option value="{{ $wf->id }}" 
									<?php
									if(Input::old('hide_modeOfProcurement') == $wf->id)
										echo "selected";
									?> >{{$wf->workFlowName}}</option>
									@endforeach
							</select>
							<input type="hidden" name="hide_modeOfProcurement" id="hide_modeOfProcurement" value="{{ Input::old('hide_modeOfProcurement') }} ">

							@if (Session::get('error_modeOfProcurement'))
								<font color="red"><i>The mode of procurement is required field</i></font>
							@endif
						</div>

						<div class="col-md-3">
							{{ Form::label('otherType', 'Other Type', array('class' => 'create-label')) }}
							<select name="otherType" id="otherType" class="form-control" onchange="change_OtherType(this.value)">
								<option value="">None</option>
								<option value="shopping" <?php if(Input::old('otherType') == 'shopping') echo 'selected'; ?> >Shopping</option>
								<option value="fuel" <?php if(Input::old('otherType') == 'fuel') echo 'selected'; ?>>Fuel</option>
								<option value="pakyaw" <?php if(Input::old('otherType') == 'pakyaw') echo 'selected'; ?>>Pakyaw</option>
								<option value="Direct Contracting" <?php if(Input::old('otherType') == 'Direct Contracting') echo 'selected'; ?>>Direct Contracting</option>
							</select>
							<p> </p>
						</div>
						</select>
						<input type="hidden" name="Procurement" id="hide_modeOfProcurement">

						<div class="col-md-3">
							<?php 
								$cn = 0;
								$purchase = Purchase::orderBy('ControlNo', 'ASC')->get();
								foreach ($purchase as $pur) {
									$cn = $pur->controlNo;
								}
								$cn =$cn+1;
							?>

							{{ Form::label('dispCN', 'Control No.', array('class' => 'create-label')) }}
							<input type="text"  name="dispCN"  class="form-control" value="<?php echo str_pad($cn, 5, '0', STR_PAD_LEFT); ?>"disabled>
							<input type="hidden" name="controlNo" value="<?php echo $cn; ?>">
						</div>
					</div>

					<div class="row">
						<div class="col-md-8">
							{{ Form::label('projectPurpose', 'Project/Purpose *', array('class' => 'create-label')) }}
							{{ Form::text('projectPurpose','', array('class'=>'form-control', 'placeholder' => 'Enter project/purpose','maxlength'=>'255')) }}

						@if (Session::get('error_projectPurpose'))
							<font color="red"><i>{{ Session::get('error_projectPurpose') }}</i></font>
						@endif
						</div>


						<div class="col-md-4">
							{{ Form::label('projectType', 'Project Type *', array('class' => 'create-label')) }}
							<select name="projectType" class="form-control" id="projectType">
								<option value="">None</option>
								<option value="Goods/Services" <?php if( Input::old('projectType') == "Goods/Services" ) echo "selected" ?>>Goods/Services</option>
								<option value="Infrastructure" <?php if( Input::old('projectType') == "Infrastructure" ) echo "selected" ?>>Infrastructure</option>
								<option value="Consulting Services" <?php if( Input::old('projectType') == "Consulting Services" ) echo "selected" ?>>Consulting Services</option>
							</select>
							<p> {{ Input::get('projectType')  }} </p>

							@if (Session::get('error_projectType'))
								<font color="red"><i>{{ Session::get('error_projectType') }}</i></font>
							@endif
						</div>

						
					</div>
					<br/>	

					<div class="row">
						<div class="col-md-6">
							{{ Form::label('sourceOfFund', 'Source of Fund *', array('class' => 'create-label')) }}
							{{ Form::text('sourceOfFund','', array('class'=>'form-control', 'placeholder'=>'Enter source','maxlength'=>'255')) }}

						@if (Session::get('error_sourceOfFund'))
							<font color="red"><i>{{ Session::get('error_sourceOfFund') }}</i></font>
						@endif
						</div>


						<div class="col-md-6">
							{{ Form::label('amount', 'Amount *', array('class' => 'create-label')) }}
							{{ Form::text('amount','',array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)', 'onkeypress' => 'return isNumberKey(event)','id'=>'num','maxlength'=>'12', 'placeholder' => 'Enter amount')) }}
						@if (Session::get('error_amount'))
							<font color="red"><i>{{ Session::get('error_amount') }}</i></font>
						@endif
						</div>
					</div>
					<br/>

					<div class="row">
						<div class="form-group col-md-6" id="template">
							{{ Form::label('office', 'Office *', array('class' => 'create-label')) }}
							<select id="office" name="office" class="form-control" data-live-search="true">
								<option value="">Please select</option>
								@foreach($office as $off)
									<option value="{{ $off->id }}" 
										<?php if(Input::old('office')==$off->id)
											echo "selected" ?>
										>
										{{{ $off->officeName }}}
									</option>
								@endforeach
							</select>
							@if (Session::get('error_office'))
								<font color="red"><i>{{ Session::get('error_office') }}</i></font>
							@endif
						</div>

						<div class="form-group col-md-6" id="template">
							{{ Form::label('requisitioner', 'Requisitioner *', array('class' => 'create-label')) }}
							<select class="form-control" id="requisitioner" name="requisitioner"  data-live-search="true" >
								<option value="">Please select</option>
								@foreach($users as $user)
									{{{ $fullname = $user->lastname . ", " . $user->firstname }}}
									@if($user->confirmed == 0)
										continue;
									@else
									<option value="{{ $user->id }}" class="{{$user->office_id}}"
										<?php if(Input::old('requisitioner')==$user->id)
										echo "selected" ?>
										>{{ $fullname }}
									</option>
									@endif
								@endforeach
							</select>
							@if (Session::get('error_requisitioner'))
								<font color="red"><i>{{ Session::get('error_requisitioner') }}</i></font>
							@endif
						</div>
					</div>
			
					<div class="row">
						<div class="form-group col-md-6" id="template">
							{{ Form::label('dateTime', 'Date Received *', array('class' => 'create-label')) }}
							<div class="input-group date form_datetime col-md-12" data-date="{{ date('Y-m-d') }}T{{ date('H:i:s') }}Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input2">
								<input id="disabled_datetimeDateRec" onchange="fix_formatDateRec()" class="form-control" size="16" type="text" value="<?php  if(Input::old("dateReceived")) { echo Input::old("dateReceived"); } else { echo $date_today; } ?>" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
							</div>
							<input type="hidden" id="dtp_input2" name="dateReceived" value="<?php  if(Input::old("dateReceived")) { echo Input::old("dateReceived"); } else { echo $date_today; } ?>" />
							@if (Session::get('error_dateReceived'))
								<font color="red"><i>{{ Session::get('error_dateReceived') }}</i></font>
							@endif
							<br>
						</div>

						<div class="form-group col-md-6" id="template">
							{{ Form::label('dateTime', 'Date Requested ', array('class' => 'create-label')) }}
							<div class="input-group date form_datetime col-md-12" data-date="{{ date('Y-m-d') }}T{{ date('H:i:s') }}Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
								<input id="disabled_datetime" onchange="fix_format()" class="form-control" size="16" type="text" value="{{{ Input::old('dateRequested') }}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
							</div>
							<input type="hidden" id="dtp_input1" name="dateRequested" value="{{{ Input::old('dateRequested') }}}" />
							@if (Session::get('error_dateRequested'))
								<font color="red"><i>{{ Session::get('error_dateRequested') }}</i></font>
							@endif
							<br>
						</div>
					</div>

					<!--  Image Module -->

					<label class="create-label">Related files:</label>
					<div class="panel panel-default fc-div">
						<div class="panel-body" style="padding: 5px 20px;">
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

							<?php $doc_id = $doc_id+1; ?>

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
	
		{{ Session::forget('notice'); }}
		{{ Session::forget('main_error'); }}
		{{ Session::forget('error_projectPurpose'); }}
		{{ Session::forget('error_projectType'); }}
		{{ Session::forget('error_sourceOfFund'); }}
		{{ Session::forget('error_amount'); }}
		{{ Session::forget('error_office'); }}
		{{ Session::forget('error_requisitioner'); }}
		{{ Session::forget('error_modeOfProcurement'); }}
		{{ Session::forget('error_dateRequested'); }}
		{{ Session::forget('error_dateReceived'); }}
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
				amount = 0;
			}
			else
			{
				document.getElementById("num").value = window.old_amount;
				amount = 0;
			}

			newamount =	amount; 

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

		function fix_formatDateRec()
		{
			document.getElementById('disabled_datetimeDateRec').value = document.getElementById('dtp_input2').value;
		}
	</script>

	<script>
		function change_OtherType(value)
		{
			//alert(value);
			if(value == "pakyaw")
			{
				document.getElementById('projectType').disabled = true;
				document.getElementById('projectType').selectedIndex = 0;

				window.last_selected = document.getElementById('modeOfProcurement').selectedIndex 
				document.getElementById('modeOfProcurement').selectedIndex = 4;
				document.getElementById('modeOfProcurement').onchange = 4;
				document.getElementById("num").onchange = new Function("numberWithCommas2(this.value)");
				document.getElementById('hide_modeOfProcurement').value = document.getElementById('modeOfProcurement').value;
				
			}
			else if(value == "Direct Contracting")
			{
				document.getElementById('projectType').disabled = false;
				document.getElementById('projectType').disabled = false;
				window.last_selected = document.getElementById('modeOfProcurement').selectedIndex 
				document.getElementById('modeOfProcurement').selectedIndex = 5;
				document.getElementById('modeOfProcurement').onchange = 5;
				document.getElementById("num").onchange = new Function("numberWithCommas2(this.value)");
				document.getElementById('hide_modeOfProcurement').value = document.getElementById('modeOfProcurement').value;

			}
			else
			{
				document.getElementById('projectType').disabled = false;
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

	<script type="text/javascript">
	    // When the document is ready
	    $(document).ready(function () 
	    {
	       var otherType = document.getElementById('otherType').value;
	       //alert(otherType);

	       if(otherType == 'pakyaw')
	       {

	       		document.getElementById('projectType').disabled = true;
	       }
	    });
	</script>

	<!-- js for chained dropdown -->
@stop