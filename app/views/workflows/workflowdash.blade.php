@extends('layouts.dashboard')

@section('header')
    {{ HTML::style('ios_overlay/css/iosOverlay.css')}}

    <style type="text/css">
        #description {
            height: 400px;
            top: calc(50% - 200px) !important;
            overflow: hidden;
        }
    </style>
@stop

@section('content')
    <?php include('app/views/workflows/delete_task.blade.php'); ?>
    <!--CODE REVIEW:
        - remove comments
    -->

	<!-- Creates the form -->
    <h1 class="page-header">Workflows</h1>
    <!--div>
        <a href="{{ URL::to('workflow/belowFifty') }}" class="btn btn-success">Below P50,000</a>
        <a href="{{ URL::to('workflow/aboveFifty') }}" class="btn btn-success">Between P50,000 and P500,000</a>
        <a href="{{ URL::to('workflow/aboveFive') }}" class="btn btn-success">Above P500,000</a>
        <br><br><br>
	</div-->
    <ul class="nav nav-tabs" role="tablist" id="myTab">
      <li class="active"><a href="#home" role="tab" data-toggle="tab">SVP (Below P50,000)</a></li>
      <li><a href="#profile" role="tab" data-toggle="tab">SVP (P50,000 and P499,000)</a></li>
      <li><a href="#messages" role="tab" data-toggle="tab">Bidding</a></li>
      <li><a href="#pakyaw" role="tab" data-toggle="tab">Pakyaw</a></li> <!-- FOR PAKYAW WORKFLOW -->
      <li><a href="#directContracting" role="tab" data-toggle="tab">Direct Contracting</a></li> <!-- FOR DIRECT CONTRACTING WORKFLOW -->
    </ul>

    <div class="modal fade" id="description" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><b>Description</b></h4>
                </div>
                <center>
                    <div class="modal-body" id="description_body">
                        <!-- Insert Data Here -->
                    </div>
                </center>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                </div>
            </div>
        </div>
    </div>
            @if(Session::get('successlabel'))
                <div class="alert alert-success"> {{ Session::get('successlabel') }}</div> 
            @endif

            @if(Session::get('errorlabel'))
                <div class="alert alert-danger"> {{ Session::get('errorlabel') }}</div> 
            @endif
                 {{Session::forget('errorlabel')}}
            {{Session::forget('successlabel')}}
    <div class="tab-content">
      <div class="tab-pane fade in active" id="home">@include('workflows.workflow1')</div>
      <div class="tab-pane fade" id="profile">@include('workflows.workflow2')</div>
      <div class="tab-pane fade" id="messages">@include('workflows.workflow3')</div>
      <div class="tab-pane fade" id="pakyaw">@include('workflows.workflow4')</div> <!-- FOR PAKYAW WORKFLOW -->
      <div class="tab-pane fade" id="directContracting">@include('workflows.workflow5')</div> <!-- FOR DIRECT CONTRACTING WORKFLOW -->
    </div>

    
@stop

@section('footer')
                {{ HTML::script('ios_overlay/js/iosOverlay.js') }}
                {{ HTML::script('ios_overlay/js/prettify.js') }}
                {{ HTML::script('ios_overlay/js/custom.js') }}

                {{ HTML::script('js/bootstrap-ajax.js');}}
                <script>
                  $(function () {
                    $('#myTab a:first').tab('show')
                  });
                  $(function() { 
                      //for bootstrap 3 use 'shown.bs.tab' instead of 'shown' in the next line
                      $('a[data-toggle="tab"]').on('click', function (e) {
                        //save the latest tab; use cookies if you like 'em better:
                        localStorage.setItem('lastTab', $(e.target).attr('href'));
                      });

                      //go to the latest tab, if it exists:
                      var lastTab = localStorage.getItem('lastTab');

                      if (lastTab) {
                          $('a[href="'+lastTab+'"]').click();
                      }
                    });
                </script>
                <script>
                function empty_div()
                {
                    document.getElementById("description_body").innerHTML = "";
                }

                </script>
                <script type = "text/javascript">
                $(document).ready(function() {
                    $(".mode2").hide();
                    $(".allow-edit").on("click", function() {
                        var current = $(this).closest("tr").find(".current-text");
                        var textfield = $(this).closest("tr").find(".edit-text");
                        var text = current.text().trim();
                        current.hide();
                        textfield.val(text);
                        textfield.attr({"placeholder": text, "value": text}).show().focus();
                        $(this).closest("tr").find(".mode1").hide();
                        $(this).closest("tr").find(".mode2").show();
                        var a = $(this).closest("tr").find("#none").attr('selected',true);
                        //alert(a);
                       // document.getElementById('designa').selectedIndex = 0;
                    });

                    $(".save-edit").on("click", function(event) {
                        var current = $(this).closest("tr").find(".current-text");
                        var textfield = $(this).closest("tr").find(".edit-text");
                        var text = textfield.val();
                        textfield.hide();
                        current.text(text);
                        current.show();
                        textfield.parent().submit();
                        $(this).closest("tr").find(".mode1").show();
                        $(this).closest("tr").find(".mode2").hide();

    });

                    $(".cancel-edit").on("click", function() {
                        $(this).closest("tr").find(".mode2").hide();
                        $(this).closest("tr").find(".mode1").show();
                    });
                });


</script>

@stop