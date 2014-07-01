@extends('layouts.dashboard')

@section('header')
<!-- CSS and JS for Dropdown Search
	{{ HTML::script('drop_search/bootstrap-select.js')}}
	{{ HTML::style('drop_search/bootstrap-select.css')}}
-->
{{ HTML::style('date_picker/bootstrap-datetimepicker.min.css')}}
{{ HTML::script('date_picker/bootstrap-datetimepicker.js') }}
{{ HTML::script('date_picker/bootstrap-datetimepicker.fr.js') }}

<!--
{{ HTML::script('js/bootstrap-dropdown.js') }}
-->
{{ HTML::script('js/jquery.chained.min.js') }} 


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
				<br>

				<div>
					{{ Form::label('status', 'Status: ', array('class' => 'create-label')) }}
					<input type="text" value="New" readonly class="form-control">
				</div>
				<br>

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
					{{ Form::text('amount','',array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)','id'=>'num')) }}
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
							>{{{ $key->officeName }}}</option>
							@endforeach
						</select>
						@if (Session::get('m4'))
						<font color="red"><i>{{ Session::get('m4') }}</i></font>
						@endif
						<br>
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
						<br>
					</div>

					<div>
						{{ Form::label('modeOfProcurement', 'Mode of Procurement *', array('class' => 'create-label')) }}
						<select  name="modeOfProcurement" id="modeOfProcurement" class="form-control" data-live-search="true">
							<option value="">Please select</option>
							@foreach($workflow as $wf)
							<option value="{{ $wf->id }}" 
								<?php
								if(Input::old('modeOfProcurement')==$wf->id)
									echo "selected";
								?> >{{$wf->workFlowName}}</option>
								@endforeach

							</select>
							@if (Session::get('m6'))
							<font color="red"><i>The mode of procurement is required field</i></font>
							@endif
							<br>
						</div><br>

						<div class="form-group">
							{{ Form::label('dateTime', 'Date Requested *', array('class' => 'create-label')) }}
							<div class="input-group date form_datetime col-md-12" data-date="{{ date('Y-m-d') }}T{{ date('H:i:s') }}Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
								<input class="form-control" size="16" type="text" value="{{{ Input::old('dateRequested') }}}" readonly>
								<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
								<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
							</div>
							<input type="hidden" id="dtp_input1" name="dateRequested" value="{{{ Input::old('dateRequested') }}}" />
							@if (Session::get('m7'))
							<font color="red"><i>{{ Session::get('m7') }}</i></font>
							@endif
							<br>
						</div>

						<div><br>
							{{ Form::submit('Create Purchase Request',array('class'=>'btn btn-success')) }}
							{{ link_to( 'purchaseRequest/view', 'Cancel Create', array('class'=>'btn btn-default') ) }}

						</div>
					</div>
				</div>	
			</div>		
			{{ Form::close() }}	



			<div>


<!--  
Image Module
-->

<div class="form-create fc-div">
<h2>Attachments</h2>
<br>
					@if(Session::get('imgsuccess'))
						<div class="alert alert-success"> {{ Session::get('imgsuccess') }}</div> 
					@endif

					@if(Session::get('imgerror'))
						<div class="alert alert-danger"> {{ Session::get('imgerror') }}</div> 
					@endif

<br>
<?php

$id = 0;
$purchase = Purchase::orderBy('id', 'ASC')->get(); ?>
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

<input name="file[]" type="file"  multiple/>
<input name="doc_id" type="hidden" value="{{ $doc_id }}">

<br>
<br>
<button type="submit"  class "btn btn-primary">Add</button>
{{ Form::close() }}
</div>
<div id="img-section">

			<?php

			 $attachments = DB::table('attachments')->where('doc_id', $doc_id)->get();	
			 $srclink="uploads\\";
			 ?>
			@foreach ($attachments as $attachment) 
	<div class="image-container">
				<img class="img-thumbnail" src="{{asset('uploads/'.$attachment->data)}}" style="width: 200px; height: 200px;" />
{{ Form::open(array('method' => 'post', 'url' => 'delimage')) }}
<input type="hidden" name="hide" value="{{$attachment->id}}">
  <button><img height="10%" width="10%" class="star-button " src="{{asset('img/Delete_Icon.png')}}"></button>
{{Form::close()}}
</div>

			@endforeach
	  
	</div>


	<!-- End Image Module-->


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
				x = x.replace(',','');
				x = parseFloat(x).toFixed(2);
				var parts = x.toString().split(".");
				parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				parts =  parts.join(".");
				document.getElementById("num").value = parts;
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
			</script>

			<!-- js for chained dropdown -->
			
@stop