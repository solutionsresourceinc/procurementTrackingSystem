@extends('layouts.dashboard')

@section('header')
{{ HTML::script('js/bootstrap.file-input.js') }}

<style type="text/css">
    td{

        padding:5px 10px;
        vertical-align:top;
        word-break:break-word;
    }

    @media print /*FOR PRINT LAYOUT*/
    {
        .no-print, .no-print *
        {
            display: none !important;
        }

        table, tr, td, th, p, h1, h2, h3, h4, h5
        {
            border-collapse: collapse !important;
            padding : 0px !important;
            font-size : 86% !important;
            height : 4px !important;
        }

        .panel, .panel-heading
        {
            margin: 0px !important;
            /*padding : 5px !important;*/
        }
    }
</style>


{{ HTML::script('js/lightbox.min.js') }}
{{ HTML::style('css/lightbox.css')}}


@stop

@section('content')

@if(Session::get('notice'))
    <div class="alert alert-success no-print"> {{ Session::get('notice') }}</div>
@endif

<h2 class="pull-left">Purchase Request Details </h2>


<div class="btn-group pull-right options">
    @if(!Entrust::hasRole('Requisitioner'))
        <button class="btn btn-info no-print" onclick="window.print()">
            <span class="glyphicon glyphicon-print"></span>&nbsp;&nbsp;Print
        </button>
    @endif
    <?php
    $cuser=Auth::User()->id;
    if (Entrust::hasRole('Administrator'))
    {
        if($purchase->status!="Cancelled"&&$purchase->status!="Closed")
        {
            ?>
            <a href="../edit/{{$purchase->id}}" class="btn btn-success no-print">
                <span class="glyphicon glyphicon-edit no-print"></span>&nbsp;&nbsp;Edit
            </a>
        <?php
        }
    }

    else if (Entrust::hasRole('Procurement Personnel'))
    {
        if($purchase->created_by==$cuser)
        {
            if($purchase->status!="Cancelled"&&$purchase->status!="Closed")
            {
                ?>
                <a href="../edit/{{$purchase->id}}" class="btn btn-success no-print">
                    <span class="glyphicon glyphicon-edit no-print"></span>&nbsp;&nbsp;Edit
                </a>


            <?php
            }
        }
    }
    ?>

    <button type="button" class="btn btn-default no-print" onclick="window.location.href='{{ URL::to('back') }}'">
        <span class="glyphicon glyphicon-chevron-left"></span>&nbsp;Back
    </button>

</div>

<hr class="clear no-print" />

@if($purchase->status == "Cancelled")
<div class="alert alert-danger no-print"> Reason: {{ strip_tags($purchase->reason) }}</div>
@endif

