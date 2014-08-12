@extends('layouts.dashboard')

@section('header')
{{ HTML::style('date_picker/bootstrap-datetimepicker.min.css')}}
   {{ HTML::script('date_picker/bootstrap-datetimepicker.js') }}
   {{ HTML::script('date_picker/bootstrap-datetimepicker.fr.js') }}
   {{ HTML::style('css/datepicker.css')}}
   {{ HTML::script('js/bootstrap-datepicker.js') }}
{{ HTML::script('js/bootstrap.file-input.js') }} 
<script type="text/javascript">
function codeAddress() 
{
if(document.layers) document.layers['remarkd'].visibility="show";
if(document.getElementById) document.getElementById("remarkd").style.visibility="visible";
if(document.all) document.all.remarkd.style.visibility="visible";

if(document.layers) document.layers['formr'].visibility="hide";
if(document.getElementById) document.getElementById("formr").style.visibility="hidden";
if(document.all) document.all.formr.style.visibility="hidden";
}
window.onload = codeAddress;
</script>

<style type="text/css">
td{
   padding:5px 10px;
   vertical-align:top;
   word-break:break-word;
}
</style>
@stop

@section('content')

<?php
error_reporting(0);
$taskdetails_id=Session::get('taskdetails_id');
Session::forget('taskdetails_id');
$taskd =TaskDetails::find($taskdetails_id);
$task= Task::find($taskd->task_id);
$doc= Document::find($taskd->doc_id);
$purchase = Purchase::find($doc->pr_id);
$date_today = $date_today = date('Y-m-d H:i:s');
?>

{{Session::put('backTo',"task/$taskdetails_id");}}

<h2 class="pull-left">Task Details</h2>

<div class="pull-right options">

@if($taskd->status == "Active" && $taskd->dueDate > $date_today)
<a href="{{ URL::to('task/active') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
@elseif($taskd->status == "New")
<a href="{{ URL::to('task/new') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
@elseif($taskd->status == "Closed")
<a href="{{ URL::to('task/active') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
@else
<a href="{{ URL::to('task/overdue') }}" class="btn btn-default"><span class="glyphicon glyphicon-chevron-left"></span> Back</a>
@endif
</div>
<!--Trigger to Change Routing-->
{{Session::put('changeroute','change')}}

<!--End Trigger to Change Routing-->

<hr class="clear" />

<div class="panel panel-default">
<div class="panel-body task-body">

<table border=0 width="100%">
<tr>
<td>
<h3 class="pull-left">{{$task->taskName}}</h3>
<?php
if($taskd->status=="New")
{
?>	
{{ Form::open(['route'=>'accept_task']) }}
{{ Form::hidden('hide_taskid',$taskdetails_id) }}
{{ Form::submit('Accept Task', ['class' => 'btn btn-sm btn-primary accept-button', 'style' => 'margin-bottom: 10px'])}}     
{{ Form::close() }}
<?php
}
?>
<hr class="clear" />
</td>
</tr>

<tr> 
<td>
<span style="font-weight: bold">Description: </span><br/>
<p>{{$task->description}}</p>
</td>
<tr>

<tr> 
<td>
<span style="font-weight: bold">Control No. : </span><br/>
<p><?php echo str_pad($purchase->controlNo, 5, '0', STR_PAD_LEFT); ?></p>

</td>
<tr>

<tr> 
<td>
<span style="font-weight: bold">Project/Purpose: </span><br/>
<p><a href="{{ URL::to('purchaseRequest/vieweach/'.$purchase->id) }}" ><?php echo $purchase->projectPurpose; ?> </a></p>

</td>
<tr>
@if(Session::get('successchecklist'))
               <div class="alert alert-success"> {{ Session::get('successchecklist') }}</div> 
           @endif

           @if(Session::get('errorchecklist'))
               <div class="alert alert-danger"> {{ Session::get('errorchecklist') }}</div> 
           @endif

           {{Session::forget('successchecklist');}}
           {{Session::forget('errorchecklist');}}

<?php
if ($taskd->status=="Done")
{
Redirect::to('task/active');
}

