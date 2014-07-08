@extends('layouts.dashboard')

@section('header')
<!-- CSS and JS for Dropdown Search
    {{ HTML::script('drop_search/bootstrap-select.js')}}
    {{ HTML::style('drop_search/bootstrap-select.css')}}
-->
{{ HTML::style('date_picker/bootstrap-datetimepicker.min.css')}}
{{ HTML::script('date_picker/bootstrap-datetimepicker.js') }}
{{ HTML::script('date_picker/bootstrap-datetimepicker.fr.js') }}

<!--Image Display-->

{{ HTML::script('js/lightbox.min.js') }} 
{{ HTML::style('css/lightbox.css')}}
<!--End Image Display-->

{{ HTML::script('js/jquery.chained.min.js') }} 
{{ HTML::script('js/bootstrap.file-input.js') }} 


@stop



@section('content')

<?php
$epurchase=Purchase::find($id);
?>

<h1 class="page-header">Edit Purchase Request</h1>

<div class="form-create fc-div">
    {{ Form::open(array('url' => 'newedit', 'files' => true), 'POST') }}
    <div class="row">
        <div>   
            <input type="hidden" name ="id" value={{$id}}>
            @if(Session::get('notice'))
                <div class="alert alert-success"> {{ Session::get('notice') }}</div> 
            @endif

            @if(Session::get('main_error'))
                <div class="alert alert-danger"> {{ Session::get('main_error') }}</div> 
            @endif

            <div class="form-group">
                <div>
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
                <br>
                <div>
                    <?php
                        $docs=DB::table('document')->where('pr_id', '=',$id )->first();
                        $workflow=DB::table('workflow')->get();
                    ?>

                    {{ Form::label('modeOfProcurement', 'Mode of Procurement *', array('class' => 'create-label')) }}
                    <select  name="modeOfProcurement" id="modeOfProcurement" class="form-control" data-live-search="true">
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
                    <br>
                </div>
                <br>
                <div>
                    {{ Form::label('status', 'Status: ', array('class' => 'create-label')) }}
                    <input type="text" value="{{$epurchase->status}}" readonly class="form-control">
                </div>
                <br>
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
                <div>
                    {{ Form::label('projectPurpose', 'Project/Purpose *', array('class' => 'create-label')) }}
                    {{ Form::text('projectPurpose',$valprojectPurpose, array('class'=>'form-control')) }}
                </div>

                @if (Session::get('m1'))
                    <font color="red"><i>{{ Session::get('m1') }}</i></font>
                @endif
                <br>            

                <div>
                    {{ Form::label('sourceOfFund', 'Source of Fund *', array('class' => 'create-label')) }}
                    {{ Form::text('sourceOfFund',$valsourceOfFund, array('class'=>'form-control')) }}
                </div>

                @if (Session::get('m2'))
                    <font color="red"><i>{{ Session::get('m2') }}</i></font>
                @endif
                <br>

                <div>
                    {{ Form::label('amount', 'Amount *', array('class' => 'create-label')) }}
                    {{ Form::text('amount',$valamount,array('class'=>'form-control','onchange'=>'numberWithCommas(this.value)','id'=>'num')) }}
                </div>

                @if (Session::get('m3'))
                    <font color="red"><i>{{ Session::get('m3') }}</i></font>
                @endif
                <br>
                <?php 
                    $office= DB::table('offices')->get();
                ?>
                <div class="form-group" id="template">
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
                    <br>
                </div>

                <div class="form-group" id="template">
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
                    <br>
                </div>

                <br>

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
                    <!--h2>Attachments</h2>
                    <br-->

                    @if(Session::get('imgsuccess'))
                        <div class="alert alert-success"> {{ Session::get('imgsuccess') }}</div> 
                    @endif

                    @if(Session::get('imgerror'))
                        <div class="alert alert-danger"> {{ Session::get('imgerror') }}</div> 
                    @endif

                    <br>

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
        </div>  
    </div>      
    {{ Form::close() }} 

    <!--  
    Image Module
    -->

    <div id="img-section">

        <?php
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

    </div>
</div>

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
 
<div class="form-create fc-div">
<!-- Section 1  -->
<?php 
//Cursor Component
$taskc= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->first(); 
$workflow= Workflow::find($docs->work_id);
$section= Section::where('workflow_id', $workflow->id)->orderBy('section_order_id','ASC')->get();

$taskd= TaskDetails::where('doc_id', $docs->id)->orderBy('id', 'ASC')->get();
$sectioncheck=0;
echo "<table border='1'>";
foreach ($section as $sections) {   
$task= Task::where('section_id', $sections->section_order_id)->where('wf_id', $workflow->id)->orderBy('order_id', 'ASC')->get();
echo "<tr><th colspan='5' ><h3>".$sections->section_order_id.". ".$sections->sectionName."</h3></th></tr>";
echo " <tr><th></th><th>By:</th><th>Date:</th><th>Days of Action</th><th>Remarks</th></tr>";
foreach ($task as $tasks) {
//Cursor Open form 
    //Displayer 
    $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();

    echo "<tr><td>".$tasks->order_id.". ".$tasks->taskName."</td>";

if ($taskc->task_id==$tasks->id && $tasks->designation_id==0){
    ?>
{{Form::open(['url'=>'checklistedit'], 'POST')}}
<input type="hidden" name="taskdetails_id" value="{{$taskc->id}}">
<td><input type ="text" name="assignee"></td>
<td><div class="input-group date form_datetime col-md-12" data-date="{{ date('Y-m-d') }}T{{ date('H:i:s') }}Z" data-date-format="dd MM yyyy - HH:ii p" data-link-field="dtp_input1">
                        <input id="disabled_datetime" onchange="fix_format()" class="form-control" size="16" type="text" value="<?php
                        if (NULL!=Input::old('dateRequested'))
                            echo Input::old('dateRequested');
                        else
                         echo $epurchase->dateRequested; ?>" readonly>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                        <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
                    </div>
                    <input type="hidden" id="dtp_input1" name="dateRequested" >
                </td>
                <td>
<input type="number" name="daysOfAction">
</td>
<td>
    
<input type="text" name="remarks">

</td>
{{Form::close()}}
</tr><tr>
<td colspan="5"> <br><center> <input type="submit" class="btn btn-success"> <center><br></td>
<?php }
//END Cursor Open Form
else{
?>
<td>{{$taskp->assignee}}</td>
<td><?php if($taskp->daysOfAction!=0) echo $taskp->updated_at; ?></td>
<td><?php if($taskp->daysOfAction!=0) echo $taskp->daysOfAction; ?></td>
<td><?php echo $taskp->remarks; ?></td>
<?php
}
echo "</tr>";
}


}
echo "</table>";
?>

<!-- Section 1  -->

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


    </script>

    <!-- js for chained dropdown -->
@stop
