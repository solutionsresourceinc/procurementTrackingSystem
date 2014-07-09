<style type="text/css">
		#description {
	    height: 400px;
	    top: calc(50% - 200px) !important;
	    overflow: hidden;
		}
	</style>

	<?php $wfName = Workflow::find('3'); ?>
<!--h1 class="page-header">{{{ $wfName->workFlowName }}}</h1-->
<br/>

<div class="panel panel-success">
	<div class="panel-heading">
		<?php $sectionName = Section::find('9'); ?>
		<h3 class="panel-title"> <?php $secname=strtoupper($sectionName->sectionName);
			$pos=90;
			$str = substr($secname, 0, $pos) . "<br>" . substr($secname, $pos);
			echo $str;
	?>  </h3>
	</div>
	<div class="panel-body">
		<table border="1" class="workflow-table">
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="25%">ACTION</th>
			</tr>
			<?php 
			$section1 = DB::table('tasks')->where('wf_id','3')->get(); 
			?>

			@foreach($section1 as $section)
			@if($section->section_id == 1)
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
					<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
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
						{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2', 'id' => 'temp_alert'])}}
						{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
						<a href="/workflow/replace/{{$section->id}}" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn ajax btn-info" data-toggle="modal" data-target="#description"  onclick="empty_div()"><span class="glyphicon glyphicon-list-alt"></span></a><br>
					</td>
				</tr>

				@endif
				@endforeach
			</table>
		</div>
	</div>

	<div class="panel panel-success">
		<div class="panel-heading">
			<?php $sectionName = Section::find('10'); ?>
			<h3 class="panel-title">{{{ strtoupper($sectionName->sectionName) }}}</h3>
		</div>
		<div class="panel-body">
			<table border="1" class="workflow-table">
				<tr>
					<th class="workflow-th" width="25%">TASK</th>
					<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
					<th class="workflow-th" width="25%">ACTION</th>
				</tr>
				<?php 
				$section1 = DB::table('tasks')->where('wf_id','3')->get(); 
				?>

				@foreach($section1 as $section)
				@if($section->section_id == 2)
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
						<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
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
							{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2', 'id' => 'temp_alert'])}}
							{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
							<a href="/workflow/replace/{{$section->id}}" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn ajax btn-info" data-toggle="modal" data-target="#description"  onclick="empty_div()"><span class="glyphicon glyphicon-list-alt"></span></a><br>
						</td>
					</tr>

					@endif
					@endforeach
				</table>
			</div>
		</div>

		<div class="panel panel-success">
			<div class="panel-heading">
				<?php $sectionName = Section::find('11'); ?>
				<h3 class="panel-title">{{{ strtoupper($sectionName->sectionName) }}}</h3>
			</div>
			<div class="panel-body">
				<table border="1" class="workflow-table">
					<tr>
						<th class="workflow-th" width="25%">TASK</th>
						<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
						<th class="workflow-th" width="25%">ACTION</th>
					</tr>
					<?php 
					$section1 = DB::table('tasks')->where('wf_id','3')->get(); 
					?>

					@foreach($section1 as $section)
					@if($section->section_id == 3)
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
							<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
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
								{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2', 'id' => 'temp_alert'])}}
								{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
								<a href="/workflow/replace/{{$section->id}}" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn ajax btn-info" data-toggle="modal" data-target="#description"  onclick="empty_div()"><span class="glyphicon glyphicon-list-alt"></span></a><br>
							</td>
						</tr>

						@endif
						@endforeach
					</table>
				</div>
			</div>

			<div class="panel panel-success">
				<div class="panel-heading">
					<?php $sectionName = Section::find('12'); ?>
					<h3 class="panel-title">{{{ strtoupper($sectionName->sectionName) }}}</h3>
				</div>
				<div class="panel-body">
					<table border="1" class="workflow-table">
						<tr>
							<th class="workflow-th" width="25%">TASK</th>
							<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
							<th class="workflow-th" width="25%">ACTION</th>
						</tr>
						<?php 
						$section1 = DB::table('tasks')->where('wf_id','3')->get(); 
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
								<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
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
									{{Form::button('Save', ['class' => 'btn btn-success save-edit mode2', 'id' => 'temp_alert'])}}
									{{Form::button('Cancel', ['class' => 'btn btn-default cancel-edit mode2'])}}
									<a href="/workflow/replace/{{$section->id}}" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn ajax btn-info" data-toggle="modal" data-target="#description"  onclick="empty_div()"><span class="glyphicon glyphicon-list-alt"></span></a><br>
								</td>
							</tr>

							@endif
							@endforeach
						</table>
					</div>
				</div>