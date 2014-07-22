@extends('layouts.dashboard')

@section('header')
	{{ HTML::style('css/datepicker.css')}}

	
@stop

@section('content')
	<h1 class="page-header">Summary</h1>

	<br/><br/>
    <!--<div class="form-inline" style="width: 80%; margin: 0px 15px;">-->
    <form class="form ajax" action="summary/changeDate" method="post" role="form" class="form-inline">
        <div class="form-group col-md-9">
            <div class="input-daterange input-group" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="yyyy-mm-dd">
                <input type="text" class="form-control" name="start" id="start" style="text-align: center"/>
                <span class="input-group-addon" style="vertical-align: top;height:20px">to</span>
                <input type="text" id="end" class="form-control" name="end" style="text-align: center" />
            </div>
        </div>
        {{ Form::submit('Apply', array('class' => 'btn btn-success col-md-3')) }}
    </form>
    <!--</div>-->

    <div style="margin-top: 100px">
        <div id="dateReport">

        </div>

        <div class="col-md-4">
            <div class="well" style="" id="PR">
                <span class="summary-panel-title"><strong>Total Number of PR Received:</strong></span><br/>
                <span class="summary-amount" style="color: #246D27">{{ $prCount }}</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="well" style="" id="PO">
                <span class="summary-panel-title"><strong>Total Number of PO Received:</strong></span><br/>
                <span class="summary-amount" style="color: #4E3A17">{{ $POCount }}</span>
            </div>
        </div>
        <div class="col-md-4" >
            <div class="well" style="" id="Cheque">
                <span class="summary-panel-title"><strong>Total Number of Cheque Received:</strong></span><br/>
                <span class="summary-amount" style="color: #1B4F69">{{ $chequeCount }}</span>
            </div>
        </div>
    </div>
    

@stop

@section('footer')
    {{ HTML::script('js/bootstrap-ajax.js');}}

	<!-- Load jQuery and bootstrap datepicker scripts -->
    <!--script src="js/jquery-1.9.1.min.js"></script-->
    <script src="js/bootstrap-datepicker.js"></script>
    <script type="text/javascript">
        // When the document is ready
        $(document).ready(function () {
            
            $('.input-daterange').datepicker({
                todayBtn: "linked"
            });
        });
    </script>
@stop