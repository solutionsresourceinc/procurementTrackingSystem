<?php $wfName = Workflow::find('1'); ?>
<br/>
<?php $sectionName = Section::find('5'); ?>
<div class="panel panel-success">
	<div class="panel-heading">
		<?php $sectionName = Section::find('1'); ?>
		<div class="panel-title"> 
			<?php
				$secname=strtoupper($sectionName->sectionName);
				$pos=90;
				$str = substr($secname, 0, $pos) . "<br>" . substr($secname, $pos);
				echo $str;
			?> 
		</div>
	</div>
	<div class="panel-body">

		<!-- Displays form for adding new tasks to the workflow -->
		<div id="office-create-form" class="well div-form">
	    	{{ Form::open(['url'=>'addtask'], 'POST', array('role' => 'form')) }}
		    	<div class="col-md-8">
			    	{{ Form::text('label', null, array('class' => 'form-control', 'placeholder' => 'Task Name')) }}
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
					$addontasks= OtherDetails::where('section_id', $sectionName->id )->get();
			?>
			@foreach ($addontasks as $addontask)
				<tr>
					<td colspan="2">{{$addontask->label}}</td>
					<td>
						<form method="POST" action="deladdtask"  id="myForm_{{ $addontask->id }}" name="myForm" style="display: -webkit-inline-box;">
							<input type="hidden" name="id" value="<?php echo $addontask->id ?>">
							<button type="button" onclick="hello( {{{ $addontask->id }}})" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
					</td>
				</tr> 
				@endforeach
			@endif
			<!-- Displays the defined tasks of the workflow -->
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="25%">ACTION</th>
			</tr>
			<?php $sections = DB::table('tasks')->where('wf_id','1')->get(); ?>
			@foreach($sections as $section)
				@if($section->section_id == 1)
					<?php $d_id=$section->designation_id; ?>
					<tr>
						<td>{{{ $section->taskName }}}</td>
						<td> 
							<?php $desigs = DB::table('designation')->where('id', $d_id)->get(); ?>
							@if($d_id!=0)
								@foreach ($desigs as $desig)
									<div class="mode1" id="insert_{{$section->id}}">
										{{ $desig->designation }}
									</div>
								@endforeach
							@else 
								<div class="mode1" id="insert_{{$section->id}}">None</div>
							@endif
							<?php $desigs = DB::table('designation')->get(); ?>
							<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
								<select name ="designa" class = "form-control mode2 edit-text" style="width:100%">
									<option value="0" selected>None</option>
									@foreach ($desigs as $desig)
										<option value="{{$desig->id}}" >{{$desig->designation}}</option>
									@endforeach
								</select>
								<input type="hidden" value="{{$section->id}}" name ="task_id">
							</form>
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
		<?php $sectionName = Section::find('2'); ?>
		<h3 class="panel-title"> {{{ strtoupper($sectionName->sectionName) }}} </h3>
	</div>

	<div class="panel-body">

		<!-- Displays the tasks added through the add new task form -->
		<div id="office-create-form" class="well div-form">
	    	{{ Form::open(['url'=>'addtask'], 'POST', array('role' => 'form')) }}
		    	<div class="col-md-8">
			    	{{ Form::text('label', null, array('class' => 'form-control', 'placeholder' => 'Task Name')) }}
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
			<?php $addontasks = OtherDetails::where('section_id', $sectionName->id )->get(); ?>
				@foreach ($addontasks as $addontask)
					<tr>
						<td colspan="2">{{$addontask->label}}</td>
						<td>
							<form method="POST" action="deladdtask"  id="myForm_{{ $addontask->id }}" name="myForm" style="display: -webkit-inline-box;">
								<input type="hidden" name="id" value="<?php echo $addontask->id ?>">
								<button type="button" onclick="hello( {{{ $addontask->id }}})" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete"><span class="glyphicon glyphicon-trash"></span></button>
							</form>
						</td>
					</tr> 
				@endforeach
			@endif
			<!-- Displays the defined tasks of the workflow -->
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="25%">ACTION</th>
			</tr>
			<?php $sections = DB::table('tasks')->where('wf_id','1')->get(); ?>
			@foreach($sections as $section)
				@if($section->section_id == 2)
					<?php $d_id=$section->designation_id; ?>
					<tr> 
						<td> {{{ $section->taskName }}} </td>
						<td> 
							<?php $desig = DB::table('designation')->where('id', $d_id)->get(); ?>
							@if($d_id!=0)
								@foreach($desigs as $desig)
									<div class="mode1" id="insert_{{$section->id}}">
										{{$desig->designation }}
									</div>
								@endforeach
							@else 
								<div class="mode1" id="insert_{{$section->id}}">None</div>
							@endif
							<?php $desig = DB::table('designation')->get();	?>
							<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
								<select name ="designa" class = "form-control mode2 edit-text" style="width:100%">
									<option value="0">None</option>
									@foreach($desigs as $desig)
										<option value="{{$desig->id}}">{{$desig->designation}}</option>
									@endforeach
								</select>
								<input type="hidden" value="{{$section->id}}" name ="task_id">
							</form>
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
		<?php $sectionName = Section::find('3'); ?>
		<h3 class="panel-title"> {{{ strtoupper($sectionName->sectionName) }}} </h3>
	</div>

	<div class="panel-body">

		<!-- Displays the tasks added through the add new task form -->
		<div id="office-create-form" class="well div-form">
		    	{{ Form::open(['url'=>'addtask'], 'POST', array('role' => 'form')) }}
			    	<div class="col-md-8">
				    	{{ Form::text('label', null, array('class' => 'form-control', 'placeholder' => 'Task Name')) }}
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
				<?php $addontasks = OtherDetails::where('section_id', $sectionName->id )->get(); ?>
				@foreach ($addontasks as $addontask)
				<tr>
					<td colspan="2">{{$addontask->label}}</td>
					<td>
						<form method="POST" action="deladdtask"  id="myForm_{{ $addontask->id }}" name="myForm" style="display: -webkit-inline-box;">
							<input type="hidden" name="id" value="<?php echo $addontask->id ?>">
							<button type="button" onclick="hello( {{{ $addontask->id }}})" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete"><span class="glyphicon glyphicon-trash"></span></button>
						</form>
					</td>
				</tr> 
				@endforeach
			@endif
			<!-- Displays the defined tasks of the workflow -->
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="25%">ACTION</th>
			</tr>
			<?php $sections = DB::table('tasks')->where('wf_id','1')->get(); ?>

			@foreach($sections as $section)
				@if($section->section_id == 3)
					<?php $d_id=$section->designation_id; ?>
					<tr>
						<td> {{{ $section->taskName }}} </td>
						<td>
							<?php $desigs = DB::table('designation')->where('id', $d_id)->get(); ?>
							@if($d_id!=0)
								@foreach ($desigs as $desig)
									<div class="mode1" id="insert_{{$section->id}}">
										{{$desig->designation }}
									</div>
								@endforeach
							@else 
								<div class="mode1" id="insert_{{$section->id}}">None</div>
							@endif
							<?php $desigs = DB::table('designation')->get();	?>
							<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
								<select name ="designa" class = "form-control mode2 edit-text" style="width:100%">
									<option value=0>None                                 </option>
									@foreach ($desigs as $desig)
										<option value="{{$desig->id}}">{{$desig->designation}}</option>
									@endforeach
								</select>
								<input type="hidden" value="{{$section->id}}" name ="task_id">
							</form>
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
		<?php $sectionName = Section::find('4'); ?>
		<h3 class="panel-title"> {{{ strtoupper($sectionName->sectionName) }}} </h3>
	</div>
	<div class="panel-body">
		
		<!-- Displays the tasks added through the add new task form -->
		<div id="office-create-form" class="well div-form">
		    	{{ Form::open(['url'=>'addtask'], 'POST', array('role' => 'form')) }}
			    	<div class="col-md-8">
				    	{{ Form::text('label', null, array('class' => 'form-control', 'placeholder' => 'Task Name')) }}
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
				<?php $addontasks = OtherDetails::where('section_id', $sectionName->id )->get(); ?>
				@foreach ($addontasks as $addontask)
					<tr>
						<td colspan="2">{{$addontask->label}}</td>
						<td>
							<form method="POST" action="deladdtask"  id="myForm_{{ $addontask->id }}" name="myForm" style="display: -webkit-inline-box;">
								<input type="hidden" name="id" value="<?php echo $addontask->id ?>">
								<button type="button" onclick="hello( {{{ $addontask->id }}})" class="btn btn-danger" data-toggle="modal" data-target="#confirmDelete"><span class="glyphicon glyphicon-trash"></span></button>
							</form>
						</td>
					</tr> 
				@endforeach
			@endif
			<!-- Displays the defined tasks of the workflow -->
			<tr>
				<th class="workflow-th" width="25%">TASK</th>
				<th class="workflow-th" width="45%">DESIGNATION ASSIGNED</th>
				<th class="workflow-th" width="25%">ACTION</th>
			</tr>
			<?php $section1 = DB::table('tasks')->where('wf_id','1')->get(); ?>
			@foreach($sections as $section)
				@if($section->section_id == 4)
					<?php $d_id=$section->designation_id; ?>
					<tr>
						<td> {{{ $section->taskName }}} </td>
						<td>
							<?php $desigs = DB::table('designation')->where('id', $d_id)->get();	?>
							@if($d_id!=0)
								@foreach ($desigs as $desig)
									<div class="mode1" id="insert_{{$section->id}}">
										{{$desig->designation }}
									</div>
								@endforeach
							@else 
								<div class="mode1" id="insert_{{$section->id}}">None</div>
							@endif
							<?php $desigs = DB::table('designation')->get();	?>
							<form class="form ajax" action="/workflow/submit/{{$section->id}}" data-replace="#insert_{{$section->id}}" method="post" role="form" class="form-inline">
								<select name ="designa" class = "form-control mode2 edit-text" style="width:100%">
									<option value=0>None</option>
									@foreach ($desigs as $desig)
										<option value="{{$desig->id}}">{{$desig->designation}}</option>
									@endforeach
								</select>
								<input type="hidden" value="{{$section->id}}" name ="task_id">
							</form>
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