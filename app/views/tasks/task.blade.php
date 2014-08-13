@extends('layouts.dashboard')

@section('header')

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
    .tableTask td{
        padding:5px 10px;
        vertical-align:top;
        word-break:break-word;
      }
    </style>
@stop

@section('content')

  <?php
  //Initializers
  error_reporting(0);
  $taskdetails_id=Session::get('taskdetails_id');
  Session::forget('taskdetails_id');
  $taskd =TaskDetails::find($taskdetails_id);
  $task= Task::find($taskd->task_id);
  $doc= Document::find($taskd->doc_id);
  $purchase = Purchase::find($doc->pr_id);
    $purchaseToEdit = Purchase::find($doc->pr_id);
  $date_today = $date_today = date('Y-m-d H:i:s');
  //End Initializers
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
  
@if($taskd->status=="New")
{{ Form::open(['route'=>'accept_task']) }}
{{ Form::hidden('hide_taskid',$taskdetails_id) }}
{{ Form::submit('Accept Task', ['class' => 'btn btn-sm btn-primary accept-button', 'style' => 'margin-bottom: 10px'])}}     
{{ Form::close() }}
@endif

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
$assign_user=User::find(Auth::user()->id);
                        $name=$assign_user->lastname.", ".$assign_user->firstname;
}
?>

<input type="hidden" name ="by" value= "{{$name}}">
@if( $date_today > $taskd->dueDate )
<tr>
<td>
<span style="font-weight: bold">Due Date: </span><br/>
<p><font color="red">{{ $taskd->dueDate; }}</font></p>
</td>
</tr>
@elseif( $taskd->dueDate=="9999-01-01 00:00:00")
<tr>
<td>
<span style="font-weight: bold">Due Date: </span><br/>
<p>None</p>
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



<hr class="clear" />
</td>
</tr>


</table>

<br/>
<br/>
@if ($taskd->status=="Active")

<!--Tasks Forms-->


                  <table border='1' class='workflow-table' id='tableTask'>
<?php

                $tasks= Task::find($taskd->task_id);


                //Get Cursor Value
                $taskc= TaskDetails::find($taskd->id);
                //Queries
                $workflow= Workflow::find($docs->work_id);
                $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();

                    if ($tasks->taskType=="normal")
                    {
                        echo "<tr><th width='30%'></th>";
                      
                        echo "<th class='workflow-th' width='18%'>Date:</th>";
                        echo "<th class='workflow-th' width='12.5%'>Days of Action</th>";
                      
                        echo "</tr>";
                    }
                    if ($tasks->taskType=="datebyremark")
                    {
                        echo "<tr><th width='30%'></th>";
                        echo "<th class='workflow-th' >Date:</th>";
                       
                       
                        echo "</tr>";
                    }
                    if ($tasks->taskType=="dateby")
                    {
                        echo "<tr><th width='30%'></th>";
                        echo "<th class='workflow-th' colspan='2'>Date:</th>";
                        
                        echo "</tr>";
                    }
                    if ($tasks->taskType=="evaluation")
                    {
                        echo "<tr><th width='30%'></th>";
                        echo "<th class='workflow-th' colspan='2'>Date:</th>";
                        echo "<th class='workflow-th' colspan='2'>No. of Days Accomplished:</th>";
                        echo "</tr>";
                    }
                
                    //Cursor Open form 
                    //Displayer 
                    $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();   
