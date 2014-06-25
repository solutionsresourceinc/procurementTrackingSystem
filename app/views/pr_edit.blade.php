@extends('layouts.login')



@section('content')
    <?php $p_id = Purchase::find($id); ?>
    <div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Delete Attachment</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure about this ?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm">Delete</button>
      </div>
    </div>
  </div>
</div>

    <h1 class="page-header">Edit Purchase Request</h1>  

    <form method="POST" action="edit"  class = "form-create">
        <fieldset>
            <input type="hidden" name="purch_id" id="purch_id" value="{{{ $p_id->id }}}">
           
            @if(Session::get('notice'))
                <div class="alert alert-success"> {{ Session::get('notice') }}</div> 
            @endif

            @if(Session::get('main_error'))
                <div class="alert alert-danger"> {{ Session::get('main_error') }}</div> 
            @endif

            <div class="form-group">
                <label for="projectPurpose">Project/Purpose</label>
                <input class="form-control"  type="text" name="projectPurpose" id="projectPurpose" value="{{{ $p_id->projectPurpose }}}" required>
                @if (Session::get('m1'))
                    <font color="red"><i>{{ Session::get('m1') }}</i></font>
                @endif
            </div>

            <div class="form-group">
                <label for="sourceOfFund">Source of Funds</label>
                <input class="form-control" type="text" name="sourceOfFund" id="sourceOfFund" value="{{{ $p_id->sourceOfFund }}}" required>
                @if (Session::get('m2'))
                    <font color="red"><i>{{ Session::get('m2') }}</i></font>
                @endif
            </div>

            <div class="form-group">
                <label for="amount">Amount</label>
               
                <input  onchange="formatCurrency(this);"  class="form-control"  type="text" name="amount" id="amount"  required>

                @if (Session::get('m3'))
                    <font color="red"><i>{{ Session::get('m3') }}</i></font>
                @endif
            </div>
            
            <div class="form-group" id="template">
                <label for="office">Office</label>
                <select id="office" name="office" class="form-control">
                <?php 
                    //$offices = DB::table('offices')->lists('officeName');
                    $offices = DB::table('offices')->get();
                ?>
                @foreach($offices as $office)
                    @if($p_id->office ==  $office)
                        <option value="{{{ $office->id }}}" selected>{{$office->officeName;}}</option>
                    @else
                        <option value="{{{ $office->id }}}">{{$office->officeName;}}</option>
                    @endif
                @endforeach
            </select>
            </div>

            <div class="form-group" id="template">
                <label for="requisitioner">Requisitioner</label>
                <select id="requisitioner" name="requisitioner" class="form-control">
                    <?php $requi = DB::table('users')->get(); ?>
                    @foreach ($requi as $requis)
                        @if($requis->office_id != 0)
                            @if($requis->id == $p_id->requisitioner )
                                <option value="{{{ $requis->id }}}" class="{{{ $requis->office_id }}}" selected> {{{ $requis-> firstname }}} </option>
                            @else
                                <option value="{{{ $requis->id }}}" class="{{{ $requis->office_id }}}" > {{{ $requis-> firstname }}} </option>
                            @endif
                        @endif
                    @endforeach
                </select>
            </div>
             
            <div class="form-group">
                <label for="modeOfProcurement">Mode of Procurement</label>
                <select class="form-control" name="modeOfProcurement" id="modeOfProcurement">
                <?php $flows = DB::table('workflow')->get(); ?>
                    @foreach($flows as $flow)
                        <option value="{{{ $flow->id }}}">{{{ $flow->workFlowName }}}</option>
                    @endforeach
                </select>
                @if (Session::get('m4'))
                    <font color="red"><i>{{ Session::get('m4') }}</i></font>
                @endif
            </div>

            <div class="form-group">
                <label for="ControlNo">Control No.</label>
                <input class="form-control"  type="text" name="ControlNo" id="ControlNo" value="{{{ $p_id->ControlNo }}}" required>
                @if (Session::get('m5'))
                    <font color="red"><i>{{ Session::get('m5') }}</i></font>
                @endif
            </div>
          
            <div class="form-actions form-group">
                <button type="submit" class="btn btn-default" name="submit">Save</button>
            </div>
        </fieldset>
    </form>
<div >
<br>
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="/attach/{{$p_id->id}}">
<button class="btn btn-primary">
    Add Attachments
</button>
<br>
<br>
</a>
</div>
    
    <?php
function data_uri($image, $mime) 
{  

  $base64   = base64_encode($image); 
  return ('data:' . $mime . ';base64,' . $base64);


}
    ?>

<div id="img-section">

<?php
$doc = DB::table('document')->where('pr_id', $p_id->id)->get();
?>
@foreach($doc as $docs)
<?php
 $attachments = DB::table('attachments')->where('doc_id', $docs->id)->get(); 
?>

@foreach ($attachments as $attachment) 


<div id="img-per" >
                    <img data-src="holder.js/200x200" class="img-thumbnail" alt="200x200" src="<?php echo data_uri( $attachment->data, 'image/png'); ?>" style="width: 200px; height: 200px;" >
<form method="POST" action="delimage"/ id="myForm_{{ $attachment->id }}" name="myForm">
                                            <input type="hidden" name="hide" value="{{ $attachment->id }}">
                                            <center>
                                            <button class="button"  type="button" data-toggle="modal" data-target="#confirmDelete" onclick="hello( {{ $attachment->id }})"  data-title="Delete Attachment" data-message="Are you sure you want to delete attachment?">Delete</button>
                                            </center>
                                        </form>
</div>
@endforeach
@endforeach
<?php



 ?>

</div>


    <?php

        {{ Session::forget('notice'); }}
        {{ Session::forget('main_error'); }}
        {{ Session::forget('m1'); }}
        {{ Session::forget('m2'); }}
        {{ Session::forget('m3'); }}
        {{ Session::forget('m4'); }}
        {{ Session::forget('m5'); }}
    ?>

  <script type="text/javascript">

function formatCurrency(fieldObj)
{
    if (isNaN(fieldObj.value)) { return false; }
    fieldObj.value = '' + parseFloat(fieldObj.value).toFixed(2);
    return true;
}

  $('#confirmDelete').on('show.bs.modal', function (e) {

      $message = $(e.relatedTarget).attr('data-message');
  $(this).find('.modal-body p').text($message);

  $title = $(e.relatedTarget).attr('data-title');
  $(this).find('.modal-title').text($title);

      var form = $(e.relatedTarget).closest('form');

      $(this).find('.modal-footer #confirm').data('form', form);

  });

  $('#confirmDelete').find('.modal-footer #confirm').on('click', function(){

     //$(this).data('form').submit();
      var name = "myForm_" + window.my_id; 
      document.getElementById(name).submit();
     //alert(name);
  });
  function hello(pass_id)
  {
      window.my_id = pass_id;
     // alert(window.my_id);
  }

        $("#requisitioner").chained("#office");
    </script>

@stop

@section('footer')

 
@stop