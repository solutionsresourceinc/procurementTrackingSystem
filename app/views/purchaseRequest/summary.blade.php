@extends('layouts.dashboard')

@section('header')
    <script src="js/foundation-datepicker.js"></script>
    {{ HTML::script('datepicker_range/foundation-datepicker.js')}}
    {{ HTML::style('datepicker_range/foundation-datepicker.css')}}

@stop


@section('content')
    <?php 
        $date_today = date('m/d/Y');
        echo "<input type='hidden' value='$date_today' id='date_today'>";
    ?>

    <!-- Modal Alert -->
    <div class="modal fade" id="description" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><b>Date Invalid</b></h4>
                </div>
                <center>
                    <div class="modal-body" id="description_body">
                        <p>Date input can't be greater than date today!</p>
                    </div>
                </center>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

	<h1 class="page-header">Summary</h1>

	<br/><br/>
    <!--<div class="form-inline" style="width: 80%; margin: 0px 15px;">-->
    <form class="form ajax" action="summary/changeDate" method="post" role="form" class="form-inline">
        <div class="form-group col-md-9">
            <div class="input-daterange input-group" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="yyyy-mm-dd">
                <input type="text" class="form-control" name="start" value="" id="dpd1" style="text-align: center" placeholder="Click to select date" onchange="checkInput(this.value,this.id)">
                <span class="input-group-addon" style="vertical-align: top;height:20px">to</span>
                <input type="text" class="form-control" name="end" value="" id="dpd2" style="text-align: center"  placeholder="Click to select date" onchange="checkInput(this.value,this.id)">
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
    <a href="" id="showModal" title="Description" data-placement="top" data-method="post" data-replace="#description_body"  class="btn-info" data-toggle="modal" data-target="#description" style="visibility:hidden;"><span class="glyphicon glyphicon-list-alt"></span></a><br>

    

@stop

@section('footer')
{{ HTML::script('js/bootstrap-ajax.js');}}


<script>
    $(function () {
        // implementation of disabled form fields
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate() + 1, 0, 0, 0, 0);
                
        var checkin = $('#dpd1').fdatepicker(
        {
            onRender: function (date) 
            {
                return date.valueOf() > now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function (ev) {
        if (ev.date.valueOf() > checkout.date.valueOf()) 
        {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate() + 1);
            checkout.update(newDate);
        }
            checkin.hide();
            $('#dpd2')[0].focus();
            }).data('datepicker');
                
        var checkout = $('#dpd2').fdatepicker({
            onRender: function (date) {
            // return date.valueOf() < checkin.date.valueOf() ? 'disabled' : '';
                if(date.valueOf() < checkin.date.valueOf() || date.valueOf() > now.valueOf())
                {
                    return 'disabled';
                }

            }
        }).on('changeDate', function (ev) {
            checkout.hide();
        }).data('datepicker');
    });

    function checkInput(value,inputId)
    {

        var dateInput = new Date(value);
        var dateTodayTemp = document.getElementById('date_today').value;
        var dateToday = new Date(dateTodayTemp);
        if(dateInput > dateToday)
        {
            document.getElementById('showModal').click();
            document.getElementById("dpd1").value = dateTodayTemp;
            document.getElementById("dpd2").value = dateTodayTemp;

        }
    }
</script>
@stop