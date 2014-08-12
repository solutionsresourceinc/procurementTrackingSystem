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
  //Initializers
  error_reporting(0);
  $taskdetails_id=Session::get('taskdetails_id');
  Session::forget('taskdetails_id');
  $taskd =TaskDetails::find($taskdetails_id);
  $task= Task::find($taskd->task_id);
  $doc= Document::find($taskd->doc_id);
  $purchase = Purchase::find($doc->pr_id);
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