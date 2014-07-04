@extends('layouts.dashboard')
@section('header')

        <script type="text/javascript">


 function codeAddress() {
         if(document.layers) document.layers['remarkd'].visibility="show";
if(document.getElementById) document.getElementById("remarkd").style.visibility="visible";
if(document.all) document.all.remarkd.style.visibility="visible";

if(document.layers) document.layers['formr'].visibility="hide";
if(document.getElementById) document.getElementById("formr").style.visibility="hidden";
if(document.all) document.all.formr.style.visibility="hidden";

        }
        window.onload = codeAddress;
</script>
@stop
@section('content')
	<h1 class="pull-left">Task Details</h1>
	<div class="pull-right options">
		<a href="{{ URL::previous() }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
	</div>
<?php
$taskdetails_id=Session::get('taskdetails_id');
Session::forget('taskdetails_id');
$taskd =TaskDetails::find($taskdetails_id);
$task= Task::find($taskd->task_id);
$doc= Document::find($taskd->doc_id)->first();
$purchase = Purchase::find($doc->pr_id);

?>
	<hr class="clear" />

	<div class="panel panel-default">
  		<div class="panel-body task-body">
		    <h3 class="pull-left">{{$task->taskName}}</h3>
		    <div class="pull-right" style="margin-top: 10px;">
		<?php
if($taskd->status=="New"){
		?>		{{Form::button('Accept Task', ['class' => 'btn btn-primary', 'style' => 'margin-bottom: 10px'])}}
			<?php
}
			?>
			</div>
		    <hr class="clear" />
		    <span style="font-weight: bold">Description: </span><br/>
			<p>
				{{$task->description}}</p>
			<br/>
			<span style="font-weight: bold">Purchase Request ID-Name: 
			</span><br/>
			<p><a href="{{ URL::to('purchaseRequest/vieweach/'.$purchase->id) }}" ><?php echo $purchase->id."-".$purchase->projectPurpose; ?> </a></p>
			<br/>
			<?php 
if ($taskd->status!="New"){
			?>
			<span style="font-weight: bold">Due Date: </span><br/>
			<p><?php


	
$date = new DateTime($taskd->dateReceived);
$date->add(new DateInterval('P'.$taskd->daysOfAction.'D'));
echo date_format($date, 'jS F Y');
	}
	else{
		?>
			<span style="font-weight: bold">Max Duration: </span><br/>
			<p><?php

echo $taskd->daysOfAction." days";
	}		

	?></p>
			<br/>
			

			<p style="font-weight: bold">Remarks: </p>



<div id="remarkd" onclick="show()">
<p>
<?php
echo $taskd->remarks;
if ($taskd->remarks==NULL)
{
?>
{{ Form::open(['url'=>'remarks'], 'POST') }}
			    {{ Form::textarea('remarks','', array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255')) }}
			    <div class="pull-right">
				    {{ link_to( 'task/active', 'Cancel', array('class'=>'btn btn-sm btn-default remarks-btn') ) }}
				    {{ Form::submit('Submit',array('class'=>'btn btn-sm btn-success remarks-btn')) }}
				</div>
			{{ Form::close() }}
<?php
}
?>
</p>
</div>

<div id ="formr">
{{ Form::open(['url'=>'remarks'], 'POST') }}
			    {{ Form::textarea('remarks','', array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255')) }}
			    <div class='pull-right'>
				    {{ link_to( 'task/active', 'Cancel', array('class'=>'btn btn-sm btn-default remarks-btn') ) }}
				    {{ Form::submit('Submit',array('class'=>'btn btn-sm btn-success remarks-btn')) }}
				</div>
			{{ Form::close() }}
</div>
<hr class="clear" />
<?php 
if ($task->taskType==0){
?>
			{{ link_to( '#', 'Task Done', array('class'=>'btn btn-primary remarks-btn') ) }}
<?php
}
else{
?>
			{{ link_to( '#', 'Approve', array('class'=>'btn btn-primary remarks-btn') ) }}
			{{ link_to( '#', 'Reject', array('class'=>'btn btn-danger remarks-btn') ) }}
			

<?php
}

?>	
		</div>
	</div>
@stop

@section('footer')

    
        <script type="text/javascript">
function show(){
        if(document.layers) document.layers['formr'].visibility="show";
if(document.getElementById) document.getElementById("formr").style.visibility="visible";
if(document.all) document.all.formr.style.visibility="visible";

if(document.layers) document.layers['remarkd'].visibility="hide";
if(document.getElementById) document.getElementById("remarkd").style.visibility="hidden";
if(document.all) document.all.remarkd.style.visibility="hidden";


}</script>
@stop
