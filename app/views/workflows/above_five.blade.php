@extends('layouts.default')

@section('content')
	<h1 class="page-header">Large Value Procurement (Above P500,000)</h1>

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
				<?php 
					$section1 = DB::table('tasks')->where('wf_id','3')->get();	
				?>
				@foreach($section1 as $section)
					@if($section->section_id == 1)
						<tr>
							<td> {{{ $section->taskName }}} </td>
							<td></td>
							<td>
								<center>
										<button class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></button>
										<button class="btn btn-info"><span class="glyphicon glyphicon-list-alt"></span></button>
								</center>
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
				@foreach($section1 as $section)
					@if($section->section_id == 2)
						<tr>
							<td> {{{ $section->taskName }}} </td>
							<td></td>
							<td>
								<center>
										<button class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></button>
										<button class="btn btn-info"><span class="glyphicon glyphicon-list-alt"></span></button>
								</center>
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
				@foreach($section1 as $section)
					@if($section->section_id == 3)
						<tr>
							<td> {{{ $section->taskName }}} </td>
							<td></td>
							<td>
								<center>
										<button class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></button>
										<button class="btn btn-info"><span class="glyphicon glyphicon-list-alt"></span></button>
								</center>
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
				@foreach($section1 as $section)
					@if($section->section_id == 4)
						<tr>
							<td> {{{ $section->taskName }}} </td>
							<td></td>
							<td>
								<center>
										<button class="btn btn-success"><span class="glyphicon glyphicon-edit"></span></button>
										<button class="btn btn-info"><span class="glyphicon glyphicon-list-alt"></span></button>
								</center>
							</td>
						</tr>
					@endif
				@endforeach
			</table>
		</div>
	</div>
@stop