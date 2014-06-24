@extends('layouts.default')

@section('content')
	<h1 class="page-header">Small Value Procurement (Below P50,000)</h1>

	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">A. PURCHASE REQUEST</h3>
		</div>
		<div class="panel-body">
			<table border="1" class="workflow-table">
				<tr>
					<td width="30%">1. PPMP CERTIFICATION</td>
					<td width="12.5%" class="no-border"><input type="radio" name="certification" value="yes"> Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
					<td width="12.5%" class="no-border"><input type="radio" name="certification" value="no"> No</td>
					<td width="45%" colspan="2">By: </td>
				</tr>
				<tr>
					<td>2. DATE OF PR:</td>
					<td colspan="4"></td>
				</tr>
				<tr>
					<th></th>
					<th class="workflow-th" width="12.5%">BY:</th>
					<th class="workflow-th" width="12.5%">DATE:</th>
					<th class="workflow-th" width="12.5%">NO. OF DAYS OF ACTION</th>
					<th class="workflow-th">REMARKS</th>
				</tr>
				<tr>
					<td>3. GSD</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>4. BUDGET / ACTG</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>5. PA</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>6. PGO</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>7. GSD</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>TOTAL NO. OF DAYS</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">B. BAC REQUIREMENTS</h3>
		</div>
		<div class="panel-body">
			<table border="1" class="workflow-table">
				<tr>
					<th width="30%"></th>
					<th class="workflow-th" width="12.5%">BY:</th>
					<th class="workflow-th" width="12.5%">DATE:</th>
					<th class="workflow-th" width="12.5%">NO. OF DAYS OF ACTION</th>
					<th class="workflow-th">REMARKS</th>
				</tr>
				<tr>
					<td>1. 3 RFQ / CANVASS</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>2. ABSTRACT OF QUOTES</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td>TOTAL NO. OF DAYS</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">C. PURCHASE ORDER</h3>
		</div>
		<div class="panel-body">
			<table border="1" class="workflow-table">
				<tr>
					<td width="30%">LCRB / HRB / SUPPLIER</td>
					<td width="37.5%" colspan="3"></td>
					<td width="12.5%">AMOUNT:</td>
					<td></td>
				</tr>
				<tr>
					<th></th>
					<th class="workflow-th" width="12.5%">BY:</th>
					<th class="workflow-th" width="12.5%">DATE:</th>
					<th class="workflow-th" width="12.5%">NO. OF DAYS OF ACTION</th>
					<th class="workflow-th" colspan="2">REMARKS</th>
				</tr>
				<tr>
					<td>1. GSD</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>2. ACTG</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>3. PA</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>4. PGO</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>5. BAC (DELIVERY)</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>TOTAL NO. OF DAYS</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">D. VOUCHER</h3>
		</div>
		<div class="panel-body">
			<table border="1" class="workflow-table">
				<tr>
					<td width="30%">CHEQUE AMOUNT:</td>
					<td width="37.5%" colspan="3"></td>
					<td width="12.5%">CHEQUE NUM.:</td>
					<td></td>
				</tr>
				<tr>
					<th></th>
					<th class="workflow-th" width="12.5%">BY:</th>
					<th class="workflow-th" width="12.5%">DATE:</th>
					<th class="workflow-th" width="12.5%">NO. OF DAYS OF ACTION</th>
					<th class="workflow-th" colspan="2">REMARKS</th>
				</tr>
				<tr>
					<td>1. BUDGET</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>2. ACCOUNTING</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>3. TREASURY</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>4. PA</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>5. PGO</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
				<tr>
					<td>TOTAL NO. OF DAYS</td>
					<td></td>
					<td></td>
					<td></td>
					<td colspan="2"></td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
	    	<table border="1" class="workflow-table">
	    		<tr>
					<td width="55%">TOTAL NO. OF DAYS FROM PR TO PAYMENT</td>
					<td></td>
				</tr>
	    	</table>
	  	</div>
	</div>
@stop