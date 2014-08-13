@extends('layouts.default')

@section('header')
    {{ HTML::style('date_picker/bootstrap-datetimepicker.min.css')}}
    {{ HTML::script('date_picker/bootstrap-datetimepicker.js') }}
    {{ HTML::script('date_picker/bootstrap-datetimepicker.fr.js') }}
    {{ HTML::style('css/datepicker.css')}}
    {{ HTML::script('js/bootstrap-datepicker.js') }}
@stop

@section('content')
<style type="text/css">
	th,td {
		text-align: center;
		font-size: 12px;
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
            font-size : 84% !important;
            height : 4px !important;
        }

        .panel, .panel-heading
        {
            margin: 0px !important;
            /*padding : 5px !important;*/
        }

        table {
        	margin-top: 20px;
        }
    }
</style>
	<br/>
	<br/>
    @if(isset($msg))
        <div class="col-md-12 no-print alert alert-danger">
            {{{ $msg }}}
        </div>
    @endif
	<div class="btn-group pull-left col-md-6 no-print">
        <button class="btn btn-info no-print" onclick="window.print()">
            <span class="glyphicon glyphicon-print"></span>&nbsp;&nbsp;Print
        </button>
		<button type="button" class="btn btn-default no-print" onclick="window.location.href='{{ URL::to('back') }}'">
			<span class="glyphicon glyphicon-step-backward"></span>&nbsp;Back
		</button>		
	</div>

	<form method="POST" action="">
	<div class="col-md-3 no-print">     
		<select id="searchBy" name="searchBy" class="form-control" onchange="changeSearch(this.value)">
            <option value="0" <?php if($searchBy == '0'){ echo "selected";} ?> >Search by</option>
            <option value="all" <?php if($searchBy == 'all'){ echo "selected";} ?> >Display All</option>
            <option value="dateReceived" <?php if($searchBy == 'dateReceived'){ echo "selected";} ?>>Date Received</option>
            <option value="controlNo" <?php if($searchBy == 'controlNo'){ echo "selected";} ?> >BAC No</option>
            <option value="office" <?php if($searchBy == 'office'){ echo "selected";} ?> >Dept</option>
            <option value="projectPurpose" <?php if($searchBy == 'projectPurpose'){ echo "selected";} ?> >Particulars</option>
            @if(!isset($cancelled))<option value="budgetdate" <?php if($searchBy == 'budgetdate'){ echo "selected";} ?> >Budget</option>@endif
            <option value="sourceOfFund" <?php if($searchBy == 'sourceOfFund'){ echo "selected";} ?> >Source of Fund</option>
            <option value="amount" <?php if($searchBy == 'amount'){ echo "selected";} ?>>Amount</option>
            @if(!isset($cancelled))
                <option value="dateapproved" <?php if($searchBy == 'dateapproved'){ echo "selected";} ?>>Date Approved</option>
                <option value="supplier" <?php if($searchBy == 'supplier'){ echo "selected";} ?>>Supplier</option>
            @endif
            <option value="1" <?php if($searchBy == '1'){ echo "selected";} ?> >Mode-SVP Below 50k</option>
            <option value="2" <?php if($searchBy == '2'){ echo "selected";} ?> >Mode-SVP Above 50k,Below 500k</option>
            <option value="3" <?php if($searchBy == '3'){ echo "selected";} ?> >Mode-Bidding</option>
            <option value="4" <?php if($searchBy == '4'){ echo "selected";} ?> >Mode-Pakyaw</option>
            <option value="5" <?php if($searchBy == '5'){ echo "selected";} ?> >Mode-Direct Contracting</option>
        </select>
    </div>   
	<div class="input-group col-md-3 no-print" id="searchBox">
      <input onchange="disableButton()" onkeyup="disableButton()" id="searchTerm" name="searchTerm" placeholder="Enter search keywords" type="text" class="form-control" onchange="detectInput()">
      <span class="input-group-btn">
        <button class="btn btn-primary" name="searchButton" id="searchButton" type="submit">Search</button>
      </span>
    </div>

	<div id="allButton" class="no-print" style="display: none;">
        <button class="btn btn-primary col-md-3" name="allButton" id="allButton" type="submit">Display</button>
    	<br/>
    	<br/>
    </div>

    <div class="form-group no-print" id="searchDate" style="display: none;">
        <div class="input-daterange input-group" id="datepicker" data-date="{{ date('Y-m-d') }}T" data-date-format="yyyy-mm-dd">
            <input onchange="dateButton()" type="text" class="form-control" name="start" id="start" style="text-align: center"/>
            <span class="input-group-addon" style="vertical-align: top;height:20px">to</span>
            <input onchange="dateButton()" type="text" id="end" class="form-control" name="end" style="text-align: center" />
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary" id="betDate" name="betDate">Search</button>
            </span>
            <!-- <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button> -->
        </div>
    </div>
    </form>