<div class="panel panel-success">
    <div class="panel-body">
        <table border="1" class="proc-details">
            <tr>
                <td class="proc-headers" colspan="3"><h4 style="line-height: 25px;">
                        <?php $workName = DB::table('workflow')->where('id',$wfName->work_id)->first(); ?>

                        @if($workName->workFlowName == $purchase->otherType)
                        <?php $workflowNameWithOtherType = $workName->workFlowName; ?>
                        @else
                        <?php $workflowNameWithOtherType = $workName->workFlowName . " - " . $purchase->otherType; ?>
                        @endif

                        {{{ strtoupper($workflowNameWithOtherType) }}}

						<span class="no-print label {{($purchase->status == 'New') ? 'label-primary' : (($purchase->status == 'Active') ? 'label-success' : (($purchase->status == 'Overdue') ? 'label-danger' : 'label-default'))}}">
							{{ $purchase->status; }}
						</span>
                    </h4>
                </td>

                <td colspan="1" width="30%">
                    <span class="bac-ctrl-no"><strong>BAC CTRL. NO.:</strong></span><br/>
                    <h4 align="center" class="ctrl-no"><?php echo str_pad($purchase->controlNo, 5, '0', STR_PAD_LEFT); ?></h4>
                </td>
            </tr>

            <tr>
                <td class="proc-headers" width="20%"><h5><strong>Requisitioner<strong></h5></td>
                <td class="proc-data">
                    <?php $user = User::find($purchase->requisitioner) ?>
                    {{ $user->lastname . ", " . $user->firstname }}
                </td>
                <td class="proc-data"><strong>Office<strong></td>
                <td class="proc-data">
                    <?php $office = Office::find($purchase->office) ?>
                    {{ $office->officeName }}
                </td>

            </tr>

            <tr>
                <td class="proc-headers"><h5><strong>Project / Purpose</strong></h5></td>
                <td class="proc-data">{{ $purchase->projectPurpose }}</td>
                <td class="proc-headers"><h5><strong>Project Type</strong></h5></td>
                <td class="proc-data">{{ $purchase->projectType }}</td>
            </tr>

            <tr>
                <td class="proc-data"><strong>ABC Amount</strong></td>
                <td class="proc-data">{{ $purchase->amount }}</td>
                <td class="proc-data"><strong>Date Requested</strong></td>
                <td class="proc-data">{{ $purchase->dateRequested }}</td>
            </tr>

            <tr>
                <td class="proc-headers"><h5><strong>Source Of Funds</strong></h5></td>
                <td class="proc-data">{{ $purchase->sourceOfFund }}</td>
                <td class="proc-headers"><h5><strong>Date Received</strong></h5></td>
                <td class="proc-data">{{ $purchase->dateReceived }}</td>
            </tr>

        </table>
    </div>
</div>
<!-- images section -->
@if(Entrust::hasRole('Requisitioner'))

<div id="img-section" class="no-print">

    <?php
    $docs= Document::where('pr_id', $purchase->id)->first();
    $attachmentc = DB::table('attachments')->where('doc_id', $docs->id)->count();
    if ($attachmentc!=0)
        echo "<h3>"."Attachments"."</h3>";

    $luser=Auth::user()->id;
    $count= Count::where('doc_id','=', $docs->id)->where('user_id','=', $luser )->delete();


    $attachments = DB::table('attachments')->where('doc_id', $docs->id)->get();
    $srclink="uploads\\";
    ?>

    @foreach ($attachments as $attachment)
    <div class="image-container">
        <a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="{{$attachment->data}}" title="{{$attachment->data}}">
            <img class="img-thumbnail" src="{{asset('uploads/'.$attachment->data)}}" style="width: 100px; height: 100px;" />
        </a>

    </div>
    @endforeach

</div>
@endif
<!--images section-->
@if(!Entrust::hasRole('Requisitioner'))
<!-- Section 1  -->
<?php
//Cursor Component
//Count Cursor
$id=$purchase->id;
$docs=DB::table('document')->where('pr_id', '=',$id )->first();
$workflow=DB::table('workflow')->get();
$taskch= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->count();

$taskexist= TaskDetails::where('doc_id', $docs->id)->count();
//Get Cursor Value
if($taskch==0)
    $taskc= TaskDetails::where('doc_id', $docs->id)->where('status', 'Done')->orderBy('id', 'DESC')->first();

else
    $taskc= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->first();


//Queries
$workflow= Workflow::find($docs->work_id);
$section= Section::where('workflow_id', $workflow->id)->orderBy('section_order_id','ASC')->get();
$taskd= TaskDetails::where('doc_id', $docs->id)->orderBy('id', 'ASC')->get();

$sectioncheck=0;
$prdays=0;
$lastid=0;

