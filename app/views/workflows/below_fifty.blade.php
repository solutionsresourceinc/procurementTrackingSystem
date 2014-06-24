@extends('layouts.default')

@section('content')
	<h1 class="page-header">Small Value Procurement (Below P50,000)</h1>

	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">A. PURCHASE REQUEST</h3>
		</div>
		<div class="panel-body">
			<table border="1" class="workflow-table">
				<tr>
					<th width="25%"><center>TASK</center></th>
					<th width="60%"><center>DESIGNATION ASSIGNED</center></th>
					<th width="15%"><center>ACTION</center></th>
				</tr>
				<tr>
					<td>1. PPMP CERTIFICATION</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>2. DATE OF PR</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>3. GSD</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>4. BUDGET / ACTG</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>5. PA</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>6. PGO</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>7. GSD</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
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
					<th width="25%"><center>TASK</center></th>
					<th width="60%"><center>DESIGNATION ASSIGNED</center></th>
					<th width="15%"><center>ACTION</center></th>
				</tr>
				<tr>
					<td>1. 3 RFQ CANVASS</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>2. ABSTRACT OF QUOTES</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>3. BAC RESOLUTION</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>4. NOTICE OF AWARD</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>	
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
					<th width="25%"><center>TASK</center></th>
					<th width="60%"><center>DESIGNATION ASSIGNED</center></th>
					<th width="15%"><center>ACTION</center></th>
				</tr>
				<tr>
					<td>1. GSD</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>2. ACTG</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>3. PA</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>4. PGO</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>5. BAC (DELIVERY)</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>	
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
					<th width="25%"><center>TASK</center></th>
					<th width="60%"><center>DESIGNATION ASSIGNED</center></th>
					<th width="15%"><center>ACTION</center></th>
				</tr>
				<tr>
					<td>1. BUDGET</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>2. ACCOUNTING</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>3. TREASURY</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>4. PA</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>
				<tr>
					<td>5. PGO</td>
					<td></td>
					<td><center><a href="{{ URL::to('') }}" class="btn btn-success">Edit Asignee</a></center></td>
				</tr>	
			</table>
		</div>
	</div>
@stop