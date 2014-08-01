@extends('layouts.dashboard')

@section('header')
<!---->
    {{ HTML::style('date_picker/bootstrap-datetimepicker.min.css')}}
    {{ HTML::script('date_picker/bootstrap-datetimepicker.js') }}
    {{ HTML::script('date_picker/bootstrap-datetimepicker.fr.js') }}
    {{ HTML::style('css/datepicker.css')}}
    {{ HTML::script('js/bootstrap-datepicker.js') }}
    
    <!--Image Display-->
    {{ HTML::script('js/lightbox.min.js') }} 
    {{ HTML::style('css/lightbox.css')}}
    
    <!--End Image Display-->
    {{ HTML::script('js/jquery.chained.min.js') }} 
    {{ HTML::script('js/bootstrap.file-input.js') }}

    <script type="text/javascript">
        function stopRKey(evt) { 
        var evt = (evt) ? evt : ((event) ? event : null); 
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
        if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
        } 

        document.onkeypress = stopRKey; 
    </script>

@stop



@section('content')



    <?php
        
    //Initialization for page query
    $pass=0; //Use in role restriction.
    $purchaseToEdit=Purchase::find($id);
    $user_id=Auth::User()->id;
    
    //Retain Inputte Values

        if (Input::old('projectPurpose')||Input::old('sourceOfFund')||Input::old('amount')){
            $valprojectPurpose=Input::old('projectPurpose');
            $valsourceOfFund=Input::old('sourceOfFund');
            $valamount=Input::old('amount'); 
            $valprojectType=$purchaseToEdit->projectType;

        }
        else
        {
            $valprojectPurpose=$purchaseToEdit->projectPurpose;
            $valprojectType=$purchaseToEdit->projectType;
            $valsourceOfFund=$purchaseToEdit->sourceOfFund;
            $valamount=$purchaseToEdit->amount; 

        }
    ?>

    <h1 class="page-header">Edit Purchase Request</h1>

    <div class="form-create fc-div">

        {{ Form::open(array('action' => '/newedit','files' => true, 'id'=>'createform'), 'POST') }}

            <input type="hidden" name ="id" value={{$id}}>

            @if(Session::get('notice'))
                <div class="alert alert-success"> {{ Session::get('notice') }}</div> 
            @endif

            @if(Session::get('main_error'))
                <div class="alert alert-danger"> {{ Session::get('main_error') }}</div> 
            @endif
            
            
            @if(Session::get('successlabel'))
                <div class="alert alert-success"> {{ Session::get('successlabel') }}</div> 
            @endif

            @if(Session::get('errorlabel'))
                <div class="alert alert-danger"> {{ Session::get('errorlabel') }}</div> 
            @endif

            <div class="form-group">
                <?php
                        $docs=DB::table('document')->where('pr_id', '=',$id )->first();
                        $workflow=DB::table('workflow')->get();
                        
                        $luser=Auth::user()->id;
                        $count= Count::where('doc_id','=', $docs->id)->where('user_id','=', $luser )->delete();
                    ?>
                <div class="row">
                    <div class="col-md-6">
                        
                        {{ Form::label('modeOfProcurement', 'Mode of Procurement', array('class' => 'create-label')) }}

                        <select  name="modeOfProcurement" id="modeOfProcurement" class="form-control" data-live-search="true" disabled="disabled">
                                <option value="">Please select</option>
                            @foreach($workflow as $wf)
                                <option value="{{ $wf->id }}" 
                                    <?php
                                        if (Input::old('modeOfProcurement'))
                                            echo "selected";
                                        else if($docs->work_id==$wf->id)
                                            echo "selected";
                                        else echo " "
                                    ?> >{{$wf->workFlowName}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        {{ Form::label('otherType', 'Other Type', array('class' => 'create-label')) }}
                        @if($purchaseToEdit->otherType == ' ' || $purchaseToEdit->otherType == null)
                            <input type="text" disabled value="None" class="form-control">
                        @else
                            <input type="text" disabled value="{{ $purchaseToEdit->otherType }}" class="form-control">
                        @endif
                    </div>

                    <div class="col-md-3">
                        <?php 
                            $cn = 0;
                            $purchase = Purchase::orderBy('controlNo', 'ASC')->get();
                            foreach ($purchase as $pur) {

                                $cn = (int)$pur->controlNo;
                            }
                            $cn =$cn+1;
                        ?>

                        {{ Form::label('dispCN', 'Control No.', array('class' => 'create-label')) }}
                        <input type="text"  name="dispCN"  class="form-control" value="{{
                        $purchaseToEdit->controlNo}}"disabled>
                        <input type="hidden" name="controlNo" value="{{
                        $purchaseToEdit->controlNo}}">
                    </div>
                </div>
                <br/>

                <div class="row">
                    <div class="col-md-8">
                        {{ Form::label('projectPurpose', 'Project/Purpose *', array('class' => 'create-label')) }}
                        {{ Form::text('projectPurpose',$valprojectPurpose, array('class'=>'form-control','maxlength'=>'255')) }}

                        @if (Session::get('error_projectPurpose'))
                            <font color="red"><i>{{ Session::get('error_projectPurpose') }}</i></font>
                        @endif
                    </div>


                    <div class="col-md-4">
                        {{ Form::label('projectType', 'Project Type', array('class' => 'create-label')) }}
                        {{ Form::text('projectPurpose',$valprojectType, array('class'=>'form-control', 'disabled')) }}
                    </div>
                </div>
                <br/>              

                <div class="row">
                    <div class="col-md-6">
                        {{ Form::label('sourceOfFund', 'Source of Fund *', array('class' => 'create-label')) }}
                        {{ Form::text('sourceOfFund',$valsourceOfFund, array('class'=>'form-control','maxlength'=>'255')) }}
                        @if (Session::get('error_sourceOfFund'))
                            <font color="red"><i>{{ Session::get('error_sourceOfFund') }}</i></font>
                        @endif
                    </div>

                    
                    <div class="col-md-6">
                        {{ Form::label('amount', 'Amount', array('class' => 'create-label')) }}
                        {{ Form::text('amount', $purchaseToEdit->amount ,array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)','id'=>'num','disabled')) }}
                    </div>
           
                </div>
                <br/>

                <?php 
                    $office= DB::table('offices')->get();
                ?>

                <div class="row">
                    <div class="form-group col-md-6" id="template">
                        {{ Form::label('office', 'Office *', array('class' => 'create-label')) }}

                        <select id="office" name="office" class="form-control" data-live-search="true">
                                <option value="">Please select</option>
                            @foreach($office as $key)
                                <option value="{{ $key->id }}" 
                                    <?php 
                                        if (Input::old('office')==$key->id)
                                            echo "selected";
                                        else if($purchaseToEdit->office==$key->id)
                                            echo "selected" 
                                    ?>
                                    >{{{ $key->officeName }}}
                                </option>
                            @endforeach
                        </select>

                        @if (Session::get('error_office'))
                            <font color="red"><i>{{ Session::get('error_office') }}</i></font>
                        @endif
                    
                    </div>

                    <div class="form-group col-md-6" id="template">
                        {{ Form::label('requisitioner', 'Requisitioner *', array('class' => 'create-label')) }}
                        
                        <select class="form-control" id="requisitioner" name="requisitioner"  data-live-search="true" >
                            <?php
                            $users= DB::table('users')->get();
                            ?>
                                    <option value="">Please select</option>
                            @foreach($users as $key2)
                                {{{ $fullname = $key2->lastname . ", " . $key2->firstname }}}
                                @if($key2->confirmed == 0)
                                    continue;
                                @else
                                    <option value="{{ $key2->id }}" class="{{$key2->office_id}}"
                                        <?php 
                                        if (Input::old('requisitioner')==$key2->id)
                                          echo "selected";
                                        else if($purchaseToEdit->requisitioner==$key2->id)
                                            echo "selected" 
                                        ?>
                                        >{{ $fullname }}
                                    </option>
                                @endif
                            @endforeach
                        </select>

                        @if (Session::get('error_requisitioner'))
                            <font color="red"><i>{{ Session::get('error_requisitioner') }}</i></font>
                        @endif
                  
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6" id="template">
                        {{ Form::label('dateTime', 'Date Received ', array('class' => 'create-label')) }}
                        <div class="input-group date form_datetime col-md-12" data-date="{{ date('Y-m-d') }}T{{ date('H:i:s') }}Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input2">
                            <input id="disabled_datetimeDateRec" onchange="fix_formatDateRec()" class="form-control" size="16" type="text" value="<?php
                            if (NULL!=Input::old('dateReceived'))
                                echo Input::old('dateReceived');
                            else
                                echo $purchaseToEdit->dateReceived; ?>" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                        
                        <input type="hidden" id="dtp_input2" name="dateReceived" value="<?php
                            if (NULL!=Input::old('dateReceived'))
                                echo Input::old('dateReceived');
                            else
                                echo $purchaseToEdit->dateReceived; ?>" />
                        @if (Session::get('error_dateReceived'))
                            <font color="red"><i>{{ Session::get('error_dateReceived') }}</i></font>
                        @endif
                        <br>
                    </div>

                    <div class="form-group col-md-6" id="template">
                        {{ Form::label('dateTime', 'Date Requested ', array('class' => 'create-label')) }}
                        <div class="input-group date form_datetime col-md-12" data-date="{{ date('Y-m-d') }}T{{ date('H:i:s') }}Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                            <input id="disabled_datetime" onchange="fix_format()" class="form-control" size="16" type="text" value="<?php
                            if (NULL!=Input::old('dateRequested'))
                                echo Input::old('dateRequested');
                            else
                                echo $purchaseToEdit->dateRequested; ?>" readonly>
                            <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                        </div>
                        
                        <input type="hidden" id="dtp_input1" name="dateRequested" value="<?php
                            if (NULL!=Input::old('dateRequested'))
                                echo Input::old('dateRequested');
                            else
                                echo $purchaseToEdit->dateRequested; ?>" />
                        @if (Session::get('error_dateRequested'))
                            <font color="red"><i>{{ Session::get('error_dateRequested') }}</i></font>
                        @endif
                        <br>
                    </div>
                </div>
            </div>

            <label class="create-label">Related files:</label>
            <div class="panel panel-default fc-div">
                <div class="panel-body" style="padding: 5px 20px;">
            
                <!--Image Module-->
                 <?php
                        $document = Document::where('pr_id', $purchaseToEdit->id)->first();
                        $doc_id= $document->id;
                    ?>
                
                    <input name="file[]" type="file"  multiple title="Select image to attach" onchange="autouploadsaved('createform')"/>
                    <input name="doc_id" type="hidden" value="{{ $doc_id }}">
@if(Session::get('imgsuccess'))
                        <div class="alert alert-success"> {{ Session::get('imgsuccess') }}</div> 
                    @endif

                    @if(Session::get('imgerror'))
                        <div class="alert alert-danger"> {{ Session::get('imgerror') }}</div> 
                    @endif
                    <br>
                   
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
                </div>
            </div>

            <div><br/>
                <br>
                {{ Form::submit('Save',array('class'=>'btn btn-success')) }}
                <a href="{{ URL::previous() }}" class="btn btn-default">Cancel</a>
            </div>
            
            {{ Form::close() }} 
     <!--  
            Image Module
            -->
            <div >

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
 
            <!-- End Image Module-->

           

                {{Session::forget('notice'); }}
                {{Session::forget('main_error'); }}
                {{Session::forget('imgerror'); }}
                {{Session::forget('imgsuccess'); }}
                {{Session::forget('error_projectPurpose');}}
                {{Session::forget('error_sourceOfFund');}}
                {{Session::forget('error_office');}}
                {{Session::forget('error_requisitioner');}}
                {{Session::forget('error_dateRequested');}}
                {{Session::forget('error_modeOfProcurement');}}
                {{Session::forget('imgerror'); }}

    
            </div>
        </div>  
       
        <br>
        <div style="width: 85%;margin: auto;">
            <!-- Section 1  -->
            <?php 
            //Cursor Component
                //Count Cursor
                $taskch= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->count(); 
                //Get Cursor Value
                $taskc= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->first(); 
                
                //Queries
                $workflow= Workflow::find($docs->work_id);
                $section= Section::where('workflow_id', $workflow->id)->orderBy('section_order_id','ASC')->get();
                $taskd= TaskDetails::where('doc_id', $docs->id)->orderBy('id', 'ASC')->get();
                
                $sectioncheck=0;
                $prdays=0;
      
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
                            $valuesc=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchaseToEdit->id)->count();
                            $values=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchaseToEdit->id)->first();  
                             ?>  
                            @if ($valuesc==0) 
                                {{Form::open(['url'=>'insertaddon'], 'POST')}}
                                    <input type="hidden" name="otherDetails_id" value="{{$otherdetails->id}}">
                                    <input type="hidden" name="purchase_request_id" value="{{$purchaseToEdit->id}}">
                                    <td colspan="3">


                                        <input name ="value" type="text" class="form-control" value="<?php if($otherdetails->id==Session::get("retainId"))
                                        {
                                            echo trim(Session::get("retainOtherDetails"));
                                        }
                                        
                                            ?>"></td>
                                    <td align="center"><button class ="btn btn-primary">Save</button></td>
                                {{Form::close()}}
                            @else 
                                <td width='48.5%' colspan='3'>{{$values->value}}</td>
                    
                                {{Form::open(['url'=>'editaddon', 'POST'])}}
                                    <input type='hidden' name='values_id' value="{{$values->id}}">
                                    <td align='center'>
                                        <button class ='btn btn-default'>Edit</button>
                                    </td>
                                {{Form::close()}}
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
                    $previousTaskType=$tasks->taskType;
                    //Cursor Open form 
                    //Displayer 
                    $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();

                    if ($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0)
                    {   
                        ?>
                        
                                @if(Session::get('successchecklist'))
                                 <tr>
                                 <br>
                                    <div class="alert alert-success"> {{ Session::get('successchecklist') }}</div> 
                                  
                                <tr>
                                @endif
                                @if(Session::get('errorchecklist'))
                                <tr>
                                <br>
                                    <div class="alert alert-danger"> {{ Session::get('errorchecklist') }}</div> 
                                    
                                <tr>
                                @endif
                         
                        <?php
                        echo "<tr class='current-task'>";

                        //if ($tasks->taskType!="cheque"&&$tasks->taskType!="published"&&$tasks->taskType!="contract"&&$tasks->taskType!="meeting"&&$tasks->taskType!="rfq")
                        if ($tasks->taskType!="cheque"&&$tasks->taskType!="published"&&$tasks->taskType!="contract"&&$tasks->taskType!="meeting"&&$tasks->taskType!="rfq"&&$tasks->taskType!="documents"&&$tasks->taskType!="evaluation"&&$tasks->taskType!="preparation")
                        {
                            echo "<td>";
                            echo $tasks->taskName."</td>";
                        }
                        
                    
                    //Task Forms
                    ?>

                    @if($tasks->taskType == "normal")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'checklistedit', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                <td class="edit-pr-input">
                                    <input type ="text" name="assignee" placeholder="Enter name" class="form-control" width="100%" maxlength="100"
                                    value="<?php
                                    if (NULL!=Input::old('assignee'))
                                    echo Input::old('assignee');
                                    ?>"
                                    >
                                </td>
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
                                <td class="edit-pr-input">
                                    <input type="number" name="daysOfAction" class="form-control"  min="0" value="1" width="100%" maxlength="12"
                                    value="<?php
                                    if (NULL!=Input::old('daysOfAction'))
                                    echo Input::old('daysOfAction');
                                    ?>"
                                    >
                                </td>
                                <td class="edit-pr-input">
                                    <input type="text" name="remarks"  class="form-control" maxlength="255" width="100%"
                                    value="<?php
                                    if (NULL!=Input::old('remarks'))
                                    echo Input::old('remarks');
                                    ?>"
                                    >
                                </td>
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "certification")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'certification', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                               <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                <td class="edit-pr-input" colspan="2">
                                    <input type="radio" name="radio" value="yes" />&nbsp;&nbsp;Yes &nbsp;&nbsp;
                                    <input type="radio" name="radio" value="no" />&nbsp;&nbsp;No<br />
                                </td>
                                
                                <td class="edit-pr-input" colspan="2">
                                    <input type="text" name="by"  placeholder="Enter name" class="form-control" maxlength="100" width="80%" maxlength="100"
                                    value="<?php
                                    if (NULL!=Input::old('by'))
                                    echo Input::old('by');
                                    ?>"
                                    >
                                </td>

                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "posting")
                    <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'posting', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                <td class="edit-pr-input">
                                    Reference No. : 
                                    <input type="text" name="referenceno"  class="form-control" maxlength="100" width="80%" maxlength="100"
                                    value="<?php
                                    if (NULL!=Input::old('referenceno'))
                                    echo Input::old('referenceno');
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
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                    </div>
                                </td>
                                <td class="edit-pr-input" colspan="3">
                                    By: 
                                    <input type="text" name="by"  placeholder="Enter name" class="form-control" maxlength="100" width="80%" maxlength="100"
                                     value="<?php
                                    if (NULL!=Input::old('by'))
                                    echo Input::old('by');
                                    ?>"
                                    >
                                </td>

                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "supplier")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'supplier', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                <td class="edit-pr-input" colspan="2">
                                    <input type="text" name="supplier"  class="form-control" maxlength="100" width="80%" placeholder="Enter supplier"
                                     value="<?php
                                    if (NULL!=Input::old('supplier'))
                                    echo Input::old('supplier');
                                    ?>"

                                    >
                                </td>
                                
                                <td class="edit-pr-input" colspan="2">
                                    <input type="decimal" name="amount"  id="amount" class="form-control" maxlength="12" width="80%" placeholder="Enter amount" onkeypress="return isNumberKey(event)" onchange="checklist_changeAmount(this.id,this.value)"
                                     value="<?php
                                    if (NULL!=Input::old('amount'))
                                    echo Input::old('amount');
                                    ?>"
                                    >
                                </td>

                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "cheque")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'cheque', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                <td class="edit-pr-input" colspan="2">
                    
                                    <input type="decimal" name="amt"  id="amt" class="form-control" maxlength="12" width="80%" placeholder="Enter cheque amount" onkeypress="return isNumberKey(event)" onchange="checklist_changeAmount(this.id,this.value)"
                                     value="<?php
                                    if (NULL!=Input::old('amt'))
                                    echo Input::old('amt');
                                    ?>"
                                    >
                                </td>
                                <td class="edit-pr-input" colspan="2">
                                    
                                    <input type="decimal" name="num"  class="form-control" maxlength="12" width="80%" placeholder="Enter cheque number"
                                     value="<?php
                                    if (NULL!=Input::old('num'))
                                    echo Input::old('num');
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
                    @if($tasks->taskType == "published")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'published', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
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
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                             />
                                        </div>
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                        <input type="text" name="by"  placeholder="Enter name" class="form-control" maxlength="100" width="80%"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('by'))
                                            echo Input::old('by');
                                       
                                        ?>
                                        "
                                        >
                                    </td>
                          
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"
                                    > 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "documents")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'documents', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
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
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                            />
                                        </div>
                                    </td>
                                    <td class="edit-pr-input" colspan="2" >  
                                        <input type="text" name="by"  class="form-control" maxlength="100" width="80%" placeholder="Enter name"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('by'))
                                            echo Input::old('by');
                                        
                                        ?>
                                        "
                                        >
                                    </td>

                                   
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "evaluation")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'evaluations', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
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
                                            <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%"
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                        </div>
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                        <input type="number" name="noofdays"  class="form-control" maxlength="100" width="80%" placeholder="Enter no. of days"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('noofdays'))
                                            echo Input::old('noofdays');
                                       
                                        ?>
                                        "
                                        >
                                    </td>   
                 
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "conference")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'conference', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                    <td colspan="4">
                                    <?php 
                                    $today = date("m/d/y");
                                    ?>
                                    <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                        <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" 
                                        
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
                    @if($tasks->taskType == "contract")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'contractmeeting', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
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
                                        <div class="input-daterange" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="mm/dd/yy" style="width:100%">
                                            <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" 
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        />
                                        </div>
                                    </td>
                                    <td><input type="number" name="noofdays"  class="form-control" maxlength="100" width="80%" placeholder="Enter no. of days accomplished"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('noofdays'))
                                            echo Input::old('noofdays');
                                     
                                        ?>
                                        "
                                        ></td>
                                    <td class="edit-pr-input" colspan="2">  
                                        <input type="text" name="contractmeeting"  class="form-control" maxlength="100" width="80%" placeholder="Enter contract agreement"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('contractmeeting'))
                                            echo Input::old('contractmeeting');
                                        
                                        ?>
                                        "
                                        >
                                    </td>
                                    

                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "meeting")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'contractmeeting', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
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
                                            <input type="text" class="form-control" name="date" id="date" style="text-align: center; width:100%" 
                                            
                                        <?php
                                        if (NULL!=Input::old('dateFinished'))
                                            echo "value ='" . Input::old('dateFinished') ."'";
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                            />
                                        </div>
                                    </td>
                                    <td class="edit-pr-input">  
                                        <input type="number" name="noofdays"  class="form-control" maxlength="12" width="80%" placeholder="Enter no. of days accomplished"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('noofdays'))
                                            echo Input::old('noofdays');
                                        
                                            
                                        ?>
                                        "
                                        >
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                        <input type="text" name="contractmeeting"  class="form-control" maxlength="100" width="80%" placeholder="Enter minutes of meeting"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('contractmeeting'))
                                            echo Input::old('contractmeeting');
                                        
                                            
                                        ?>
                                        "
                                        >
                                    </td>
                          
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "rfq")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'rfq', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
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
                                        else
                                            echo "value = '" . $today . "'";
                                        ?>
                                        
                                        />
                                        </div>
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                        <input type="text" name="by"  class="form-control" maxlength="100" width="80%" placeholder="Enter name"
                                        value="
                                        <?php
                                        if (NULL!=Input::old('by'))
                                            echo Input::old('by');
                                        
                                        
                                        ?>
                                        ">
                                    </td>
                                  
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "dateby")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'dateby', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                <td class="edit-pr-input" colspan="2"> 
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
                                <td class="edit-pr-input" colspan="2">
                                    <input type ="text" name="assignee" placeholder="Enter name" class="form-control" width="100%" maxlength="100" 
                                    placeholder="Enter name"
                                    value="
                                        <?php
                                        if (NULL!=Input::old('assignee'))
                                            echo Input::old('assignee');
                                       
                                        ?>
                                        "
                                    >
                                </td>
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    @if($tasks->taskType == "datebyremark")
                            <?php $myForm = 'myForm_' . $taskc->id; ?>
                            {{Form::open(['url'=>'datebyremark', 'id' => $myForm], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                 <Input type="hidden" name="pr_id" value="{{$purchaseToEdit->id}}" );>
                                
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
                                <td class="edit-pr-input">
                                    <input type ="text" name="assignee" placeholder="Enter name" class="form-control" width="100%" maxlength="100" placeholder="Enter name"
                                    value="
                                        <?php
                                        if (NULL!=Input::old('assignee'))
                                            echo Input::old('assignee');
                    
                                        ?>
                                        "
                                    >
                                </td>
                                <td class="edit-pr-input" colspan="2">
                                    <input type="text" name="remarks"  class="form-control" maxlength="255" width="100%"
                                    value="
                                        <?php
                                        if (NULL!=Input::old('remarks'))
                                            echo Input::old('remarks');
                                        
                                        ?>
                                        "
                                    >
                                </td>
                                </tr>
                                <tr class="current-task">
                                <td colspan="4" style="border-right: none"></td>
                                <td style="border-left: none; text-align: center;">
                                
                                    <input type="button" class="btn btn-success" value="Submit" @if(Session::get('goToChecklist'))  autofocus  @endif data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $taskc->id }})"> 
                                </td>
                            {{Form::close()}}
                    @endif
                    <!--End Task Forms-->
                    <?php
                    }
                        
                    //END Cursor Open Form        
                    else
                    {
                        
                        echo "<tr>";

            
                        if($tasks->taskType!="cheque"&&$tasks->taskType!="published"&&$tasks->taskType!="contract"&&$tasks->taskType!="meeting"&&$tasks->taskType!="rfq"&&$tasks->taskType!="documents"&&$tasks->taskType!="evaluation"&&$tasks->taskType!="preparation")
                        {
                            echo "<td>";
                            echo $tasks->taskName."</td>";
                        }
                        
                        //Task Display
                        
                      
                        ?>

                        @if ($tasks->taskType=="normal")
                            <td>
                                <?php
                                if($taskp->assignee!=NULL)
                                { 
                                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                                    echo $dassignee;
                                }
                                else if($taskp->assignee_id!=0)
                                {
                                    $assign_user=User::find($taskp->assignee_id);
                                    echo $assign_user->lastname.", ".$assign_user->firstname;
                                }
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                            ?>
                            </td>

                            <td >
                            <?php 
                                if($taskp->dateFinished!="0000-00-00 00:00:00") 
                                    echo $datef; 
                            ?>
                            </td>
                            <td>
                            <?php 
                                if($taskp->dateFinished!="0000-00-00 00:00:00") 
                                    echo $taskp->daysOfAction; 
                            ?>
                            </td>
                            <td>
                            <?php 
                                $dremarks=chunk_split($taskp->remarks, 20, "<br>");
                                echo $dremarks; 
                            ?>
                            </td>
                        @endif
                        @if($tasks->taskType=="certification")
                            <td colspan="2">
                                <input type="radio" name="displayradio" value="yes" 
                                <?php if($taskp->custom1=="yes") echo " checked";?> 
                                disabled > Yes 
                                <input type="radio" name ="displayradio" value="no" 
                                <?php if($taskp->custom1=="no") echo " checked";?>
                                disabled> No
                            </td>
                            <td colspan="2">
                                By:         
                                {{$taskp->custom2;}}
                            </td>
                        @endif
                        @if($tasks->taskType=="posting")
                            <td colspan="2">
                                Reference No. :          
                                {{$taskp->custom1;}}
                            </td>
                             <td>
                                Date:           
                                {{$taskp->custom2;}}
                            </td>
                            <td>
                                By:          
                                {{$taskp->custom3;}}
                            </td>
                        @endif
                        @if($tasks->taskType == "supplier")
                                <td class="edit-pr-input" colspan="2" width="20%">
                                    {{$taskp->custom1}}
                                </td>
                                
                                <td class="edit-pr-input" colspan="2" width="20%">
                                    Amount: 
                                    {{$taskp->custom2}}
                                </td>     
                        @endif
                        @if($tasks->taskType=="cheque")
                                <td class="edit-pr-input" colspan="2">
                                    CHEQUE AMT:
                                    {{$taskp->custom1}}
                                </td>
                                <td class="edit-pr-input" colspan="2">
                                    CHEQUE NUM:
                                    {{$taskp->custom2}}
                                </td>
                                <td class="edit-pr-input" colspan="2">
                                    CHEQUE DATE:
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
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        {{$taskp->custom1}}
                                        <span class="add-on"><i class="icon-th"></i></span>
                                    </td>
                                    <td>{{$taskp->custom2}}</td>
                                    <td class="edit-pr-input" colspan="2">{{$taskp->custom3}}</td>
                        @endif
                        @if($tasks->taskType=="documents")
                                    <td> </td>
                                    <th class='workflow-th'>Eligibility Documents:</th>
                                    <th class='workflow-th'>Date of Bidding:</th>
                                    <th class='workflow-th' colspan="2">Checked By:</th>
                                </tr>
                                <tr>
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        {{$taskp->custom1}}
                                        <span class="add-on"><i class="icon-th"></i></span>
                                    </td>
                                    <td>{{$taskp->custom2}}</td>
                                    <td class="edit-pr-input" colspan="2">{{$taskp->custom3}}</td>
                                    <!--td>
                                    {{$tasks->taskName}}
                                    <br>
                                    {{$taskp->custom1}}
                                    <span class="add-on"><i class="icon-th"></i></span>
                                    </td>
                                    <td>
                                    Date of Bidding
                                    <br>
                                    {{$taskp->custom2}}
                                    </td>
                                    <td >
                                    Checked By
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                    {{$taskp->custom3}}
                                    </td-->
                        @endif
                        @if($tasks->taskType=="evaluation")
                                    <td> </td>
                                    <th class='workflow-th' colspan="2">Date:</th>
                                    <th class='workflow-th' colspan="2">No. Of Days Accomplished:</th>
                                </tr>
                                <tr>
                                    <td>{{$tasks->taskName}}</td>
                                    <td colspan="2">
                                        {{$taskp->custom1}}
                                    </td>
                                    <td class="edit-pr-input" colspan="2"> {{$taskp->custom2}}</td>
                                    <?php
                                    $sectiondays=$sectiondays+$taskp->custom2;
                                    $prdays=$prdays+$taskp->custom2;
                                    ?>

                        @endif
                        @if($tasks->taskType=="conference")
                                    <td colspan="4">
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
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        {{$taskp->custom1}}
                                    </td>
                                    <td class="edit-pr-input">  
                                        {{$taskp->custom2}}
                                    <?php
                                    $sectiondays=$sectiondays+$taskp->custom2;
                                    $prdays=$prdays+$taskp->custom2;
                                    ?>
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                        {{$taskp->custom3}}
                                    </td>

                        @endif
                        @if($tasks->taskType=="meeting")
                                    <td> </td>
                                    <th class='workflow-th'>Date:</th>
                                    <th class='workflow-th'>No. of Days Accomplished:</th>
                                    <th class='workflow-th' colspan="2">Minutes of Bidding:</th>
                                </tr>
                                <tr>
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        <?php 
                                        $today = date("m/d/y");
                                        ?>
                                        {{$taskp->custom1}}
                                    </td>
                                    <td class="edit-pr-input">  
                                        {{$taskp->custom2}}
                                    <?php
                                    $sectiondays=$sectiondays+$taskp->custom2;
                                    $prdays=$prdays+$taskp->custom2;
                                    ?>
                                    </td>
                                    <td class="edit-pr-input" colspan="2">  
                                        {{$taskp->custom3}}
                                    </td>

                        @endif
                        @if($tasks->taskType=="rfq")
                                    <td> </td>
                                    <th class='workflow-th'>No. of Supplier:</th>
                                    <th class='workflow-th'>Date of RF (Within PGEPS 7 Days):</th>
                                    <th class='workflow-th' colspan="2">By:</th>
                                </tr>
                                <tr>
                                    <td>{{$tasks->taskName}}</td>
                                    <td>
                                        {{$taskp->custom1}}
                                    </td>
                                    <td>
                                        {{$taskp->custom2}}
                                    </td>
                                    <td class="edit-pr-input" colspan="2"> {{$taskp->custom2}}</td>
                        @endif
                        @if($tasks->taskType=="dateby") 
                            <td colspan="2">
                            <?php 
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                                if($taskp->dateFinished!="0000-00-00 00:00:00") 
                                    echo $datef; 
                            ?>
                            </td>
                            <td colspan="2">
                                <?php
                                if($taskp->assignee!=NULL)
                                { 
                                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                                    echo $dassignee;
                                }
                                else if($taskp->assignee_id!=0)
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
                           <td >
                            <?php 
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                                if($taskp->dateFinished!="0000-00-00 00:00:00") 
                                    echo $datef; 
                            ?>
                            </td>
                            <td>
                                <?php
                                if($taskp->assignee!=NULL)
                                { 
                                    $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                                    echo $dassignee;
                                }
                                else if($taskp->assignee_id!=0)
                                {
                                    $assign_user=User::find($taskp->assignee_id);
                                    echo $assign_user->lastname.", ".$assign_user->firstname;
                                }
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                            ?>
                            </td>
                            <td colspan="2">
                            <?php 
                                $dremarks=chunk_split($taskp->remarks, 20, "<br>");
                                echo $dremarks; 
                            ?>
                            </td>
                        @endif

                        <?php 
                        //End Task Display
                        $sectiondays=$sectiondays+$taskp->daysOfAction;
                        $prdays=$prdays+$taskp->daysOfAction;

                        
                    }   
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
                            $valuesc=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchaseToEdit->id)->count();
                            $values=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchaseToEdit->id)->first();    
                             ?>
                            @if ($valuesc==0) 
                                {{Form::open(['url'=>'insertaddon'], 'POST')}}
                                    <input type="hidden" name="otherDetails_id" value="{{$otherdetails->id}}">
                                    <input type="hidden" name="purchase_request_id" value="{{$purchaseToEdit->id}}">
                                    <td colspan="3"><input name ="value" type="text" class="form-control" maxlength="100"></td>
                                    <td align="center"><button class ="btn btn-primary">Save</button></td>
                                {{Form::close()}}
                            @else 
                                <td width='48.5%' colspan='3'>{{$values->value}}</td>

                                {{Form::open(['url'=>'editaddon', 'POST'])}}
                                    
                                    <input type='hidden' name='values_id' value="{{$values->id}}">
                                    <td align='center'>
                                        <button class ='btn btn-default'>Edit</button>
                                    </td>
                
                                {{Form::close()}}
                                </tr>
                            @endif
                    <?php
                           
                            }   
                        
                        }
                    //End of Addon Display

                    }

                    ?>

                    @if($workflow->workFlowName!="Direct Contracting")
                    <tr>
                            <td>TOTAL NO. OF DAYS</td>
                            <!-- <td></td>
                            <td></td>
                            <td></td> -->
                            <td colspan="4"><center>{{$sectiondays}}</center></td>
                    </tr>
                    @endif
                    </table></div></div>
                    
                    <?php
                }
                echo "<div class='panel panel-success'><div class='panel-body'>
                        <table border='1' class='proc-details'>
                            <tr>
                                <td width='66%'><h4 style='margin-left: 10px'>TOTAL NO. OF DAYS FROM PR TO PAYMENT: </h4></td>
                                <td><h4 style='margin-left: 50px;'>".$prdays."</h4></td>
                            </tr>
                        </table>
                    </div></div>"; 
            ?>

        </div>
    </div>

            {{Session::forget('errorlabel')}}
            {{Session::forget('successlabel')}}
            {{Session::forget('errorchecklist')}}
            {{Session::forget('successchecklist')}}
            {{Session::forget('goToChecklist')}}

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
            <button type="button" class="btn btn-success" id="confirmModal" value="Submit">Submit</button>
          </div>
        </div>
      </div>
    </div>
    <!-- CODES FOR MODAL END -->