if ($taskexist!=0){
foreach($section as $sections)
{
    $sectiondays=0;
    $task= Task::where('section_id', $sections->section_order_id)->where('wf_id', $workflow->id)->orderBy('order_id', 'ASC')->get();
    echo "<div class='panel panel-success'><div class='panel-heading'><h3 class='panel-title'>".$sections->section_order_id.". ".$sections->sectionName."</h3></div>";

    echo "<div class='panel-body'>";
    echo "<table border='1' class='workflow-table'>";

    //Addon Display
    $otherc=OtherDetails::where('section_id', $sections->id)->count();

    if($otherc!=0)
    {
        $otherd= OtherDetails::where('section_id', $sections->id)->get();
        foreach ($otherd as $otherdetails)
        {
            if($otherdetails->label!="Total Days for BAC Documents Preparation"&&$otherdetails->label!="Compliance")
            {

                echo "<tr><td width='30%'>".$otherdetails->label."</td>";
                $valuesc=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchase->id)->count();
                $values=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchase->id)->first();
                ?>
                @if ($valuesc==0)
                <td colspan="3"> </td>
                <?php continue; ?>
                @else
                <td width='48%' colspan='3'>{{$values->value}}</td>

                @endif


                </tr>
            <?php
            }
        }
    }
    //End of Addon Display
    $previousTaskType="0";

    foreach ($task as $tasks)
    {
        $taskcount =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->count();
        if($taskcount==0)
            continue;
        $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();
        if($taskp->status=="Lock")
            continue;

        if ($previousTaskType!="normal"&&$tasks->taskType=="normal")
        {
            echo "<tr><th width='30%'></th>";
            echo "<th class='workflow-th' width='18%'>By:</th>";
            echo "<th class='workflow-th' width='18%'>Date:</th>";
            echo "<th class='workflow-th' width='12.5%'>Days of Action</th>";
            echo "<th class='workflow-th'>Remarks</th></tr>";
        }
        if ($previousTaskType!="datebyremark"&&$tasks->taskType=="datebyremark")
        {
            echo "<tr><th width='30%'></th>";
            echo "<th class='workflow-th' >Date:</th>";
            echo "<th class='workflow-th' >By:</th>";
            echo "<th class='workflow-th' colspan='2'>Remarks</th></tr>";
        }
        if ($previousTaskType!="dateby"&&$tasks->taskType=="dateby")
        {
            echo "<tr><th width='30%'></th>";
            echo "<th class='workflow-th' colspan='2'>Date:</th>";
            echo "<th class='workflow-th' colspan='2'>By:</th></tr>";
        }
        if ($previousTaskType!="evaluation"&&$tasks->taskType=="evaluation")
        {
            echo "<tr><th width='30%'></th>";
            echo "<th class='workflow-th' colspan='2'>Date:</th>";
            echo "<th class='workflow-th' colspan='2'>No. of Days Accomplished:</th></tr>";
        }
        $previousTaskType=$tasks->taskType;

        //Displayer
        $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();
        //Total Initializers Function
        $taskprevc =TaskDetails::find($taskp->id-1);
        //Handle Section 1 set prfirst and sectionfirst
        if($tasks->section_id=="1")
        {
            $prfirstdate=date('Y-m-d', strtotime($purchase->dateReceived));
            $sectionfirstdate=date('Y-m-d', strtotime($purchase->dateReceived));
            if($taskc->id==$taskp->id)
                $lastid=$taskc->id-1;
            else
                $lastid=$taskp->id;
        }
        //Handle Pending task record nothing
        else if ($taskp->status=="Pending")
        {

        }
        //Handles othe
        else
        {
            $taskprevlast =TaskDetails::find($taskp->id-1);
            $taskprevtask=Task::find($taskprevlast->task_id);

            if ($taskprevtask->section_id!=$tasks->section_id)
            {



                $sectionfirstdate=date('Y-m-d', strtotime($taskp->dateFinished));


            }
            $taskctask=Task::find($taskc->task_id);
            if($taskctask->section_id==$tasks->section_id)
            {
                if($taskc->id==$taskp->id)
                    $lastid=$taskc->id-1;
                else
                    $lastid=$taskp->id;

                if ($purchase->status=="Closed")
                    $lastid=$taskp->id;

            }
            else
            {
                $lastid=$taskp->id;

            }
        }
        //End Initializers Total Function


            echo "<tr>";

            if ($tasks->taskType!="cheque"&&$tasks->taskType!="published"&&$tasks->taskType!="contract"&&$tasks->taskType!="meeting"&&$tasks->taskType!="rfq"&&$tasks->taskType!="documents"&&$tasks->taskType!="evaluation"&&$tasks->taskType!="preparation")
            {
                echo "<td width='30%'>";
                echo " <b>".$tasks->taskName."</b></td>";
            }

            //Task Display


            ?>

     @if ($tasks->taskType=="normal")
            <td align="center">
                <?php
                if($taskp->assignee!=NULL)
                {
                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                    echo $dassignee;
                }
                else if($taskp->assignee_id!=0 && $taskp->status=='Done')
                {
                    $assign_user=User::find($taskp->assignee_id);
                    echo $assign_user->lastname.", ".$assign_user->firstname;
                }
                $date = new DateTime($taskp->dateFinished);
                $datef = $date->format('m/d/y');
                ?>
            </td>

            <td align="center">
                <?php
                if($taskp->dateFinished!="0000-00-00 00:00:00")
                    echo $datef;
                ?>
            </td>
            <td align="center">
                <?php
                if($taskp->dateFinished!="0000-00-00 00:00:00")
                    echo $taskp->daysOfAction;
                ?>
            </td>
            <td align="center">
                <?php
                if($taskp->status == 'Done')
                {
                    $dremarks=chunk_split($taskp->remarks, 20, "<br>");
                    echo $dremarks;
                }
                ?>
            </td>
  @endif
      @if($tasks->taskType=="certification")
            <td colspan="2" align="center">
                <input type="radio" name="displayradio" value="yes"
                    <?php if($taskp->custom1=="yes") echo " checked";?>
                       disabled > Yes
                <input type="radio" name ="displayradio" value="no"
                    <?php if($taskp->custom1=="no") echo " checked";?>
                       disabled> No
            </td>
            <td colspan="2" align="center">
                 <b>By: </b>
                {{$taskp->custom2;}}
            </td>
        @endif
    @if($tasks->taskType=="posting")
            <td colspan="2" align="center">
                 <b>Reference No. : </b>
                {{$taskp->custom1;}}
            </td>
            <td align="center">
                 <b>Date: </b>
                {{$taskp->custom2;}}
            </td>
            <td align="center">
                 <b>By: </b>
                {{$taskp->custom3;}}
            </td>
    @endif
     @if($tasks->taskType == "supplier")
            <td class="edit-pr-input" colspan="2" align="center">
                {{$taskp->custom1}}
            </td>

            <td class="edit-pr-input" colspan="2" align="center">
                 <b>AMOUNT: </b>
                {{$taskp->custom2}}
            </td>
    @endif
     @if($tasks->taskType=="cheque")
            <td class="edit-pr-input" colspan="2" align="center">
                 <b>CHEQUE AMOUNT: </b>&nbsp;&nbsp;&nbsp;
                {{$taskp->custom1}}
            </td>
            <td class="edit-pr-input" colspan="2" align="center">
                 <b>CHEQUE NUMBER: </b>&nbsp;&nbsp;&nbsp;
                {{$taskp->custom2}}
            </td>
            <td class="edit-pr-input" colspan="2" align="center">
                 <b>CHEQUE DATE: </b>&nbsp;&nbsp;&nbsp;
                {{$taskp->custom3}}
            </td>
        @endif
        @if($tasks->taskType=="published")
            <td> </td>
            <th class='workflow-th' width="18%">Date Published:</th>
            <th class='workflow-th' width="18%">End Date:</th>
            <th class='workflow-th' colspan="2">Posted By:</th>
            </tr>
            <tr>
                <td > <b>{{$tasks->taskName}} </b></td>
                <td align="center">
                    {{$taskp->custom1}}
                    <span class="add-on"><i class="icon-th"></i></span>
                </td>
                <td align="center">{{$taskp->custom2}}</td>
                <td class="edit-pr-input" colspan="2" align="center">{{$taskp->custom3}}</td>

        @endif
        @if($tasks->taskType=="philgeps")

            </tr>
            <tr>

                <td>
                     <b>Reference No.: </b><br>
                    <center>{{$taskp->custom1}}</center>
                    <span class="add-on"><i class="icon-th"></i></span>
                </td>
                <td >
                     <b>Date Published: </b><br>
                    <center>{{$taskp->custom2}}</center>
                </td>
                <td class="edit-pr-input" >
                     <b>End Date: </b><br>
                    <center>{{$taskp->custom3}}</center>
                </td>
                <td colspan="2" >
                     <b>Posted By: </b><br>
                    <center>{{$taskp->assignee}}</center>
                </td>

        @endif
        @if($tasks->taskType=="documents")
                <td> </td>
                <th class='workflow-th'>Eligibility Documents:</th>
                <th class='workflow-th'>Date of Bidding:</th>
                <th class='workflow-th' colspan="2">Checked By:</th>
             </tr>
             <tr>
                <td> <b>{{$tasks->taskName}} </b></td>
                <td align="center">
                    {{$taskp->custom1}}
                    <span class="add-on"><i class="icon-th"></i></span>
                </td>
                <td align="center">{{$taskp->custom2}}</td>
                <td class="edit-pr-input" colspan="2" align="center">{{$taskp->custom3}}</td>
        @endif
        @if($tasks->taskType=="evaluation")

                <td > <b>{{$tasks->taskName}} </b></td>
                <td colspan="2" align="center">
                    {{$taskp->custom1}}
                </td>
                <td class="edit-pr-input" colspan="2" align="center"> {{$taskp->custom2}}</td>
        @endif
        @if($tasks->taskType=="conference")
                <td colspan="4" align="center">
                    {{$taskp->custom1}}
                </td>
        @endif
        @if($tasks->taskType=="contract")
                <td> </td>
                <th class='workflow-th'>Date:</th>
                <th class='workflow-th'>No. of Days Accomplished:</th>
                <th class='workflow-th' colspan="2">Contract Agreement:</th>
                </tr>
                <tr>
                <td > <b>{{$tasks->taskName}} </b></td>
                <td align="center">
                    <?php
                    $today = date("m/d/y");
                    ?>
                    {{$taskp->custom1}}
                </td>
                <td class="edit-pr-input" align="center">{{$taskp->custom2}}</td>
                <td class="edit-pr-input" colspan="2" align="center">{{$taskp->custom3}}</td>
        @endif
            @if($tasks->taskType=="meeting")
                <td> </td>
                <th class='workflow-th'>Date:</th>
                <th class='workflow-th'>No. of Days Accomplished:</th>
                <th class='workflow-th' colspan="2">Minutes of Bidding:</th>
                </tr>
                <tr>
                <td > <b>{{$tasks->taskName}} </b></td>
                <td align="center">
                    <?php
                    $today = date("m/d/y");
                    ?>
                    {{$taskp->custom1}}
                </td>
                <td class="edit-pr-input" align="center">{{$taskp->custom2}}</td>
                <td class="edit-pr-input" colspan="2" align="center">{{$taskp->custom3}}</td>
            @endif
            @if($tasks->taskType=="rfq")
                <td> </td>
                <th class='workflow-th'>Supplier:</th>
                <th class='workflow-th'>Date of RF (Within PGEPS 7 Days):</th>
                <th class='workflow-th' colspan="2">By:</th>
                </tr>
                <tr>
                <td > <b>{{$tasks->taskName}} </b></td>
                <td align="center">
                {{$taskp->custom1}}
                </td>
                <td align="center">
                {{$taskp->custom2}}
                 </td>
                <td  colspan="2" class="edit-pr-input" align="center"> {{$taskp->custom3}}</td>
            @endif
    @if($tasks->taskType=="dateby")
                <td colspan="2" align="center">
                <?php
                $date = new DateTime($taskp->dateFinished);
                $datef = $date->format('m/d/y');
                if($taskp->dateFinished!="0000-00-00 00:00:00")
                    echo $datef;
                ?>
                </td>
                <td colspan="2" align="center">
                <?php
                if($taskp->assignee!=NULL)
                {
                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                    echo $dassignee;
                }
                else if($taskp->assignee_id!=0 && $taskp->status=='Done')
                {
                    $assign_user=User::find($taskp->assignee_id);
                    echo $assign_user->lastname.", ".$assign_user->firstname;
                }
                $date = new DateTime($taskp->dateFinished);
                $datef = $date->format('m/d/y');
                ?>
                 </td>
     @endif
    @if($tasks->taskType=="datebyremark")
            <td align="center">
                <?php
                $date = new DateTime($taskp->dateFinished);
                $datef = $date->format('m/d/y');
                if($taskp->dateFinished!="0000-00-00 00:00:00")
                    echo $datef;
                ?>
            </td>
            <td align="center">
                <?php
                if($taskp->assignee!=NULL)
                {
                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                    echo $dassignee;
                }
                else if($taskp->assignee_id!=0 && $taskp->status=='Done')
                {
                    $assign_user=User::find($taskp->assignee_id);
                    echo $assign_user->lastname.", ".$assign_user->firstname;
                }
                $date = new DateTime($taskp->dateFinished);
                $datef = $date->format('m/d/y');
                ?>
            </td>
            <td colspan="2" align="center">
                <?php
                if($taskp->status == 'Done')
                {
                    $dremarks=chunk_split($taskp->remarks, 20, "<br>");
                    echo $dremarks;
                }
                ?>
            </td>
      @endif
      @if($tasks->taskType=="dateonly")

            <td align="center" colspan="4">
                <?php

                if($taskp->custom1!=NULL)
                    echo  $taskp->custom1;
                ?>
            </td>
        @endif
            <?php
            //End Task Display
            $sectiondays=$sectiondays+$taskp->daysOfAction;
            $prdays=$prdays+$taskp->daysOfAction;



        echo "</tr>";
    
}

    //Addon Display
    $otherc=OtherDetails::where('section_id', $sections->id)->count();

    if($otherc!=0)
    {
        $otherd= OtherDetails::where('section_id', $sections->id)->get();

        foreach ($otherd as $otherdetails)
        {
            if($otherdetails->label=="Total Days for BAC Documents Preparation"||$otherdetails->label=="Compliance")
            {

                echo "<tr><td width='30%'>".$otherdetails->label."</td>";
                $valuesc=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchase->id)->count();
                $values=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchase->id)->first();
                ?>
                @if ($valuesc==0)
                <td colspan='3'> </td>
                <?php continue; ?>
                @else
                <td width='48%' colspan='3' align="center">{{$values->value}}</td>


                </tr>
                @endif
            <?php

            }
        }
        //End of Addon Display
    }
                 //Total Function Counting

                    $taskp =TaskDetails::find($lastid);

                    $lastdate=date('Y-m-d', strtotime($taskp->dateFinished));

                    $start = new DateTime($sectionfirstdate);
                    $end = new DateTime($lastdate);
                    // otherwise the  end date is excluded (bug?)
                    $end->modify('+1 day');
                    $interval = $end->diff($start);

                    // total days
                    $days = $interval->days;

                    // create an iterateable period of date (P1D equates to 1 day)
                    $period = new DatePeriod($start, new DateInterval('P1D'), $end);

                    // best stored as array, so you can add more than one
                    $holidays = array('2012-09-07');

