<?php 
	$section1 = DB::table('tasks')->where('wf_id',$wfName->work_id)->where('section_id','1')->get();
	$section2 = DB::table('tasks')->where('wf_id',$wfName->work_id)->where('section_id','2')->get();
	$section3 = DB::table('tasks')->where('wf_id',$wfName->work_id)->where('section_id','3')->get();
	$section4 = DB::table('tasks')->where('wf_id',$wfName->work_id)->where('section_id','4')->get();
?>
<div class="panel panel-success">
	<div class="panel-body">
		<table border="1" class="proc-details">
			<tr>
				<td class="proc-headers" colspan="5">
					<h4>{{{ strtoupper($secName1->sectionName) }}}</h4>
				</td>
			</tr>
			@foreach($section1 as $section)
				@if($section->taskName != 'PPMP CERTIFICATION')
				<tr>
					<td class="proc-headers" width="20%"><h5>{{{ $section->taskName }}}</h5></td>
					<td class="proc-data" width="20%"></td>
					<td class="proc-data" width="13%"></td>
					<td class="proc-data" width="27%"></td>
					<td class="proc-data" width="20%"></td>
				</tr>
				@else
				<tr>
					<td class="proc-headers"><h5>{{{ $section->taskName }}}</h5></td>
					<td class="proc-data"><center>YES&nbsp&nbsp{{ Form::radio('name','value', true) }} &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp NO:&nbsp&nbsp{{ Form::radio('name','value', true) }}</center></td>
					<td class="proc-headers" colspan="3"><h5>BY :</h5></td>
				</tr>
				<tr>
					<td class="proc-headers" width="20%"></td>
					<td class="proc-data" width="20%"><center>BY :</center></td>
					<td class="proc-data" width="13%"><center>DATE :</center></td>
					<td class="proc-data" width="27%"><center>NO. OF DAYS OF ACTION :</center></td>
					<td class="proc-data" width="20%"><center>REMARKS :</center></td>
				</tr>
				@endif
			@endforeach
		</table>
	</div>
</div>

<!-- SECTION 2 -->

<div class="panel panel-success">
	<div class="panel-body">
		<table border="1" class="proc-details">
			<tr>
				<td class="proc-headers" colspan="5">
					<h4>{{{ strtoupper($secName2->sectionName) }}}</h4>
				</td>
			</tr>
			<tr>
				<td class="proc-headers" width="20%"></td>
				<td class="proc-data" width="20%"><center>BY :</center></td>
				<td class="proc-data" width="13%"><center>DATE :</center></td>
				<td class="proc-data" width="27%"><center>NO. OF DAYS OF ACTION :</center></td>
				<td class="proc-data" width="20%"><center>REMARKS :</center></td>
			</tr>
			@foreach($section2 as $section)
				<tr>
					<td class="proc-headers" width="20%"><h5>{{{ $section->taskName }}}</h5></td>
					<td class="proc-data" width="20%"></td>
					<td class="proc-data" width="13%"></td>
					<td class="proc-data" width="27%"></td>
					<td class="proc-data" width="20%"></td>
				</tr>
			@endforeach
		</table>
	</div>
</div>

<!-- SECTION 3 -->

<div class="panel panel-success">
	<div class="panel-body">
		<table border="1" class="proc-details">
			<tr>
				<td class="proc-headers" colspan="5">
					<h4>{{{ strtoupper($secName3->sectionName) }}}</h4>
				</td>
			</tr>
			<tr>
				<td class="proc-headers" width="20%"></td>
				<td class="proc-data" width="20%"><center>BY :</center></td>
				<td class="proc-data" width="13%"><center>DATE :</center></td>
				<td class="proc-data" width="27%"><center>NO. OF DAYS OF ACTION :</center></td>
				<td class="proc-data" width="20%"><center>REMARKS :</center></td>
			</tr>
			@foreach($section3 as $section)
				<tr>
					<td class="proc-headers" width="20%"><h5>{{{ $section->taskName }}}</h5></td>
					<td class="proc-data" width="20%"></td>
					<td class="proc-data" width="13%"></td>
					<td class="proc-data" width="27%"></td>
					<td class="proc-data" width="20%"></td>
				</tr>
			@endforeach
		</table>
	</div>
</div>

<div class="panel panel-success">
	<div class="panel-body">
		<table border="1" class="proc-details">
			<tr>
				<td class="proc-headers" colspan="5">
					<h4>{{{ strtoupper($secName4->sectionName) }}}</h4>
				</td>
			</tr>
			<tr>
				<td class="proc-headers" width="20%"></td>
				<td class="proc-data" width="20%"><center>BY :</center></td>
				<td class="proc-data" width="13%"><center>DATE :</center></td>
				<td class="proc-data" width="27%"><center>NO. OF DAYS OF ACTION :</center></td>
				<td class="proc-data" width="20%"><center>REMARKS :</center></td>
			</tr>
			@foreach($section4 as $section)
				<tr>
					<td class="proc-headers" width="20%"><h5>{{{ $section->taskName }}}</h5></td>
					<td class="proc-data" width="20%"></td>
					<td class="proc-data" width="13%"></td>
					<td class="proc-data" width="27%"></td>
					<td class="proc-data" width="20%"></td>
				</tr>
			@endforeach
		</table>
	</div>
</div>