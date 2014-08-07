@extends('layouts.default')
@section('content')
<style type="text/css">
	th,td {
		text-align: center;
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
            font-size : 86% !important;
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
	<div class="btn-group pull-left col-md-6 no-print">
        <button class="btn btn-info no-print" onclick="window.print()">
            <span class="glyphicon glyphicon-print"></span>&nbsp;&nbsp;Print
        </button>
		<button type="button" class="btn btn-default no-print" onclick="window.location.href='{{ URL::to('back') }}'">
			<span class="glyphicon glyphicon-step-backward"></span>&nbsp;Back
		</button>		
	</div>
	<div class="col-md-3 no-print">
		<?php $searchBy = 0; ?> <!-- REMOVE THIS EVENTUALLY -->            
			<select id="searchBy" name="searchBy" class="form-control" onchange="changeSearch(this.value)">
                <option value="0" <?php if($searchBy == '0'){ echo "selected";} ?> >Search by</option>
                <option value="all" <?php if($searchBy == 'all'){ echo "selected";} ?> >Display All</option>
                <option value="controlNo" <?php if($searchBy == 'controlNo'){ echo "selected";} ?> >Control No.</option>
                <option value="projectPurpose" <?php if($searchBy == 'projectPurpose'){ echo "selected";} ?> >Project/Purpose</option>
                <option value="1" <?php if($searchBy == '1'){ echo "selected";} ?> >Mode-SVP Below 50k</option>
                <option value="2" <?php if($searchBy == '2'){ echo "selected";} ?> >Mode-SVP Above 50k,Below 500k</option>
                <option value="3" <?php if($searchBy == '3'){ echo "selected";} ?> >Mode-Bidding</option>
                <option value="4" <?php if($searchBy == '4'){ echo "selected";} ?> >Mode-Pakyaw</option>
                <option value="5" <?php if($searchBy == '5'){ echo "selected";} ?> >Mode-Direct Contracting</option>
                <option value="Shopping" <?php if($searchBy == 'Shopping'){ echo "selected";} ?> >Mode-Shopping</option>
                <option value="amount" <?php if($searchBy == 'amount'){ echo "selected";} ?>>Amount</option>
                <option value="dateReceived" <?php if($searchBy == 'dateReceived'){ echo "selected";} ?>>Date Received</option>
            </select>
        </div>   
	<div class="input-group col-md-3 no-print" id="searchBox">
      <input onkeyup="disableButton()"id="searchTerm" name="searchTerm" placeholder="Enter search keywords" type="text" class="form-control" onchange="detectInput()">
      <span class="input-group-btn">
        <button class="btn btn-primary" name="searchButton" id="searchButton" type="submit">Search</button>
      </span>
    </div>
<div style="margin-top: 30px">
	<table class="workflow-table" border="1">
		<thead>
			<th width="16.66%"> Control No </th>
			<th width="16.66%"> Project/Purpose </th>
			<th width="16.66%"> Mode </th>
			<th width="16.66%"> Amount </th>
			<th width="16.66%"> Date </th>
			<th width="16.66%"> Action </th>
		</thead>
		<tbody>
			@foreach($requests as $request)
				<tr><td>{{{$request->controlNo}}}</td></tr>
				<tr><td>{{{$request->projectPurpose}}}</td></tr>
				<tr><td>Test</td></tr>
				<tr><td>{{{number_format($request->amount)}}}</td></tr>
				<tr><td>{{{$request->dateReceieved}}}</td></tr>
				<tr><td>Test</td></tr>
			@endforeach
		</tbody>
	</table>
</div>
@stop