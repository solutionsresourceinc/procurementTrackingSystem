@extends('layouts.dashboard')

@section('content')

	<!-- Modal Div -->
	<style type="text/css">
		#description {
	    height: 400px;
	    top: calc(50% - 200px) !important;
	    overflow: hidden;
		}
	</style>
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

<h1 class="page-header">Small Value Procurement (Below P50,000)</h1>

<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="panel-title">A. PURCHASE REQUEST</h3>
	</div>

	<div class="panel-body">
		<table border="1" class="workflow-table">
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="25%">ACTION</th>
			</tr>

			<?php $section1 = DB::table('tasks')->where('wf_id','1')->get(); ?>

			@foreach($section1 as $section)
			@if($section->section_id == 1)
			<?php $d_id=$section->designation_id; ?>

			<tr>
				<td> {{{ $section->taskName }}} </td>

				<td> 
					<?php 
					$desig = DB::table('designation')->where('id', $d_id)->get();	
					if($d_id!=0)
					{
						?>

						@foreach ($desig as $desigs)
						<div class="mode1" id="insert_{{$section->id}}">
							{{ $desigs->designation }}
						</div>
						@endforeach

						<?php	 
					}
					else 
					{
						?>
						<div class="mode1" id="insert_{{$section->id}}"></div>
						<?php
					}
					$desig = DB::table('designation')->get();	
					?>

					<form class="form ajax" action="submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">

						<select name ="designa" class = "form-control mode2 edit-text" style="width:100%">
							<option value="0" selected>None</option>
							@foreach ($desig as $desigs)
							<option value="{{$desigs->id}}" >{{$desigs->designation}}</option>
							@endforeach
						</select>

						<input type="hidden" value="{{$section->id}}" name ="task_id">

						{{ Form::close() }}
					</td>
					<td class="col-md-4">
						{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'data-toggle' => 'tooltip']))}}
						{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2'])}}
						{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
						<a href="replace/{{$section->id}}" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn ajax btn-info" data-toggle="modal" data-target="#description"  onclick="empty_div()"><span class="glyphicon glyphicon-list-alt"></span></a><br>
					</td>
				</tr>

				@endif
				@endforeach
			</table>
		</div>
	</div>

	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">B. BAC REQUIREMENTS</h3>
		</div>

		<div class="panel-body">
			<table border="1" class="workflow-table">
				<tr>
					<th class="workflow-th" width="25%">TASK</th>
					<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
					<th class="workflow-th" width="25%">ACTION</th>
				</tr>
				<?php $section1 = DB::table('tasks')->where('wf_id','1')->get(); ?>

				@foreach($section1 as $section)
				@if($section->section_id == 2)
				<?php $d_id=$section->designation_id; ?>
				<tr> 
					<td> {{{ $section->taskName }}} </td>
					<td> 
						<?php
						$desig = DB::table('designation')->where('id', $d_id)->get();	
						if($d_id!=0)
						{
							?>

							@foreach ($desig as $desigs)
							<div class="mode1" id="insert_{{$section->id}}">
								{{$desigs->designation }}
							</div>
							@endforeach

							<?php	 
						}
						else 
						{
							?>
							<div class="mode1" id="insert_{{$section->id}}"></div>
							<?php
						}
						$desig = DB::table('designation')->get();	
						?>

						<form class="form ajax" action="submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
							<select name ="designa" class = "form-control mode2 edit-text" style="width:100%">
								<option value="0">None</option>
								@foreach ($desig as $desigs)
								<option value="{{$desigs->id}}">{{$desigs->designation}}</option>
								@endforeach
							</select>

							<input type="hidden" value="{{$section->id}}" name ="task_id">

							{{ Form::close() }}
						</td>

						<td class="col-md-4">

							{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'data-toggle' => 'tooltip']))}}
							{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2'])}}
							{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
							<a href="replace/{{$section->id}}" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn ajax btn-info" data-toggle="modal" data-target="#description"  onclick="empty_div()"><span class="glyphicon glyphicon-list-alt"></span></a><br>
						</td>
					</tr>
					@endif
					@endforeach
				</table>
			</div>
		</div>

		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 class="panel-title">C. PURCHASE ORDER</h3>
			</div>

			<div class="panel-body">
				<table border="1" class="workflow-table">
					<tr>
						<th class="workflow-th" width="25%">TASK</th>
						<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
						<th class="workflow-th" width="25%">ACTION</th>
					</tr>
					<?php $section1 = DB::table('tasks')->where('wf_id','1')->get(); ?>

					@foreach($section1 as $section)
					@if($section->section_id == 3)
					<?php  $d_id=$section->designation_id; ?>
					<tr>
						<td> {{{ $section->taskName }}} </td>
						<td>

							<?php
							$desig = DB::table('designation')->where('id', $d_id)->get();	
							if($d_id!=0)
							{
								?>

								@foreach ($desig as $desigs)
								<div class="mode1" id="insert_{{$section->id}}">
									{{$desigs->designation }}
								</div>
								@endforeach

								<?php	 
							}
							else 
							{
								?>
								<div class="mode1" id="insert_{{$section->id}}"></div>
								<?php
							}
							$desig = DB::table('designation')->get();	
							?>

							<form class="form ajax" action="submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
								<select name ="designa" class = "form-control mode2 edit-text" style="width:100%">
									<option value=0>None                                 </option>
									@foreach ($desig as $desigs)
									<option value="{{$desigs->id}}">{{$desigs->designation}}</option>
									@endforeach

								</select>
								<input type="hidden" value="{{$section->id}}" name ="task_id">

								{{ Form::close() }}
							</td>
							<td class="col-md-4">

								{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'data-toggle' => 'tooltip']))}}
								{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2'])}}
								{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
								<a href="replace/{{$section->id}}" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn ajax btn-info" data-toggle="modal" data-target="#description"  onclick="empty_div()"><span class="glyphicon glyphicon-list-alt"></span></a><br>
							</td>
						</tr>

						@endif
						@endforeach
					</table>
				</div>
			</div>

			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">D. VOUCHER</h3>
				</div>
				<div class="panel-body">
					<table border="1" class="workflow-table">
						<tr>
							<th class="workflow-th" width="25%">TASK</th>
							<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
							<th class="workflow-th" width="25%">ACTION</th>
						</tr>
						<?php 
						$section1 = DB::table('tasks')->where('wf_id','1')->get();	

						?>

						@foreach($section1 as $section)
						@if($section->section_id == 4)
						<?php 
						$d_id=$section->designation_id; 

						?>
						<tr>
							<td> {{{ $section->taskName }}} </td>
							<td>
								<?php

								$desig = DB::table('designation')->where('id', $d_id)->get();	
								if($d_id!=0){
									?>

									@foreach ($desig as $desigs)
									<div class="mode1" id="insert_{{$section->id}}">
										{{$desigs->designation }}
									</div>
									@endforeach

									<?php	 
								}
								else 
								{
									?>
									<div class="mode1" id="insert_{{$section->id}}"></div>
									<?php
								}
								$desig = DB::table('designation')->get();	
								?>
								<form class="form ajax" action="submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
									<select name ="designa" class = "form-control mode2 edit-text" style="width:100%">
										<option value=0>None                                 </option>
										@foreach ($desig as $desigs)
										<option value="{{$desigs->id}}">{{$desigs->designation}}</option>
										@endforeach

									</select>
									<input type="hidden" value="{{$section->id}}" name ="task_id">

									{{ Form::close() }}
								</td>
								<td class="col-md-4">

									{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Edit', 'data-placement' => 'top', 'data-toggle' => 'tooltip']))}}
									{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2'])}}
									{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
									<a href="replace/{{$section->id}}" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn ajax btn-info" data-toggle="modal" data-target="#description"  onclick="empty_div()"><span class="glyphicon glyphicon-list-alt"></span></a><br>
								</td>
							</tr>

							@endif
							@endforeach
						</table>
					</div>
				</div>



				@stop 

				@section('footer')
				{{ HTML::script('js/bootstrap-ajax.js');}}
				<script>
				function empty_div()
				{
					document.getElementById("description_body").innerHTML = "";
				}

				</script>
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
		//document.getElementById("insert_1").innerHTML = "Please Wait. Changing Assigned Designation!";
		//$(this).closest("tr").find(".mode1").innerHTML = "whatever";
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