<div style="margin-top: 30px">
	<table class="table table-striped display" border="1" bordercolor="black">
        <thead>
			<th width="9.09%"> DATE </th>
			<th width="9.09%"> BAC NO </th>
			<th width="9.09%"> DEPT </th>
			<th width="9.09%"> PARTICULARS </th>
			<th width="9.09%"> BUDGET </th>
			<th width="9.09%"> SOURCE OF FUNDING </th>
			<th width="9.09%"> PR AMOUNT </th>
			<th width="9.09%"> DATE APPROVED </th>
			<th width="9.09%"> MOP </th>
			<th width="9.09%"> SUPPLIER </th>
			<th width="9.09%"> REMARKS </th>
		</thead>
		<tbody>
			@foreach($requests as $request)  
				<tr>
					<td>
						<?php $prDate = DB::table('purchase_request')->where('controlNo', '=', $request->controlNo)->first(); ?>
						{{{(new \DateTime($prDate->dateReceived))->format('Y-m-d')}}}
					</td>
					<td>{{{str_pad($request->controlNo, 5, '0', STR_PAD_LEFT)}}}</td>
					<td>{{{$request->officeName}}}</td>
					<td>
                        {{{$request->projectPurpose}}}
                        @if($request->otherType != 'Pakyaw' || $request->otherType != 'Direct Contracting')
                            <br/><i> {{{ $request->otherType }}} </i>
                        @endif
                    </td>
					<td>
                        @if(isset($cancelled) && $cancelled == 0)
                            <?php
                                $cancelledBudget = DB::table('purchase_request')
                                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
    							->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
    							->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('tasks.section_id', '=', '1')->where('purchase_request.controlNo', '=', $request->controlNo)->first();
                            
                            ?>
                            @if($cancelledBudget != "")
                                {{{(new \DateTime($cancelledBudget->dateFinished))->format('Y-m-d')}}}
                            @else
                                <font color="grey">N/A</font>
                            @endif
                        @else
                            <?php error_reporting(0); ?>
                            @if(isset($flag) && $flag == 0)
                            <?php
                                $dateApproved = DB::table('purchase_request')
                                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('tasks.section_id', '=', '1')->where('purchase_request.controlNo', '=', $request->controlNo)->first();
                            ?>
                                @if($dateApproved->dateFinished == '0000-00-00 00:00:00')
                                    <font color="grey">N/A</font>
                                @else
                                    {{{(new \DateTime($dateApproved->dateFinished))->format('Y-m-d')}}}
                                @endif
                            @else
                                @if($request->dateFinished == '0000-00-00 00:00:00')
                                    <font color="grey">N/A</font>
                                @else
                                    {{{(new \DateTime($request->dateFinished))->format('Y-m-d')}}}
    							@endif
				            @endif
                        @endif
					</td>
					<td>{{{$request->sourceOfFund}}}</td>
					<td>{{{$request->amount}}}</td>
					<td>
                        @if(isset($cancelled) && $cancelled == 0)
                            <?php
                                $cancelledApproved = DB::table('purchase_request')
                                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'SIGNED BY GOV')->where('tasks.section_id', '=', '1')->where('purchase_request.controlNo', '=', $request->controlNo)->first();
                            
                            ?>
                            @if($cancelledApproved != "")
                                {{{(new \DateTime($cancelledApproved->dateFinished))->format('Y-m-d')}}}
                            @else
                                <font color="grey">N/A</font>
                            @endif
                        @else
    						@if(isset($flag) && $flag == 0)
    							@if($request->dateFinished == '0000-00-00 00:00:00')
    								<font color="grey">N/A</font>
    							@else
    								{{{(new \DateTime($request->dateFinished))->format('Y-m-d')}}}
    							@endif
    						@else
    							<?php
    								$dateApproved = DB::table('purchase_request')
    								->join('document', 'purchase_request.id', '=', 'document.pr_id')
    								->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
    								->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'SIGNED BY GOV')->where('tasks.section_id', '=', '1')->where('purchase_request.controlNo', '=', $request->controlNo)->first();
    							?>
    							@if($dateApproved->dateFinished == "0000-00-00 00:00:00")
    								<font color="grey">N/A</font>
    							@else
    								{{{(new \DateTime($dateApproved->dateFinished))->format('Y-m-d')}}}	
    							@endif
    						@endif
                        @endif
					</td>
                    <td>
                    	<?php 
	                        $workName = DB::table('purchase_request')->where('controlNo', '=', $request->controlNo)
                            ->join('document', 'purchase_request.id', '=', 'document.pr_id')->first(); 
                    	?>
                        @if($workName->work_id == 1)
                            SVP
                        @elseif($workName->work_id == 2)
                            SVP
                        @elseif($workName->work_id == 3)
                            BIDDING
                        @elseif($workName->work_id == 4)
                            PAKYAW
                        @elseif($workName->work_id == 5)
                            DIRECT CONTRACTING
                        @endif
                    </td>
					<td> 
						@if(isset($supplierFlag) && $supplierFlag == 1)
							@if($request->custom1 == "")
								<font color="grey">N/A</font>
							@else
								{{{ $request->custom1 }}}			
							@endif
                        @elseif(isset($cancelled) && $cancelled == 0)
							<?php
                                $cancelledSupplier = DB::table('purchase_request')
                                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'LCRB / HRB / SUPPLIER')->where('purchase_request.controlNo', '=', $request->controlNo)->first();                            
                            ?>
                            @if($cancelledSupplier != "")
                                {{{ $cancelledSupplier->custom1 }}}
                            @else
                                <font color="grey">N/A</font>
                            @endif
                        @else
                            <?php
                                $supplier = DB::table('purchase_request')
                                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'LCRB / HRB / SUPPLIER')->where('purchase_request.controlNo', '=', $request->controlNo)->first();
                            ?>
                            @if($supplier->custom1 == "")
                                <font color="grey">N/A</font>
                            @else
                                {{{ $supplier->custom1 }}}          
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(isset($cancelled) && $cancelled == 0)
                             <?php
                                $accomplished = DB::table('purchase_request')->where('controlNo', '=', $request->controlNo)
                                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('taskdetails.status', '=', 'Done')->select('tasks.taskName')->orderBy('taskdetails.id', 'DESC')->first();
                            ?>
                            @if(isset($accomplished->taskName) && $accomplished->taskName != '')
                                <font color="green"><b> Accomplished :<Br/> </b></font> {{{ $accomplished->taskName }}}
                            @else
                                <font color="grey">N/A</font>
                            @endif
                        @else
    						<?php
    							$accomplished = DB::table('purchase_request')->where('controlNo', '=', $request->controlNo)
    							->join('document', 'purchase_request.id', '=', 'document.pr_id')
    							->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
    							->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('taskdetails.status', '=', 'Done')->select('tasks.taskName')->orderBy('taskdetails.id', 'DESC')->first();
    						?>
    						@if(isset($accomplished->taskName) && $accomplished->taskName != '')
    							<font color="green"><b> Accomplished: </b></font> {{{ $accomplished->taskName }}}
    						@endif

    						<?php
    							$pending = DB::table('purchase_request')->where('controlNo', '=', $request->controlNo)
    							->join('document', 'purchase_request.id', '=', 'document.pr_id')
    							->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
    							->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('taskdetails.status', '=', 'New')->select('tasks.taskName')->orderBy('taskdetails.id', 'ASC')->first();
    						?>
    						<br/>
    						@if( isset($pending->taskName) && $pending->taskName != '')
    							<font color="red"><b> For: </b></font>{{{ $pending->taskName }}}
    						@endif
                        @endif
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>
<div id="pages" align="center" class="no-print">
    @if($pageCounter != 0)
        <center>{{ $requests->links(); }}</center>
    @else
        <p><i>No data available</i></p>
    @endif
