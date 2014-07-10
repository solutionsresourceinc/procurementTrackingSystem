@extends('layouts.dashboard')

@section('header')
<!-- CSS and JS for Dropdown Search
    {{ HTML::script('drop_search/bootstrap-select.js')}}
    {{ HTML::style('drop_search/bootstrap-select.css')}}
-->
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


@stop



@section('content')




<?php
$pass=0;
$epurchase=Purchase::find($id);
$cuser=Auth::User()->id;


?>


  <?php 
                    //retainer
                    if (Input::old('projectPurpose')||Input::old('sourceOfFund')||Input::old('amount')){
                        $valprojectPurpose=Input::old('projectPurpose');
                        $valsourceOfFund=Input::old('sourceOfFund');
                        $valamount=Input::old('amount'); 
                    }
                    else
                    {
                        $valprojectPurpose=$epurchase->projectPurpose;
                        $valsourceOfFund=$epurchase->sourceOfFund;
                        $valamount=$epurchase->amount; 

                    }
                ?>

<h1 class="page-header">Edit Purchase Request</h1>

<div class="form-create fc-div">
    {{ Form::open(array('url' => 'newedit', 'files' => true), 'POST') }}
     
            <input type="hidden" name ="id" value={{$id}}>
            @if(Session::get('notice'))
                <div class="alert alert-success"> {{ Session::get('notice') }}</div> 
            @endif

            @if(Session::get('main_error'))
                <div class="alert alert-danger"> {{ Session::get('main_error') }}</div> 
            @endif


             @if(Session::get('successchecklist'))
                <div class="alert alert-success"> {{ Session::get('successchecklist') }}{{Session::forget('successchecklist')}}</div> 
            @endif

            @if(Session::get('errorchecklist'))
                <div class="alert alert-danger"> {{ Session::get('errorchecklist') }}</div> 
            @endif
            {{Session::forget('errorchecklist')}}
            {{Session::forget('successchecklist')}}

<?php
if ( Entrust::hasRole('Administrator')) {
                                            $pass=1; }
 else if (Entrust::hasRole('Procurement Personnel'))
                                            {
                                 if($epurchase->created_by==$cuser)  $pass=1;
                                            }

if ($pass==0)

    Redirect::back();