?>
                        
                                @if(Session::get('successchecklist'))
                                <tr>
                                 <br>
                                    <div class="alert alert-success"> {{ Session::get('successchecklist') }}</div> 
                                  
                                </tr>
                                @endif
                                @if(Session::get('errorchecklist'))
                                <tr>
                                <br>
                                    <div class="alert alert-danger"> {{ Session::get('errorchecklist') }}</div> 
                                    
                                </tr>
                                @endif
                          <tr class='current-task'>
                        <?php
          
                        if ($tasks->taskType!="cheque"&&$tasks->taskType!="published"&&$tasks->taskType!="contract"&&$tasks->taskType!="meeting"&&$tasks->taskType!="rfq"&&$tasks->taskType!="documents"&&$tasks->taskType!="evaluation"&&$tasks->taskType!="preparation")
                        {
                            echo "<td>";
                            echo $tasks->taskName."</td>";
                        }
                    
                    //Task Forms

       $myForm = 'taskform' ;
                
                    ?>

                    @if($tasks->taskType == "normal")
                           

                            {{Form::open(['url'=>'checklistedit', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                            <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                    <input type ="hidden" name="assignee" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >
                       
                                <td class="edit-pr-input"> 
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                        <input type="text" class="form-control" onchange="changeDOA(this.value)" name="dateFinished" id="dateFinished" style="text-align: center; width:100%"
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                        else if ("0000-00-00 00:00:00"!=$taskc->dateFinished)
                                    
                                            echo "value='".$taskc->dateFinished."'";
                                
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        />
                                    </div>
                                </td>
                                <td class="edit-pr-input">

                                            @if($sectiondays==0&&$prdays==0)

                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($purchaseToEdit->dateReceived))}}">
                              
                                            @elseif("1899-11-30 00:00:00"==$taskc->dateFinished||"0000-00-00 00:00:00"==$taskc->dateFinished)


                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($taskc->updated_at))}}">

                                            @else

                                            <?php 
                                                $taskprev= TaskDetails::find($taskc->id-1); 

                                            ?>
                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($taskprev->dateFinished))}}">

                                            @endif

                                    <input id="daysOfAction" type="number" name="daysOfAction" class="form-control"  min="0"  width="100%" maxlength="12" 
                                    <?php
                                    if (NULL!=Input::old('daysOfAction'))
                                        echo "value='".Input::old('daysOfAction')."'";
                                    else if ('0'!=$taskc->daysOfAction)
                                        echo "value='".$taskc->daysOfAction."'";
                                    else
                                        echo "value='1'";
                                    ?>
                                    >
                                </td>
                              
                         
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "certification")
                          
                            {{Form::open(['url'=>'certification', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                               <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                 <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                <td class="edit-pr-input" colspan="2">
                                    <input type="radio" name="radio" value="yes" />&nbsp;&nbsp;Yes &nbsp;&nbsp;
                                    <input type="radio" name="radio" value="no" />&nbsp;&nbsp;No<br />
                                </td>
                                
                                      <input type ="hidden" name="by" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >

                        
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "posting")
                      
                            {{Form::open(['url'=>'posting', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                <td class="edit-pr-input">
                                    Reference No. : 
                                    <input type="text" name="referenceno"  class="form-control" maxlength="100" width="80%" maxlength="100"
                                    value="<?php
                                    if (NULL!=Input::old('referenceno'))
                                    echo Input::old('referenceno');
                                    else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                    ?>"
                                    >
                                </td>
                                <td class="edit-pr-input"> 
                                    Date: 
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                        <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" 
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                        else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                    </div>
                                </td>
                                       <input type ="hidden" name="by" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >

                         
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "supplier")
                      
                            {{Form::open(['url'=>'supplier', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                <td class="edit-pr-input" colspan="2">
                                    <input type="text" name="supplier"  class="form-control" maxlength="100" width="80%" placeholder="Enter supplier"
                                     value="<?php
                                    if (NULL!=Input::old('supplier'))
                                    echo Input::old('supplier');
                                    else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                    ?>"

                                    >
                                </td>
                                
                                <td class="edit-pr-input" colspan="2">
                                    <input type="decimal" name="amount"  id="amount" class="form-control" maxlength="12" width="80%" placeholder="Enter amount" onkeypress="return isNumberKey(event)" onchange="checklist_changeAmount(this.id,this.value)"
                                     value="<?php
                                    if (NULL!=Input::old('amount'))
                                    echo Input::old('amount');
                                     else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                    ?>"
                                    >
                                </td>

                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "cheque")
                          
                            {{Form::open(['url'=>'cheque', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                <td class="edit-pr-input" colspan="2">
                    
                                    <input type="decimal" name="amt"  id="amt" class="form-control" maxlength="12" width="80%" placeholder="Enter cheque amount" onkeypress="return isNumberKey(event)" onchange="checklist_changeAmount(this.id,this.value)"
                                     value="<?php
                                    if (NULL!=Input::old('amt'))
                                    echo Input::old('amt');
                                     else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                    ?>"
                                    >
                                </td>
                                <td class="edit-pr-input" colspan="2">
                                    
                                    <input type="decimal" name="num"  class="form-control" maxlength="12" width="80%" placeholder="Enter cheque number"
                                     value="<?php
                                    if (NULL!=Input::old('num'))
                                    echo Input::old('num');
                                 else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                    ?>"
                                    >
                                </td>
                                <td class="edit-pr-input" colspan="2">
                               
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                        <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" 
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom3)
                                        echo "value='".$taskc->custom3."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        />
                                    </div>
                                </td>
                                

                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "published")
                        
                            {{Form::open(['url'=>'published', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                    <td> </td>
                                    <th class='workflow-th' width="18%">Date Published:</th>
                                    <th class='workflow-th' width="18%">End Date:</th>
                                    <th class='workflow-th' colspan="2">Posted By:</th>

                                </tr>
                                <tr class="@if($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0) current-task @endif">
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="datepublished" id="datepublished" style="text-align: center; width:100%"
                                            
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                             />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="enddate" id="enddate" style="text-align: center; width:100%" 
                                            
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                             />
                                        </div>
                                    </td>
                                            <input type ="hidden" name="by" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >
                          
                                
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"
                                    > 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "philgeps")
      
                            {{Form::open(['url'=>'philgeps', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                 
                                </tr>
                                <tr class="@if($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0) current-task @endif">
                                 
                                    <td class="edit-pr-input">
                                    Reference No.:
                                    <input type="text" name="referenceno"  class="form-control" maxlength="100" width="80%" maxlength="100"
                                    <?php
                                    if (NULL!=Input::old('referenceno'))
                                    echo "value='".Input::old('referenceno')."'";
                                 else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                    ?>
                                    >
                                </td>
                                    <td>
                                        Date Published:
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="datepublished" id="datepublished" style="text-align: center; width:100%"
                                            
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                             />
                                        </div>
                                    </td>
                                    <td>
                                        End Date:
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="enddate" id="enddate" style="text-align: center; width:100%" 
                                            
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom3)
                                        echo "value='".$taskc->custom3."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                             />
                                        </div>
                                    </td>
                                            <input type ="hidden" name="by" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >
                                
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"
                                    > 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "documents")
                   
                            {{Form::open(['url'=>'documents', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                    <td> </td>
                                    <th class='workflow-th'>Eligibility Documents:</th>
                                    <th class='workflow-th'>Date of Bidding:</th>
                                    <th class='workflow-th' colspan="2">Checked By:</th>
                                </tr>
                                <tr class="@if($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0) current-task @endif">
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" 
                                            
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                            />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="biddingdate" id="biddingdate" style="text-align: center; width:100%" 
                                           
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                            />
                                        </div>
                                    </td>
                                            <input type ="hidden" name="by" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >

                                   
                            
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "evaluation")
      
                            {{Form::open(['url'=>'evaluations', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                    <td> </td>
                                    <th class='workflow-th' colspan="2">Date:</th>
                                    <th class='workflow-th' colspan="2">No. Of Days Accomplished:</th>
                                </tr>
                                <tr class="@if($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0) current-task @endif">
                                    <td>{{$tasks->taskName}}</td>
                                    <td colspan="2">
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" onchange="changeDOA(this.value)"
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                        </div>
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                            @if($sectiondays==0&&$prdays==0)

                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($purchaseToEdit->dateReceived))}}">
                                
                                            @elseif("1899-11-30 00:00:00"==$taskc->dateFinished||"0000-00-00 00:00:00"==$taskc->dateFinished)


                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($taskc->updated_at))}}">

                                            @else

                                            <?php 
                                                $taskprev= TaskDetails::find($taskc->id-1); 

                                            ?>
                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($taskprev->dateFinished))}}">

                                            @endif
                                        <input type="number" name="noofdays"  class="form-control" maxlength="12" width="80%" placeholder="Enter no. of days" id="daysOfAction"
                                        
                                        <?php
                                        if (NULL!=Input::old('noofdays'))
                                            echo "value=".Input::old('noofdays');
                                         else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                        else
                                            echo "value=1";
                                       
                                        ?>
                                        
                                        >
                                    </td>   
                 
                        
                        
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "conference")
                       
                            {{Form::open(['url'=>'conference', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                    <td colspan="4">
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                        <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" 
                                        
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                    </div>
                                    </td>
                         
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "contract")
                 
                            {{Form::open(['url'=>'contractmeeting', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                    <td> </td>
                                    <th class='workflow-th'>Date:</th>
                                    <th class='workflow-th'>No. of Days Accomplished:</th>
                                    <th class='workflow-th' colspan="2">Contract Agreement:</th>
                                </tr>
                                <tr class="@if($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0) current-task @endif">
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%" >
                                            <input type="text" class="form-control" name="date" id="date" onchange="changeDOA(this.value)" style="text-align: center; width:100%" 
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        />
                                        </div>
                                    </td>
                                            @if($sectiondays==0&&$prdays==0)

                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($purchaseToEdit->dateReceived))}}">
                          

                                            @elseif("1899-11-30 00:00:00"==$taskc->dateFinished||"0000-00-00 00:00:00"==$taskc->dateFinished)


                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($taskc->updated_at))}}">

                                            @else

                                            <?php 
                                                $taskprev= TaskDetails::find($taskc->id-1); 

                                            ?>
                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($taskprev->dateFinished))}}">

                                            @endif
                                    <td><input type="number" name="noofdays"  class="form-control" maxlength="100" width="80%" placeholder="Enter no. of days accomplished"
                                        id="daysOfAction"
                                        <?php
                                        if (NULL!=Input::old('noofdays'))
                                            echo "value=".Input::old('noofdays');
                                         else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                        else
                                            echo "value=1";
                                     
                                        ?>
                                        ></td>
                                    <td class="edit-pr-input" colspan="2">  
                                        <input type="text" name="contractmeeting"  class="form-control" maxlength="100" width="80%" placeholder="Enter contract agreement"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('contractmeeting'))
                                            echo Input::old('contractmeeting');
                                         else if (NULL!=$taskc->custom3)
                                        echo "value='".$taskc->custom3."'";
                                        
                                        ?>
                                        "
                                        >
                                    </td>
                      
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "meeting")
               
                            {{Form::open(['url'=>'contractmeeting', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                    <td> </td>
                                    <th class='workflow-th'>Date:</th>
                                    <th class='workflow-th'>No. of Days Accomplished:</th>
                                    <th class='workflow-th' colspan="2">Minutes of Bidding:</th>
                                </tr>
                                <tr class="@if($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0) current-task @endif">
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%"  onchange="changeDOA(this.value)"
                                            
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                            />
                                        </div>
                                    </td>
                                    <td class="edit-pr-input">  
                                            @if($sectiondays==0&&$prdays==0)

                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($purchaseToEdit->dateReceived))}}">
                                        

                                            @elseif("1899-11-30 00:00:00"==$taskc->dateFinished||"0000-00-00 00:00:00"==$taskc->dateFinished)


                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($taskc->updated_at))}}">

                                            @else

                                            <?php 
                                                $taskprev= TaskDetails::find($taskc->id-1); 

                                            ?>
                                            <input id="datebasis" type="hidden" name="datebasis" value="{{date('m/d/y', strtotime($taskprev->dateFinished))}}">

                                            @endif
                                        <input type="number" name="noofdays"  class="form-control" maxlength="12" width="80%" placeholder="Enter no. of days accomplished"
                                        id="daysOfAction"
                                        <?php
                                        if (NULL!=Input::old('noofdays'))
                                            echo "value=".Input::old('noofdays');
                                        else if (NULL!=$taskc->custom2)
                                            echo "value='".$taskc->custom2."'";
                                        else
                                            echo "value=1";
                                        
                                            
                                        ?>
                                        
                                        >
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                        <input type="text" name="contractmeeting"  class="form-control" maxlength="100" width="80%" placeholder="Enter minutes of meeting"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('contractmeeting'))
                                            echo Input::old('contractmeeting');
                                         else if (NULL!=$taskc->custom3)
                                        echo "value='".$taskc->custom3."'";
                                        
                                            
                                        ?>
                                        "
                                        >
                                    </td>
                          
                          
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "rfq")
                
                            {{Form::open(['url'=>'rfq', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                    <td> </td>
                                    <th class='workflow-th'>No. of Suppliers:</th>
                                    <th class='workflow-th'>Date of RF (Within PGEPS 7 Days):</th>
                                    <th class='workflow-th' colspan="2">By:</th>
                                </tr>
                                <tr class="@if($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0) current-task @endif">
                                    <td>{{$tasks->taskName}}</td>
                                    <td><input type="number" name="noofsuppliers"  class="form-control" maxlength="12" width="80%" placeholder="Enter no. of suppliers"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('noofsuppliers'))
                                            echo Input::old('noofsuppliers');
                                         else if (NULL!=$taskc->custom1)
                                        echo "value='".$taskc->custom1."'";
                                       
                                        ?>
                                        "
                                        ></td>
                                    <td>
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" 
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if (NULL!=$taskc->custom2)
                                        echo "value='".$taskc->custom2."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                        </div>
                                    </td>
                                             <input type ="hidden" name="by" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >
                         
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "dateby")
                       
                            {{Form::open(['url'=>'dateby', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                <td class="edit-pr-input" colspan="2"> 
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                        <input type="text" class="form-control" name="dateFinished" id="dateFinished" style="text-align: center; width:100%" 
                                        
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if ("0000-00-00 00:00:00"!=$taskc->dateFinished)
                                        echo "value='".$taskc->dateFinished."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                    </div>
                                </td>
                                       <input type ="hidden" name="assignee" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >
                       
                            {{Form::close()}}   
                    @endif
                    @if($tasks->taskType == "datebyremark")

                            {{Form::open(['url'=>'datebyremark', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                
                                <td class="edit-pr-input"> 
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">

                                        <input type="text" class="form-control" name="dateFinished" id="dateFinished" style="text-align: center; width:100%" 
                                        
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if ("0000-00-00 00:00:00"!=$taskc->dateFinished)
                                        echo "value='".$taskc->dateFinished."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                    </div>
                                </td>
                                        <input type ="hidden" name="assignee" 
                                    <?php
                                    echo "value='".Auth::user()->lastname.", ".Auth::user()->firstname."'";
                                    ?>
                                    >
                               
                     
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "dateonly")
                   
                            {{Form::open(['url'=>'dateonly', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" >
                                     <input type="hidden" name="remarks" id="hiddenremarks"  value="{{$taskd->remarks}}"> 
                                
                                <td class="edit-pr-input" colspan="4"> 
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">

                                        <input type="text" class="form-control" name="dateFinished" id="dateFinished" style="text-align: center; width:100%" 
                                        
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                         else if ("0000-00-00 00:00:00"!=$taskc->dateFinished)
                                        echo "value='".$taskc->dateFinished."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                    </div>
                                </td>
                                
                      
                          
                            {{Form::close()}}
                    @endif

                    <!--End Task Forms-->
                        
                    <!--END Cursor Open Form-->        
                   
                    </table>
<br><br>

<!--Remarks-->
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
<button type="button" class="btn btn-success "  id="hidebtn" data-toggle="modal" data-target="#confirmDelete" >Done</button>

<div id ="formr">
{{ Form::open(['url'=>'remarks'], 'POST') }}
@if($taskd->remarks==NULL)
{{ Form::textarea('remarks','', array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical', 'id'=>'remarksform')) }}
@else
{{ Form::textarea('remarks',$taskd->remarks, array('class'=>'form-control', 'rows'=>'3', 'maxlength'=>'255', 'style'=>'resize:vertical', 'id'=>'remarksform' )) }}

@endif
<input type ="hidden" name="taskdetails_id" value="{{$taskd->id}}">
<br>
<input type="button"  onclick="hideRemarks()"  value="Cancel" class='btn btn-default' />
&nbsp;
{{ Form::submit('Save',array('class'=>'btn btn-warning')) }}
&nbsp;
<button type="button" class="btn btn-success "  id="showbtn" data-toggle="modal" data-target="#confirmDelete"  >Done</button>
{{ Form::close() }}
</div>
<!--End Remarks-->


<!--End Tasks Forms-->


















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


   <!-- CODES FOR MODAL -->
    <div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"><b>Confirm Submission</b></h4>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to submit edit?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">Cancel</button>
            <button type="button" class="btn btn-success" id="confirmModal" value="Submit" onclick="remarksauto()">Submit</button>
          </div>
        </div>
      </div>
    </div>
    <!-- CODES FOR MODAL END -->
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
function remarksauto()
    {
      
      
      var remarks = document.getElementById('remarksform').value;
      
      document.getElementById('hiddenremarks').value = remarks;


      var formname= "taskform";
      document.getElementById(formname).submit();
    }


   </script>
   {{ HTML::script('js/bootstrap-ajax.js');}}
    <script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        // When the document is ready
        $(document).ready(function () {
            
            $('.input-daterange').datepicker({
                todayBtn: "linked"
            });
        });

          function changeDOA(value)
    {
var datebasis = document.getElementById("datebasis").value;
var date1 = new Date(value);
var date2 = new Date(datebasis);
var timeDiff = date1.getTime() - date2.getTime();
var diffDays = timeDiff / (1000 * 3600 * 24); 
if(diffDays==0)
    diffDays=1;
else if(diffDays<0)
    diffDays=0;
else
    diffDays= Math.ceil(diffDays);
        document.getElementById("daysOfAction").value=diffDays;
        
 
    }
    </script>
@stop