foreach($period as $dt)
{
    $curr = $dt->format('D');

    // for the updated question
    if (in_array($dt->format('Y-m-d'), $holidays)) {
       $days--;
    }

    // substract if Saturday or Sunday
    if ($curr == 'day') {
        $days--;
    }
}


                    $sectiondays=$days;

                    $start = new DateTime($prfirstdate);
                    $end = new DateTime($lastdate);
                    // otherwise the  end date is excluded (bug?)
                    $end->modify('+1 day');
                    $interval = $end->diff($start);

                    // total days
                    $days = $interval->days;

                    // create an iterateable period of date (P1D equates to 1 day)
                    $period = new DatePeriod($start, new DateInterval('P1D'), $end);

                    // best stored as array, so you can add more than one
                    $holidays = array('2012-09-07');

foreach($period as $dt)
{
    $curr = $dt->format('D');

    // for the updated question
    if (in_array($dt->format('Y-m-d'), $holidays)) {
       $days--;
    }

    // substract if Saturday or Sunday
    if ($curr == 'day' ) {
        $days--;
    }
}


$prdays=$days;




                    //End Total Function Counting

                    ?>

            @if($workflow->workFlowName=="Direct Contracting"&&$sections->section_order_id=="2")
            @else
                    <tr>
                            <td><b>TOTAL NO. OF DAYS</b></td>

                            <td colspan="4"><center><?php
                            if ($sectiondays>10000)
                                echo "0";
                            else
                                echo $sectiondays;
                                ?></center></td>
                    </tr>
            @endif

                    </table></div></div>

                    <?php
               } }


                echo "<div class='panel panel-success'><div class='panel-body'>
                        <table border='1' class='proc-details'>
                            <tr>
                                <td width='66%'><h4 style='margin-left: 10px'>TOTAL NO. OF DAYS FROM PR TO PAYMENT: </h4></td>
                            <td><h4 style='margin-left: 50px;'>";
                            if($prdays>10000)
                            echo "0";
                            else
                            echo $prdays;
                            echo "</h4></td>
                            </tr>
                        </table>
                    </div></div>";
            ?>
            {{Session::forget('errorlabel')}}
            {{Session::forget('successlabel')}}
            {{Session::forget('errorchecklist')}}
            {{Session::forget('successchecklist')}}
            {{Session::forget('goToChecklist')}}