if ($taskd->status=="Active")
{

$date_today =date('Y-m-d H:i:s');
?>

<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>

<input type="hidden" name ="by" value= "{{$name}}">
@if( $date_today > $taskd->dueDate )
<tr>
<td>
<span style="font-weight: bold">Due Date: </span><br/>
<p><font color="red">{{ $taskd->dueDate; }}</font></p>
</td>
</tr>
@else
<tr>
<td>
<span style="font-weight: bold">Due Date: </span><br/>
<p>{{ $taskd->dueDate; }}</p>
</td>
</tr>
@endif



@if($task->taskType=='certification')
{{Form::open(['url'=>'certification'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="by" value= "{{$name}}">
<tr> 
<td>
<span style="font-weight: bold">PPMP Certification: </span><br/>
<p>
<input type="radio" name="radio" value="yes" />&nbsp;&nbsp;Yes &nbsp;&nbsp;
                           <input type="radio" name="radio" value="no" />&nbsp;&nbsp;No<br />
                       </p>

</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
<input type="submit" class="btn btn-sm btn-success" value="Done">

</td>
</tr>
{{Form::close()}}
@if($tasks->taskType == "dateonly")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'dateonly', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskd->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchase->id}}" );>
                                
                                <td class="edit-pr-input"> 
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">

                                        <input type="text" class="form-control" name="dateFinished" id="dateFinished" style="text-align: center; width:100%" 
                                        
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                    </div>
                                </td>
                                
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
@endif


@elseif($task->taskType=='posting')
{{Form::open(['url'=>'posting'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
$birth = new DateTime($taskd->dateReceived); 
$today = new DateTime(); 
$diff = $birth->diff($today); 
$aDays= $diff->format('%d');

$converteddate = $today->format('m/d/y');
?>
<input type="hidden" name ="by" value= "{{$name}}">
<input type="hidden" name="date" value="{{$converteddate}}">
<tr> 
<td>
<span style="font-weight: bold">Reference Number: </span><br/>
<p>
<input type="text" name="referenceno"  class="form-control" maxlength="100" width="80%" maxlength="100" style="margin-top: 10px;" placeholder="Enter Reference Number">
                       </p>

</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }} 
</td>
</tr>
{{Form::close()}}

@elseif($task->taskType=='supplier')
{{Form::open(['url'=>'supplier'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="by" value= "{{$name}}">

<tr> 
<td>
<span style="font-weight: bold">Supplier: </span><br/>
<p>
<input type="text" name="supplier"  class="form-control" maxlength="100" width="80%" placeholder="Enter supplier" style="margin-top: 10px;">
                       </p>
<span style="font-weight: bold">Amount: </span><br/>
<p>
<input type="decimal" name="amount"  class="form-control" maxlength="12" width="80%" placeholder="Enter amount" style="margin-top: 10px;">
                       </p>
</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}	
</td>
</tr>
{{Form::close()}}
@elseif($task->taskType=='cheque')
{{Form::open(['url'=>'cheque'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="by" value= "{{$name}}">
<tr> 
<td>
<span style="font-weight: bold">Cheque Amount: </span><br/>
<p>
<input type="decimal" name="amt"  class="form-control" maxlength="12" width="80%" placeholder="Enter cheque amount" style="margin-top: 10px;">
                       </p>
<span style="font-weight: bold">Cheque Number: </span><br/>
<p>
<input type="decimal" name="amount"  class="form-control" maxlength="12" width="80%" placeholder="Enter amount" style="margin-top: 10px;">
                       </p>
<span style="font-weight: bold">Cheque Date: </span><br/>
<p>
<?php 
                               $today = date("m/d/y");
                               ?>
                               <input class="datepicker" size="16" type="text" name="date" class="form-control" value="{{$today}}" width="100%" placeholder="Enter cheque date">
                               <span class="add-on"><i class="icon-th"></i></span>
                       </p>
</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}	
</td>
</tr>
{{Form::close()}}	
@elseif($task->taskType=='conference')
{{Form::open(['url'=>'conference'], 'POST')}}
<tr> 
<td>
<span style="font-weight: bold">Conference Date: </span><br/>
<p>
<?php 
                           $today = date("m/d/y");
                           ?>
                           <input class="datepicker" size="16" type="text" name="date" class="form-control" value="{{$today}}" width="100%"  style="margin-top: 10px;">
                           <span class="add-on"><i class="icon-th"></i></span>
                       </p>
</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}	
</td>
</tr>
{{Form::close()}}	

@elseif($task->taskType=='published')

{{Form::open(['url'=>'published'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="by" value= "{{$name}}">
<tr> 
<td width="50%">
<span style="font-weight: bold">Date Published: </span><br/>
<p>
<?php 
                           $today = date("m/d/y");
                           ?>
                           <input class="datepicker" size="16" type="text" name="datepublished" class="form-control" value="{{$today}}" width="100%"  style="margin-top: 10px;">
                           <span class="add-on"><i class="icon-th"></i></span>
                       </p>
<span style="font-weight: bold">End Date: </span><br/>
<p>
<?php 
                           $today = date("m/d/y");
                           ?>
                           <input class="datepicker" size="16" type="text" name="datepublished" class="form-control" value="{{$today}}" width="100%"  style="margin-top: 10px;">
                           <span class="add-on"><i class="icon-th"></i></span>
                       </p>
</td>
</tr>
<tr>
<td>
<span style="font-weight: bold">Posted By: </span><br/>
<p>
<input type="text" name="by"  placeholder="Enter name" class="form-control" maxlength="100" width="80%"  style="margin-top: 10px;">
                       </p>
</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}	
</td>
</tr>	
{{Form::close()}}
@elseif($task->taskType=='philgeps')

{{Form::open(['url'=>'philgeps'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="by" value= "{{$name}}">
<tr> 

                                <td >
                                   <span style="font-weight: bold"> Reference No.:</span><br/>
                                    <input type="text" name="referenceno"  class="form-control" maxlength="100" width="80%" maxlength="100"
                                    value="<?php
                                    if (NULL!=Input::old('referenceno'))
                                    echo Input::old('referenceno');
                                    ?>"
                                    >
                                </td>
<td width="50%">
<span style="font-weight: bold">Date Published: </span><br/>
<p>
<?php 
                           $today = date("m/d/y");
                           ?>
                           <input class="datepicker" size="16" type="text" name="datepublished" class="form-control" value="{{$today}}" width="100%"  style="margin-top: 10px;">
                           <span class="add-on"><i class="icon-th"></i></span>
                       </p>
<span style="font-weight: bold">End Date: </span><br/>
<p>
<?php 
                           $today = date("m/d/y");
                           ?>
                           <input class="datepicker" size="16" type="text" name="datepublished" class="form-control" value="{{$today}}" width="100%"  style="margin-top: 10px;">
                           <span class="add-on"><i class="icon-th"></i></span>
                       </p>
</td>
</tr>
<tr>
<td>
<span style="font-weight: bold">Posted By: </span><br/>
<p>
<input type="text" name="by"  placeholder="Enter name" class="form-control" maxlength="100" width="80%"  style="margin-top: 10px;">
                       </p>
</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }} 
</td>
</tr> 
{{Form::close()}}
@elseif($task->taskType=='documents')
{{Form::open(['url'=>'documents'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="by" value= "{{$name}}">
<tr> 
<td width="50%">
<span style="font-weight: bold">Eligibility Documents: </span><br/>
<p>
<?php 
                           $today = date("m/d/y");
                               ?>
                               <input class="datepicker" size="16" type="text" name="date" class="form-control" value="{{$today}}" width="100%" style="margin-top: 10px;">
                               <span class="add-on"><i class="icon-th"></i></span>
                       </p>
<span style="font-weight: bold">Date of Bidding: </span><br/>
<p>
<input class="datepicker" size="16" type="text" name="biddingdate" class="form-control" value="{{$today}}" width="100%" style="margin-top: 10px;">
<span class="add-on"><i class="icon-th"></i></span>
                       </p>
</td>
</tr>
<tr>
<td>
<span style="font-weight: bold">Checked By: </span><br/>
<p>
<input type="text" name="by"  class="form-control" maxlength="100" width="80%" placeholder="Enter name" style="margin-top: 10px;">
                       </p>
</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}	
</td>
</tr>
{{Form::close()}}	
@elseif($task->taskType=='evaluation')
{{Form::open(['url'=>'evaluations'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="by" value= "{{$name}}">
<tr> 
<td width="50%">
<span style="font-weight: bold">Date: </span><br/>
<p>
<?php 
                           $today = date("m/d/y");
                               ?>
                               <input class="datepicker" size="16" type="text" name="date" class="form-control" value="{{$today}}" width="100%" style="margin-top: 10px;">
                               <span class="add-on"><i class="icon-th"></i></span>
                       </p>
<span style="font-weight: bold">No. of Days Accomplished: </span><br/>
<p>
<input type="number" name="noofdays"  class="form-control" maxlength="100" width="80%" placeholder="Enter no. of days" style="margin-top: 10px;">
                       </p>
</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}	
</td>
</tr>	
{{Form::close()}}
@elseif($task->taskType=='contract' || $task->taskType=='meeting')
{{Form::open(['url'=>'contractmeeting'], 'POST')}}
<?php
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="by" value= "{{$name}}">
<tr> 
<td width="50%">
<span style="font-weight: bold">
@if($task->taskType=='contract') 
Notice of Award Date: 
@else 
Notice to Proceed
@endif
</span><br/>
<p>
<?php 
                               $today = date("m/d/y");
                               ?>
                               <input class="datepicker" size="16" type="text" name="date" class="form-control" value="{{$today}}" width="100%" style="margin-top: 10px;">
                               <span class="add-on"><i class="icon-th"></i></span>
                       </p>
<span style="font-weight: bold">No. of Days Accomplished: </span><br/>
<p>
<input type="number" name="noofdays"  class="form-control" maxlength="100" width="80%" placeholder="Enter no. of days accomplished" style="margin-top: 10px;">
                       </p>
                       <span style="font-weight: bold">
                       	@if($task->taskType=='contract') 
                       	Contract Agreement: 
                       	@else
                       	Minutes of Meeting:
                       	@endif
                       </span><br/>
<p>
<input type="text" name="contractmeeting"  class="form-control" maxlength="100" width="80%" placeholder="@if($task->taskType=='contract') Enter contract agreement @else Enter minutes of meeting @endif" style="margin-top: 10px;">
                       </p>
</td>
</tr>
<tr>
<td>
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}	
</td>
</tr>
{{Form::close()}}	
@endif

<?php } 

else 
{ 
?>

<tr>
<td>
<span style="font-weight: bold">Max Duration: </span><br/>
{{ $task->maxDuration }}
</td>
</tr>

<?php 
}
?>


<tr>
<td>


@if($task->taskType=='normal')
@if($taskd->status!="New")
{{ Form::open(array('method' => 'post', 'url' => 'done', 'id'=>"taskform"))}}
<input type ="hidden" name="task_id" value="{{$task->id}}">
<input type ="hidden" name="doc_id" value="{{$doc->id}}">
<input type ="hidden" name="pr_id" value="{{$purchase->id}}">
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
{{ Form::close() }}
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
if ($taskd->remarks==NULL || $taskd->remarks==' ' )
{
?>
No remark.
<?php
}
?>
</p>
</div>
<br>
<button type="button" class="btn btn-success " onclick="doneauto('taskform')" id="hidebtn">Done</button>

<div id ="formr">
{{ Form::open(['url'=>'remarks'], 'POST') }}
@if($taskd->remarks==NULL)
{{ Form::textarea('remarks','', array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical', 'id'=>'taskform')) }}
@else
{{ Form::textarea('remarks',$taskd->remarks, array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical', 'id'=>'taskform' )) }}

@endif
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<br>
<input type="button"  onclick="hideRemarks()"  value="Cancel" class='btn btn-default' />
&nbsp;
{{ Form::submit('Save',array('class'=>'btn btn-warning')) }}
&nbsp;
<button type="button" class="btn btn-success " onclick="doneauto()" >Done</button>
{{ Form::close() }}
</div>
<br>
&nbsp;



@endif

@endif
@if($task->taskType=='dateby')
@if($taskd->status!="New")
{{ Form::open(array('method' => 'post', 'url' => 'done', 'id'=>"taskform")) }}
<?php

$birth = new DateTime($taskd->dateReceived); 
$today = new DateTime(); 
$diff = $birth->diff($today); 
$aDays= $diff->format('%d');

$converteddate = $today->format('m/d/y');

$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="assignee" value= "{{$name}}">
<input type ="hidden" name="dateFinished" value="{{$converteddate}}">
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}
{{ Form::close() }}
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
if ($taskd->remarks==NULL || $taskd->remarks==' ')
{
?>
No remark.
<?php
}
?>
</p>
</div>
<br>
<button type="button" class="btn btn-success " onclick="doneauto('taskform')" id="hidebtn">Done</button>

<div id ="formr">
{{ Form::open(['url'=>'remarks'], 'POST') }}
@if($taskd->remarks==NULL)
{{ Form::textarea('remarks','', array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical', 'id'=>'taskform')) }}
@else
{{ Form::textarea('remarks',$taskd->remarks, array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical', 'id'=>'taskform' )) }}

@endif
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<br>
<input type="button"  onclick="hideRemarks()"  value="Cancel" class='btn btn-default' />
&nbsp;
{{ Form::submit('Save',array('class'=>'btn btn-warning')) }}
&nbsp;
<button type="button" class="btn btn-success " onclick="doneauto()" >Done</button>
{{ Form::close() }}
</div>
<br>
&nbsp;


@endif

@endif
@if($task->taskType=='datebyremark')
@if($taskd->status!="New")
{{ Form::open(array('method' => 'post', 'url' => 'done', 'id'=>"taskform")) }}
<?php

$birth = new DateTime($taskd->dateReceived); 
$today = new DateTime(); 
$diff = $birth->diff($today); 
$aDays= $diff->format('%d');

$converteddate = $today->format('m/d/y');

$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
?>
<input type="hidden" name ="assignee" value= "{{$name}}">
<input type ="hidden" name="dateFinished" value="{{$converteddate}}">
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
{{ Form::submit('Done',array('class'=>'btn btn-sm btn-success')) }}
{{ Form::close() }}
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
if ($taskd->remarks==NULL || $taskd->remarks==' ')
{
?>
No remark.
<?php
}
?>
</p>
</div>
<br>
<button type="button" class="btn btn-success " onclick="doneauto('taskform')" id="hidebtn">Done</button>

<div id ="formr">
{{ Form::open(['url'=>'remarks'], 'POST') }}
@if($taskd->remarks==NULL)
{{ Form::textarea('remarks','', array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical', 'id'=>'taskform')) }}
@else
{{ Form::textarea('remarks',$taskd->remarks, array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical', 'id'=>'taskform' )) }}

@endif
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<br>
<input type="button"  onclick="hideRemarks()"  value="Cancel" class='btn btn-default' />
&nbsp;
{{ Form::submit('Save',array('class'=>'btn btn-warning')) }}
&nbsp;
<button type="button" class="btn btn-success " onclick="doneauto()" >Done</button>
{{ Form::close() }}
</div>
<br>
&nbsp;


@endif

@endif
<hr class="clear" />
</td>
</tr>


</table>

<br/>
<br/>
@if ($taskd->status=="Active")


<!--Upload Image-->
<?php


?>
{{ Form::open(array('url' => 'taskimage', 'files' => true, 'id'=>'createform'), 'POST') }}
<label class="create-label">Related files:</label>
           <div class="panel panel-default fc-div">
               <div class="panel-body" style="padding: 5px 20px;">
               
                     <!--Image Module-->
                 <?php
                        
                        $doc_id= $doc->id;
                    ?>

                   <br>
                   <input name="file[]" type="file"  multiple title="Select image to attach" onchange="autouploadsaved()"/>
                   <br>
                   <br>
                   <input name="doc_id" type="hidden" value="{{ $doc->id }}">
                     @if(Session::get('imgsuccess'))
                       <div class="alert alert-success"> {{ Session::get('imgsuccess') }}</div> 
                   @endif

                   @if(Session::get('imgerror'))
                       <div class="alert alert-danger"> {{ Session::get('imgerror') }}</div> 
                   @endif
           
<input type="hidden" name="id" value="{{$doc->pr_id}}">
                <?php
                error_reporting(0);
                 $attachmentc = DB::table('attachments')->where('doc_id', $doc_id)->count();
                 if ($attachmentc!=0)
         
                    $attachments = DB::table('attachments')->where('doc_id', $doc_id)->get();  
                    $srclink="uploads\\";
                ?>
                <br>
                <table>
                
                <?php $count = 1; ?>
                @foreach ($attachments as $attachment) 
                <tr>  
                    <td>  
                        <a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="roadtrip">
                        {{$attachment->data}}
                        </a>
                    </td>
                    <td>
                    &nbsp;
                    </td>
                    <td>
                   
                            <input type="hidden" name="hide" value="{{$attachment->id}}">
                        <button type="button" onclick="delimage({{$count}})" ><span class="glyphicon glyphicon-trash"></span></button>
      
                        <?php $count+=1; ?>
                    </td>
                 </tr>
                @endforeach
                </table>
            <!-- End Image Module-->
          
                
                   <br>
                   <br>
                   
                   {{Session::forget('imgerror');}}
                   {{Session::forget('imgsuccess');}}

          
           </div>
           {{Form::close()}}
           <!--End Upload Image-->
           <?php
                 $attachmentc = DB::table('attachments')->where('doc_id', $doc_id)->count();
                 if ($attachmentc!=0)
                 
                    $attachments = DB::table('attachments')->where('doc_id', $doc_id)->get();  
                    $srclink="uploads\\";
                ?>
        
                <?php $count=1; ?>
                @foreach ($attachments as $attachment) 
        
           
                     
                        {{ Form::open(array('method' => 'post', 'url' => 'delimage', 'id'=>"form_$count")) }}
                            <input type="hidden" name="hide" value="{{$attachment->id}}">
                        {{Form::close()}}
             
       
                 <?php $count+=1;  ?>
                @endforeach
@endif
<br/>
</div>
</div>
@stop

@section('footer')
<script type="text/javascript">
$('input[type=file]').bootstrapFileInput();
   $('.file-inputs').bootstrapFileInput();
function isNumberKey(evt)
{s
var charCode = (evt.which) ? evt.which : event.keyCode
if(charCode == 44 || charCode == 46)
return true;

if (charCode > 31 && (charCode < 48 || charCode > 57))
return false;

return true;
}
function show(){
  
  document.getElementById("hidebtn").style.visibility="hidden";

if(document.layers) document.layers['formr'].visibility="show";
if(document.getElementById) document.getElementById("formr").style.visibility="visible";
if(document.all) document.all.formr.style.visibility="visible";

if(document.layers) document.layers['remarkd'].visibility="hide";
if(document.getElementById) document.getElementById("remarkd").style.visibility="hidden";
if(document.all) document.all.remarkd.style.visibility="hidden";
}
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

   function fix_formatDateRec()
   {
       document.getElementById('disabled_datetimeDateRec').value = document.getElementById('dtp_input2').value;
        
   }

   function fix_format()
   {
       document.getElementById('disabled_datetime').value = document.getElementById('dtp_input1').value;
   }

function fix_format2()
{
   var counter = 0;
   while(counter != 100)
   {
       counter++;
       var name = "disabled_datetime2" + counter;
       var name2 = "dtp_input2" + counter;
       document.getElementById(name).value =
       document.getElementById(name2).value;
   }
}

function hideRemarks()
{
   document.getElementById("hidebtn").style.visibility="visible";
if(document.layers) document.layers['formr'].visibility="hide";
if(document.getElementById) document.getElementById("formr").style.visibility="hidden";
if(document.all) document.all.formr.style.visibility="hidden";

if(document.layers) document.layers['remarkd'].visibility="show";
if(document.getElementById) document.getElementById("remarkd").style.visibility="visible";
if(document.all) document.all.remarkd.style.visibility="visible";
}

$('.datepicker').datepicker();
function delimage(value)
    {
      //alert('form_'+value);
      var formname= "form_"+value;
      document.getElementById(formname).submit();
    }
function doneauto()
    {
      //alert('form_'+value);
      var formname= "taskform";
      document.getElementById(formname).submit();
    }
    function autouploadsaved(value)
    {
    var formname= "createform";
    var text= "/autouploadsaved";
    

    $("#createform").attr('action', text); 
    document.getElementById(formname).submit();
    }


   </script>
@stop