</div>
@stop
@section('footer')
<script type="text/javascript">
	    window.onload = function()
        {
            if(document.getElementById('start').value.length == 0 || document.getElementById('end').value.length == 0)
            {
                document.getElementById('betDate').disabled = true;
            }

            if(document.getElementById('searchTerm').value.length == 0)
            {
                document.getElementById('searchButton').disabled = true;
            }

            if(document.getElementById('searchBy').value == 'dateReceived' || document.getElementById('searchBy').value == 'budgetdate' || document.getElementById('searchBy').value == 'dateapproved')
            {
                document.getElementById('searchBox').style.display = 'none';
                document.getElementById('searchDate').style.display = ''; 
                document.getElementById('allButton').style.display = 'none';
            }
            
            if(document.getElementById('searchBy').value == '0')
            {
                document.getElementById('searchTerm').disabled = true;
                document.getElementById('searchButton').disabled = true;
                document.getElementById('allButton').style.display = 'none';
            }
            
            if(document.getElementById('searchBy').value == 'all')
            {
                document.getElementById('searchDate').style.display = 'none';
                document.getElementById('searchBox').style.display = 'none'; 
                // document.getElementById('searchTerm').style.display = 'none';
                // document.getElementById('searchButton').style.display = 'none';
                document.getElementById('allButton').style.display = '';
            }
            else
            {
                // document.getElementById('searchDate').style.display = 'none';
                document.getElementById('allButton').style.display = 'none';
            }
        }

        function changeSearch(value)
        {
            if(value == '0')
            {
                document.getElementById('searchTerm').disabled = true;
                document.getElementById('searchButton').disabled = true;
                document.getElementById('searchBox').style.display = '';
                document.getElementById('searchTerm').style.display = '';
                document.getElementById('searchButton').style.display = '';
                document.getElementById('searchDate').style.display = 'none'; 
                document.getElementById('allButton').style.display = 'none';
            }
            else if(value == 'all')
            {
                document.getElementById('allButton').style.display = '';
                document.getElementById('searchTerm').style.display = 'none';
                document.getElementById('searchButton').style.display = 'none';
                document.getElementById('searchBox').style.display = 'none';
                document.getElementById('searchDate').style.display = 'none';  
            }
            else if(value == 'dateReceived' || value == 'budgetdate' || value == 'dateapproved')
            {
                document.getElementById('searchBox').style.display = 'none';
                document.getElementById('searchDate').style.display = ''; 
                document.getElementById('allButton').style.display = 'none'; 
            }
            else
            {
                document.getElementById('searchTerm').disabled = false;
                document.getElementById('searchButton').disabled = true;
                document.getElementById('searchBox').style.display = '';
                document.getElementById('searchTerm').style.display = '';
                document.getElementById('searchButton').style.display = '';
                document.getElementById('searchDate').style.display = 'none';
                document.getElementById('allButton').style.display = 'none';  
            }
        }

        function disableButton()
        {
            if(document.getElementById('searchTerm').value.length != 0)
                document.getElementById('searchButton').disabled = false;
            else
                document.getElementById('searchButton').disabled = true;
        }

        function dateButton()
        {
            if(document.getElementById('start').value.length != 0 && document.getElementById('end').value.length != 0)
                document.getElementById('betDate').disabled = false;
            else
                document.getElementById('betDate').disabled = true;
        }
</script>

{{ HTML::script('js/bootstrap-ajax.js');}}
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