<!-- images section -->


<div id="img-section" class="no-print">

    <?php
    $docs= Document::where('pr_id', $purchase->id)->first();
    $attachmentc = DB::table('attachments')->where('doc_id', $docs->id)->count();
    if ($attachmentc!=0)
        echo "<h3>"."Attachments"."</h3>";

    $luser=Auth::user()->id;
    $count= Count::where('doc_id','=', $docs->id)->where('user_id','=', $luser )->delete();


    $attachments = DB::table('attachments')->where('doc_id', $docs->id)->get();
    $srclink="uploads\\";
    ?>

    @foreach ($attachments as $attachment)
    <div class="image-container">
        <a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="{{$attachment->data}}" title="{{$attachment->data}}">
            <img class="img-thumbnail" src="{{asset('uploads/'.$attachment->data)}}" style="width: 100px; height: 100px;" />
        </a>

    </div>
    @endforeach

</div>

<!--Upload Image-->
<?php

$doc=DB::table('document')->where('pr_id', '=',$id )->first();

?>
{{ Form::open(array('url' => 'taskimage', 'files' => true, 'id'=>'createform'), 'POST') }}
<label class="create-label no-print">Related files:</label>
<div class="panel panel-default fc-div no-print">
    <div class="panel-body" style="padding: 5px 20px;">

        <!--Image Module-->
        <?php

        $doc_id= $doc->id;
        ?>

        <br>
        <input name="file[]" type="file"  class="no-print" multiple title="Select image to attach" onchange="autouploadsaved()"/>
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
                    <a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="roadtrip" title="{{$attachment->data}}">
                        {{$attachment->data}}
                    </a>
                </td>
                <td>
                    &nbsp;
                </td>
                <td>

                    <input type="hidden" name="hide" value="{{$attachment->id}}">
                    <button type="button" onclick="delimage({{$count}})" class="tool-tip"  title="Delete" ><span class="glyphicon glyphicon-trash" title="Delete"  class="tool-tip"></span></button>

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
{{ Session::forget('notice'); }}
@stop

@section('footer')

<script type="text/javascript">

    $('input[type=file]').bootstrapFileInput();
    $('.file-inputs').bootstrapFileInput();

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
