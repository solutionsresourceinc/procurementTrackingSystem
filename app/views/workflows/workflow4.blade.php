<?php $wfName = Workflow::find('4'); ?>
<br/>

<!-- PURCHASE REQUEST SECTION -->
<div class="panel panel-success">
	<div class="panel-heading">
		<?php $sectionName = Section::find('13'); ?>
		<div class="panel-title"> 
			<?php $section_name = strtoupper($sectionName->sectionName);
			$position =90;
			$str = substr($section_name, 0, $position) . "<br>" . substr($section_name, $position);
			echo $str;
			?> 
		</div>
	</div>

	<div class="panel-body">
	
		<!-- Displays form for adding new tasks to the workflow -->
		<div id="office-create-form" class="well div-form">
		    	{{ Form::open(['url'=>'addtask'], 'POST', array('role' => 'form')) }}
			    	<div class="col-md-8">
				    	{{ Form::text('label', null, array('class' => 'form-control', 'placeholder' => 'Enter field label here','maxlength'=>'45')) }}
				    		    <input type ="hidden" name="section_id" value="{{$sectionName->id}}">
				    </div>
			
				    <div class="col-md-3">
				    	{{ Form::submit('Add', array('class' => 'btn btn-success btn-block create-btn')) }}
				    </div>
			    {{ Form::close() }}
		</div>

		<table border="1" class="workflow-table">

			<!-- Displays the tasks added through the add new task form -->
			<?php $taskcount= OtherDetails::where('section_id', $sectionName->id )->count();?>
			@if($taskcount!=0)
				<?php 
					$addedtasks= OtherDetails::where('section_id', $sectionName->id )->get();
				?>
				@foreach ($addedtasks as $addedtask)
					<tr>
						<td colspan="3">{{$addedtask->label}}</td>
						<td>
							<form method="POST" action="deladdtask"  id="myForm_{{ $addedtask->id }}" name="myForm" style="display: -webkit-inline-box;">
								<input type="hidden" name="id" value="<?php echo $addedtask->id ?>">
								<button type="button" onclick="hello( {{{ $addedtask->id }}})" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete"><span class="glyphicon glyphicon-trash"></span></button>
							</form>
						</td>
					</tr> 
				@endforeach
			@endif

			<!-- Displays the defined tasks of the workflow -->
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="10%">NO. OF DAYS</th>
				<th class="workflow-th" width="35%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="15%">ACTION</th>
			</tr>

			<?php $sections = DB::table('tasks')->where('wf_id','4')->get(); ?>

			@foreach($sections as $section)
				@if($section->section_id == 1)
					<?php $designation_id=$section->designation_id; ?>

					<tr>
						<td>{{{ $section->taskName }}}</td>
						<td>{{{ $section->maxDuration }}}</td>

						<td> 
						<?php  $designations = DB::table('designation')->where('id', $designation_id)->get();	?>
						@if($designation_id!=0)
							@foreach ($designations as $designation)
							<div class="mode1" id="insert_{{$section->id}}">
								{{ $designation->designation }}
								<input type="hidden" id="hide_currentDesignation" class="hide_currentDesignation" value="{{$designation->id}}">
							</div>
							@endforeach
						@else
							<div class="mode1" id="insert_{{$section->id}}">None</div>
							<input type="hidden" id="hide_currentDesignation" class="hide_currentDesignation" value="0">
						@endif
							
						<?php $designations = DB::table('designation')->orderBy('designation', 'ASC')->get(); ?>

						<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">

							<select name ="designa" id="designa" class = "form-control mode2 edit-text" style="width:100%">
								<option value="0" selected id="0">None</option>
								@foreach ($designations as $designation)
								<option value="{{$designation->id}}" id="{{$designation->id}}">{{$designation->designation}}</option>
								@endforeach
							</select>

							<input type="hidden" value="{{$section->id}}" name ="task_id">

						{{ Form::close() }}
					</td>
						<td class="col-md-4">
							{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Assign Designation', 'data-placement' => 'top', 'data-toggle' => 'tooltip']))}}
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

<!-- REQUIREMENTS SECTION -->
<div class="panel panel-success">
	<div class="panel-heading">
		<?php $sectionName = Section::find('14'); ?>
		<h3 class="panel-title"> {{{ strtoupper($sectionName->sectionName) }}} </h3>
	</div>

	<div class="panel-body">
		
		<!-- Displays the tasks added through the add new task form -->
		<div id="office-create-form" class="well div-form">
		    	{{ Form::open(['url'=>'addtask'], 'POST', array('role' => 'form')) }}
			    	<div class="col-md-8">
				    	{{ Form::text('label', null, array('class' => 'form-control', 'placeholder' => 'Enter field label here','maxlength'=>'45')) }}
				    		    <input type ="hidden" name="section_id" value="{{$sectionName->id}}">
				    </div>
			
				    <div class="col-md-3">
				    	{{ Form::submit('Add', array('class' => 'btn btn-success btn-block create-btn')) }}
				    </div>
			    {{ Form::close() }}
		</div>

		<table border="1" class="workflow-table">

			<!-- Displays the tasks added through the add new task form -->
			<?php $taskcount= OtherDetails::where('section_id', $sectionName->id )->count(); ?>
			@if($taskcount!=0)
				<?php 
				$addedtasks= OtherDetails::where('section_id', $sectionName->id )->get();
				?>
				@foreach ($addedtasks as $addedtask)
					<tr>
						<td colspan="3">{{$addedtask->label}}</td>
						<td>
							<form method="POST" action="deladdtask"  id="myForm_{{ $addedtask->id }}" name="myForm" style="display: -webkit-inline-box;">
								<input type="hidden" name="id" value="<?php echo $addedtask->id ?>">
								<button type="button" onclick="hello( {{{ $addedtask->id }}})" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete"><span class="glyphicon glyphicon-trash"></span></button>
							</form>
						</td>
					</tr> 
				@endforeach
			@endif		

			<!-- Displays the defined tasks of the workflow -->
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="10%">NO. OF DAYS</th>
				<th class="workflow-th" width="35%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="15%">ACTION</th>
			</tr>
			<?php $sections = DB::table('tasks')->where('wf_id','4')->get(); ?>

			@foreach($sections as $section)
				@if($section->section_id == 2)
					<?php $designation_id=$section->designation_id; ?>
					<tr> 
						<td> {{{ $section->taskName }}} </td>
						<td>{{{ $section->maxDuration }}}</td>
						<td> 
						<?php  $designations = DB::table('designation')->where('id', $designation_id)->get();	?>
						@if($designation_id!=0)
							@foreach ($designations as $designation)
							<div class="mode1" id="insert_{{$section->id}}">
								{{ $designation->designation }}
								<input type="hidden" id="hide_currentDesignation" class="hide_currentDesignation" value="{{$designation->id}}">
							</div>
							@endforeach
						@else
							<div class="mode1" id="insert_{{$section->id}}">None</div>
							<input type="hidden" id="hide_currentDesignation" class="hide_currentDesignation" value="0">
						@endif
							
						<?php $designations = DB::table('designation')->orderBy('designation', 'ASC')->get(); ?>

						<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">

							<select name ="designa" id="designa" class = "form-control mode2 edit-text" style="width:100%">
								<option value="0" selected id="0">None</option>
								@foreach ($designations as $designation)
								<option value="{{$designation->id}}" id="{{$designation->id}}">{{$designation->designation}}</option>
								@endforeach
							</select>

							<input type="hidden" value="{{$section->id}}" name ="task_id">

						{{ Form::close() }}
					</td>

						<td class="col-md-4">

							{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Assign Designation', 'data-placement' => 'top', 'data-toggle' => 'tooltip']))}}
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

<!-- VOUCHER SECTION -->
<div class="panel panel-success">
	<div class="panel-heading">
		<?php $sectionName = Section::find('15'); ?>
		<h3 class="panel-title"> {{{ strtoupper($sectionName->sectionName) }}} </h3>
	</div>

	<div class="panel-body">
		
		<!-- Displays the tasks added through the add new task form -->
		<div id="office-create-form" class="well div-form">
		    	{{ Form::open(['url'=>'addtask'], 'POST', array('role' => 'form')) }}
			    	<div class="col-md-8">
				    	{{ Form::text('label', null, array('class' => 'form-control', 'placeholder' => 'Enter field label here','maxlength'=>'45')) }}
				    		    <input type ="hidden" name="section_id" value="{{$sectionName->id}}">
				    </div>
			
				    <div class="col-md-3">
				    	{{ Form::submit('Add', array('class' => 'btn btn-success btn-block create-btn')) }}
				    </div>
			    {{ Form::close() }}
		</div>

		<table border="1" class="workflow-table">

			<!-- Displays the tasks added through the add new task form -->
			<?php $taskcount= OtherDetails::where('section_id', $sectionName->id )->count(); ?>
			
			@if($taskcount!=0)
				<?php 
					$addedtasks= OtherDetails::where('section_id', $sectionName->id )->get();
				?>
				@foreach ($addedtasks as $addedtask)
					<tr>
						<td colspan="3">{{$addedtask->label}}</td>
						<td>
							<form method="POST" action="deladdtask"  id="myForm_{{ $addedtask->id }}" name="myForm" style="display: -webkit-inline-box;">
								<input type="hidden" name="id" value="<?php echo $addedtask->id ?>">
								<button type="button" onclick="hello( {{{ $addedtask->id }}})" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete"><span class="glyphicon glyphicon-trash"></span></button>
							</form>
						</td>
					</tr> 
				@endforeach
			@endif

			<!-- Displays the defined tasks of the workflow -->
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="10%">NO. OF DAYS</th>
				<th class="workflow-th" width="35%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="15%">ACTION</th>
			</tr>
			<?php $sections = DB::table('tasks')->where('wf_id','4')->get(); ?>

			@foreach($sections as $section)
				@if($section->section_id == 3)
					<?php  $designation_id=$section->designation_id; ?>
					<tr>
						<td> {{{ $section->taskName }}} </td>
						<td>{{{ $section->maxDuration }}}</td>
						<td> 
						<?php  $designations = DB::table('designation')->where('id', $designation_id)->get();	?>
						@if($designation_id!=0)
							@foreach ($designations as $designation)
							<div class="mode1" id="insert_{{$section->id}}">
								{{ $designation->designation }}
								<input type="hidden" id="hide_currentDesignation" class="hide_currentDesignation" value="{{$designation->id}}">
							</div>
							@endforeach
						@else
							<div class="mode1" id="insert_{{$section->id}}">None</div>
							<input type="hidden" id="hide_currentDesignation" class="hide_currentDesignation" value="0">
						@endif
							
						<?php $designations = DB::table('designation')->orderBy('designation', 'ASC')->get(); ?>

						<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">

							<select name ="designa" id="designa" class = "form-control mode2 edit-text" style="width:100%">
								<option value="0" selected id="0">None</option>
								@foreach ($designations as $designation)
								<option value="{{$designation->id}}" id="{{$designation->id}}">{{$designation->designation}}</option>
								@endforeach
							</select>

							<input type="hidden" value="{{$section->id}}" name ="task_id">

						{{ Form::close() }}
					</td>
						<td class="col-md-4">

							{{HTML::decode (Form::button('<span class="glyphicon glyphicon-edit"></span>', ['class' => 'btn btn-success table-actions allow-edit mode1', 'data-original-title' => 'Assign Designation', 'data-placement' => 'top', 'data-toggle' => 'tooltip']))}}
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