@stop

@section('footer')
              
    <script type="text/javascript">
    // JS CODE FOR MODAL START
        
        $('#confirmDelete').find('.modal-footer #confirmModal').on('click', function()
        {
            var name = "myForm_" + window.my_id; 
            document.getElementById(name).submit();
        });

        function hello(pass_id)
        {
            window.my_id = pass_id;
        }

        ///////////////
        $('#confirmDelete').on('shown.bs.modal', function () {
            $(document).keypress(function(e){
                if (e.which == 13){
                    $("#confirmModal").click();
                }
            });
        });
        // alert($('#myModal').hasClass('in'));
    // JS CODE FOR MODAL END

    $('input[type=file]').bootstrapFileInput();
    $('.file-inputs').bootstrapFileInput();
    function numberWithCommas(amount) 
    {
        amount = amount.replace(',','');    
        var its_a_number = amount.match(/^[0-9,.]+$/i);
        if (its_a_number != null)
        {
            decimal_amount = parseFloat(amount).toFixed(2);
            if(decimal_amount == 0 || decimal_amount == "0.00")
            {
                document.getElementById("num").value = "1.00";
            }
            else
            {
                var parts = decimal_amount.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                parts =  parts.join(".");
                document.getElementById("num").value = parts;
                window.old_amount = parts; 
            }
        }
        else if(!window.old_amount)
        {
            document.getElementById("num").value = "1.00";
        }
        else
        {
            document.getElementById("num").value = window.old_amount;
        }


        newamount =    amount; 
        if (newamount<50000)
            document.getElementById("modeOfProcurement").selectedIndex = 1;
        else if (newamount>=50000 )
        {
            if (newamount<=500000)
                document.getElementById("modeOfProcurement").selectedIndex = 2;

            else if (newamount>500000)
                document.getElementById("modeOfProcurement").selectedIndex = 3;

        }
        else
            document.getElementById("modeOfProcurement").selectedIndex = 0;
    }

    $(window).on('load', function () {

        $('.selectpicker').selectpicker({
            'selectedText': 'cat'
        });

            //$('.selectpicker').selectpicker('hide');
        });

    $("#requisitioner").chainedTo("#office");
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

    $('.datepicker').datepicker();

