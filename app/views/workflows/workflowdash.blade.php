@extends('layouts.dashboard')

@section('header')
    {{ HTML::style('ios_overlay/css/iosOverlay.css')}}
@stop

@section('content')
	<!-- Creates the form -->
    <h1 class="page-header">Workflows</h1>
    <!--div>
        <a href="{{ URL::to('workflow/belowFifty') }}" class="btn btn-success">Below P50,000</a>
        <a href="{{ URL::to('workflow/aboveFifty') }}" class="btn btn-success">Between P50,000 and P500,000</a>
        <a href="{{ URL::to('workflow/aboveFive') }}" class="btn btn-success">Above P500,000</a>
        <br><br><br>
	</div-->
    <ul class="nav nav-tabs" role="tablist" id="myTab">
      <li class="active"><a href="#home" role="tab" data-toggle="tab">Below P50,000</a></li>
      <li><a href="#profile" role="tab" data-toggle="tab">Between P50,000 and P500,000</a></li>
      <li><a href="#messages" role="tab" data-toggle="tab">Above P500,000</a></li>
      <li><a href="#pakyaw" role="tab" data-toggle="tab">Pakyaw</a></li> <!-- FOR PAKYAW WORKFLOW -->
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade in active" id="home">@include('workflows.workflow1')</div>
      <div class="tab-pane fade" id="profile">@include('workflows.workflow2')</div>
      <div class="tab-pane fade" id="messages">@include('workflows.workflow3')</div>
      <div class="tab-pane fade" id="pakyaw">@include('workflows.workflow4')</div> <!-- FOR PAKYAW WORKFLOW -->
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
                  })
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