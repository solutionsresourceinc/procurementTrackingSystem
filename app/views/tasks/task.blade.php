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

		?>	


   {{ Form::open(['route'=>'accept_task']) }}
                        {{ Form::hidden('hide_taskid',$taskdetails_id) }}
                        {{Form::submit('Accept Task', ['class' => 'btn btn-sm btn-primary accept-button', 'style' => 'margin-bottom: 10px'])}}     

                        {{ Form::close() }}

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

	echo $taskd->dueDate;
	}
	else{
		?>
			<span style="font-weight: bold">Max Duration: </span><br/>
			<p><?php

echo $task->maxDuration." days";
	}		

	?></p>
	
			<br>
			<p style="font-weight: bold">Remarks: </p>
<?php 

if (Session::get('errorremark'))
	echo  "<div class='alert alert-danger'>".Session::get('errorremark')."</div>";
if (Session::get('successremark'))
		echo  "<div class='alert alert-success'>".Session::get('successremark')."</div>";
Session::forget('errorremark');
Session::forget('successremark');
?>



<div id="remarkd" onclick="show()">
<p>
<?php
echo $taskd->remarks;
if ($taskd->remarks==NULL)
{
?>
No remark.
<?php
}
?>
</p>
</div>
<div id ="formr">
{{ Form::open(['url'=>'remarks'], 'POST') }}
			    {{ Form::textarea('remarks','', array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical')) }}
			    <input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
			    <div class='pull-right'>
				    {{ link_to( 'task/active', 'Cancel', array('class'=>'btn btn-sm btn-default remarks-btn') ) }}
				    {{ Form::submit('Submit',array('class'=>'btn btn-sm btn-success remarks-btn')) }}
				</div>
			{{ Form::close() }}
</div>
<hr class="clear" />
<?php 

	if ($taskd->status=="Active"){
?>
		{{ Form::open(['url'=>'done'], 'POST') }}
		<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
		<input type ="hidden" name="task_id" value="{{$task->id}}">
		<input type ="hidden" name="doc_id" value="{{$doc->id}}">
		<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
		{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}
		{{ Form::close() }}
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