?>
            <div class="form-group">
                <?php
                        $docs=DB::table('document')->where('pr_id', '=',$id )->first();
                        $workflow=DB::table('workflow')->get();
                    ?>
                <div class="row">
                    <div class="col-md-6">
                        
                        {{ Form::label('modeOfProcurement', 'Mode of Procurement *', array('class' => 'create-label')) }}
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
                        @if (Session::get('m6'))
                            <font color="red"><i>The mode of procurement is required field</i></font>
                        @endif
                      
                    </div>

                    <div class="col-md-3">
                        {{ Form::label('status', 'Status: ', array('class' => 'create-label')) }}
                        <input type="text" value="{{$epurchase->status}}" readonly class="form-control">
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

                        {{ Form::label('dispCN', 'Control No. *', array('class' => 'create-label')) }}
                        <input type="text"  name="dispCN"  class="form-control" value="{{
                        $epurchase->controlNo}}"disabled>
                        <input type="hidden" name="controlNo" value="{{
                        $epurchase->controlNo}}">
                    </div>
                </div>
                <br>
              
                <div>
                    {{ Form::label('projectPurpose', 'Project/Purpose *', array('class' => 'create-label')) }}
                    {{ Form::text('projectPurpose',$valprojectPurpose, array('class'=>'form-control')) }}
                </div>

                @if (Session::get('m1'))
                    <font color="red"><i>{{ Session::get('m1') }}</i></font>
                @endif
                <br>            

                <div class="row">
                    <div class="col-md-6">
                        {{ Form::label('sourceOfFund', 'Source of Fund *', array('class' => 'create-label')) }}
                        {{ Form::text('sourceOfFund',$valsourceOfFund, array('class'=>'form-control')) }}
                        @if (Session::get('m2'))
                            <font color="red"><i>{{ Session::get('m2') }}</i></font>
                        @endif
                    </div>

                    
                    <div class="col-md-6">
                        {{ Form::label('amount', 'Amount *', array('class' => 'create-label')) }}
                        {{ Form::text('amount', $epurchase->amount ,array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)','id'=>'num','disabled')) }}
                    </div>
                    @if (Session::get('m3'))
                        <font color="red"><i>{{ Session::get('m3') }}</i></font>
                    @endif


                </div>
                <br>

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
                                        else if($epurchase->office==$key->id)
                                        echo "selected" 
                                    ?>
                                    >{{{ $key->officeName }}}
                                </option>
                            @endforeach
                        </select>
                        @if (Session::get('m4'))
                            <font color="red"><i>{{ Session::get('m4') }}</i></font>
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
                                        else if($epurchase->requisitioner==$key2->id)
                                            echo "selected" 
                                        ?>
                                        >{{ $fullname }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @if (Session::get('m5'))
                            <font color="red"><i>{{ Session::get('m5') }}</i></font>
                        @endif
                  
                    </div>
                </div>

         

                <div class="form-group">
                    {{ Form::label('dateTime', 'Date Requested *', array('class' => 'create-label')) }}
                    <div class="input-group date form_datetime col-md-12" data-date="{{ date('Y-m-d') }}T{{ date('H:i:s') }}Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                        <input id="disabled_datetime" onchange="fix_format()" class="form-control" size="16" type="text" value="<?php
                        if (NULL!=Input::old('dateRequested'))
                            echo Input::old('dateRequested');
                        else
                         echo $epurchase->dateRequested; ?>" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input1" name="dateRequested" value="<?php
                        if (NULL!=Input::old('dateRequested'))
                            echo Input::old('dateRequested');
                        else
                            echo $epurchase->dateRequested; ?>" />
                    @if (Session::get('m7'))
                        <font color="red"><i>{{ Session::get('m7') }}</i></font>
                    @endif
                    <br>
                </div>
            </div>

            <label class="create-label">Related files:</label>
            <div class="panel panel-default fc-div">
                <div class="panel-body" style="padding: 5px 20px;">
                    <br/>

                    @if(Session::get('imgsuccess'))
                        <div class="alert alert-success"> {{ Session::get('imgsuccess') }}</div> 
                    @endif

                    @if(Session::get('imgerror'))
                        <div class="alert alert-danger"> {{ Session::get('imgerror') }}</div> 
                    @endif

                    <?php
                        $document = Document::where('pr_id', $epurchase->id)->first();
                        $doc_id= $document->id;
                    ?>
                    <input name="file[]" type="file"  multiple title="Select image to attach" data-filename-placement="inside"/>
                    <input name="doc_id" type="hidden" value="{{ $doc_id }}">
                    <br>
                    <br>
                </div>
            </div>

            <div><br>
                {{ Form::submit('Save',array('class'=>'btn btn-success')) }}
                {{ link_to( 'purchaseRequest/view', 'Cancel', array('class'=>'btn btn-default') ) }}

            </div>
            
            {{ Form::close() }} 

            <!--  
            Image Module
            -->
            <div id="img-section">

                <?php
                 $attachmentc = DB::table('attachments')->where('doc_id', $doc_id)->count();
                 if ($attachmentc!=0)
                    echo "<h3>"."Attachments"."</h3>";
                    $attachments = DB::table('attachments')->where('doc_id', $doc_id)->get();  
                    $srclink="uploads\\";
                ?>
                @foreach ($attachments as $attachment) 
                    <div class="image-container">
                        <a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="roadtrip">
                        <img class="img-thumbnail" src="{{asset('uploads/'.$attachment->data)}}" style="width: 100px; height: 100px;" /></a>
                        {{ Form::open(array('method' => 'post', 'url' => 'delimage')) }}
                        <input type="hidden" name="hide" value="{{$attachment->id}}">
                        <button class="star-button"><img src="{{asset('img/Delete_Icon.png')}}"></button>
                        {{Form::close()}}
                    </div>
                @endforeach
            <!-- End Image Module-->

                {{ Session::forget('notice'); }}
                {{ Session::forget('main_error'); }}
                {{ Session::forget('m1'); }}
                {{ Session::forget('m2'); }}
                {{ Session::forget('m3'); }}
                {{ Session::forget('m4'); }}
                {{ Session::forget('m5'); }}
                {{ Session::forget('m6'); }}
                {{ Session::forget('m7'); }}
                {{ Session::forget('imgerror'); }}
                {{ Session::forget('imgsuccess'); }}
            </div>

        </div>  
       
        <br>
        <div style="width: 85%;margin: auto;">
            <!-- Section 1  -->
            <?php 
            //Cursor Component
                $taskch= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->count(); 

                $taskc= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->first(); 
                $workflow= Workflow::find($docs->work_id);
                $section= Section::where('workflow_id', $workflow->id)->orderBy('section_order_id','ASC')->get();

                $taskd= TaskDetails::where('doc_id', $docs->id)->orderBy('id', 'ASC')->get();
                $sectioncheck=0;
                $prdays=0;
      
                foreach($section as $sections)
                {
                $sectiondays=0;
                    $task= Task::where('section_id', $sections->section_order_id)->where('wf_id', $workflow->id)->orderBy('order_id', 'ASC')->get();
                    echo "<div class='panel panel-success'><div class='panel-heading'>
                        <h3 class='panel-title'>".$sections->section_order_id.". ".$sections->sectionName."</h3>
                        </div>";
                    //echo "<tr><th colspan='5' >".$sections->section_order_id.". ".$sections->sectionName."</th></tr>";
                    echo "<div class='panel-body'>";
                    echo "<table border='1' class='workflow-table'>";

                    //Addon Display
                    $otherc=OtherDetails::where('section_id', $sections->id)->count();
                    if($otherc!=0){
                        echo "<tr><th class='workflow-th'>Label</th><th class='workflow-th' colspan='3'>Input</th><th class='workflow-th'>Action</th></tr>";

                        $otherd= OtherDetails::where('section_id', $sections->id)->get();
                        foreach ($otherd as $otherdetails) {
                            echo "<tr><td width='30%'>".$otherdetails->label."</td>";
                            $valuesc=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $epurchase->id)->count();
                            $values=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $epurchase->id)->first();    
                            if ($valuesc==0) {
                    ?>
                                {{Form::open(['url'=>'insertaddon'], 'POST')}}
                                    <input type="hidden" name="otherDetails_id" value="{{$otherdetails->id}}">
                                    <input type="hidden" name="purchase_request_id" value="{{$epurchase->id}}">
                                    <td><input name ="value" type="text"></td>
                                    <td><button class ="btn btn-success">Save</button></td>
                                {{Form::close()}}
                    <?php
                            }
                            else {
                                echo "<td width='48.5%' colspan='3'>".$values->value."</td>";
                    ?>
                                {{Form::open(['url'=>'editaddon', 'POST'])}}
                    <?php
                                /*echo"<input type='hidden' name='otherDetails_id' value='".$otherdetails->id."'>
                                <input type='hidden' name='purchase_request_id' value='".$epurchase->id."'>";
                                echo "<td>"."<button class ='btn btn-success'>Edit</button>"."</td>";*/
                                echo"<input type='hidden' name='values_id' value='".$values->id."'>";
                                echo "<td colspan='2'>"."<button class ='btn btn-success'>Edit</button>"."</td>";
                    ?>
                                {{Form::close()}}
                    <?php
                            }
                            echo "</tr>";
                        }   
                    }
                    //End of Addon Display

                    echo " <tr><th width='30%'></th>";
                    echo "<th class='workflow-th' width='18%'>By:</th>";
                    echo "<th class='workflow-th' width='18%'>Date:</th>";
                    echo "<th class='workflow-th' width='12.5%'>Days of Action</th>";
                    echo "<th class='workflow-th'>Remarks</th></tr>";
                    foreach ($task as $tasks) {
                    //Cursor Open form 
                        //Displayer 
                        $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();

                        echo "<tr><td>".$tasks->order_id.". ".$tasks->taskName."</td>";

                        //if ($taskc->task_id==$tasks->id && $tasks->designation_id==0){
                        if ($taskch!=0 && $taskc->task_id==$tasks->id && $tasks->designation_id==0){
             ?>
                            {{Form::open(['url'=>'checklistedit'], 'POST')}}
                                <input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
                                <td class="edit-pr-input"><input type ="text" name="assignee" class="form-control" width="100%"></td>
                                <td class="edit-pr-input"> <input class="datepicker" size="16" type="text" name="dateFinished" class="form-control" value="12/02/2012" width="100%">
                                  <span class="add-on"><i class="icon-th"></i></span>
                                </td>
                                <td class="edit-pr-input">
                                <input type="number" name="daysOfAction" class="form-control"  min="0" width="100%">
                                </td>
                                <td class="edit-pr-input">
                                <input type="text" name="remarks"  class="form-control" maxlength="255" width="100%">
                                </td>

                                </tr><tr>
                                <td colspan="5" > <br><center> <input type="submit" class="btn btn-success"> <center><br></td>
                            {{Form::close()}}
            <?php       }
            //END Cursor Open Form
                        else{
            ?>

                            <td ><?php
                                if($taskp->assignee!=NULL)
                                  { $dassignee=chunk_split($taskp->assignee, 20, "<br>");
                                    echo $dassignee; }
                                else if($taskp->assignee_id!=0){
                                    $assign_user=User::find($taskp->assignee_id);
                                echo $assign_user->lastname.", ".$assign_user->firstname;
                                }
                                $date = new DateTime($taskp->dateFinished);
                                $datef = $date->format('m/d/y');
                            ?></td>

                            <td ><?php if($taskp->dateFinished!="0000-00-00 00:00:00") echo $datef ?></td>
                            <td ><?php if($taskp->dateFinished!="0000-00-00 00:00:00") echo $taskp->daysOfAction; ?></td>
                            <td ><?php 
                                $dremarks=chunk_split($taskp->remarks, 20, "<br>");
                                    
                                echo $dremarks; ?>
                            </td>
            <?php $sectiondays=$sectiondays+$taskp->daysOfAction;
            $prdays=$prdays+$taskp->daysOfAction;
                        }   
                        echo "</tr>";
                    }
                    echo "<tr><td>Total No. of Days</td><td>".$sectiondays."</td></tr>";
                    echo "</table></div></div>";
            
            }
              echo "<div class='form-create fc-div'><h4>Total No. of Days: ".$prdays." </h4></div>";   
?>
            <!-- Section 1  -->
        </div>
    </div>
@stop


@section('footer')
    <script type="text/javascript">
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

@stop