</script>

<script type="text/javascript">
    // When the document is ready
    $(document).ready(function () {
            
        $('.input-daterange').datepicker({
            todayBtn: "linked"
        });
    });
</script>

<script>
function autouploadsaved(value)
    {
    var formname= "createform";
    var text= "/autouploadsaved";
    

    $("#createform").attr('action', text); 
    document.getElementById(formname).submit();
    }
    function checklist_changeAmount(id,amount)
    {
       amount = amount.replace(',',''); 
        var its_a_number = amount.match(/^[0-9,.]+$/i);
        if (its_a_number != null)
        {
            decimal_amount = parseFloat(amount).toFixed(2);
            if(decimal_amount == 0 || decimal_amount == "0.00")
            {
                document.getElementById(id).value = "0.00";
                window.old_amount = 0.00; 
            }
            else
            {
                var parts = decimal_amount.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                parts =  parts.join(".");
                if(parts == "NaN")
                {
                    document.getElementById(id).value = "0.00";
                    window.old_amount = 0.00; 
                }
                else
                {
                    document.getElementById(id).value = parts;
                    window.old_amount = parts;
                }
                     
            }
        }
        else if(!window.old_amount)
        {
            document.getElementById(id).value = "0.00";
            window.old_amount = 0.00; 
            amount = 0;
        }
        else
        {
            document.getElementById(id).value = window.old_amount;
            amount = 0;
        }

    }

    function isNumberKey(evt)
    {
         var charCode = (evt.which) ? evt.which : event.keyCode
        if(charCode == 44 || charCode == 46)
             return true;

        if (charCode > 31 && (charCode < 48 || charCode > 57))
             return false;

        return true;
    }
function delimage(value)
        {
            //alert('form_'+value);
            var formname= "form_"+value;
            document.getElementById(formname).submit();
        }
</script>

@stop
