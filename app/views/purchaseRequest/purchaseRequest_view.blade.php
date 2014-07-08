@extends('layouts.dashboard')

@section('header')
	<style type="text/css">
		td{
		    border:0px solid #ccc;
		    padding:5px 10px;
		    vertical-align:top;
		    word-break:break-word;
		}
	</style>
	<!--Image Display-->
 
    {{ HTML::script('js/lightbox.min.js') }} 
    {{ HTML::style('css/lightbox.css')}}
<!--End Image Display-->
@stop

@section('content')		



	@if(Session::get('notice'))
        <div class="alert alert-success"> {{ Session::get('notice') }}</div> 
    @endif

	<!--h2 class="pull-left"> {{ $purchase->controlNo }} </h2-->
	<h2 class="pull-left">Purchase Request Details </h2>

	<!-- change urls when when purchase request functions are final -->
	<div class="btn-group pull-right options">
		<?php 
		$cuser=Auth::User()->id;
		if (Entrust::hasRole('Administrator')){
			?><a href="../edit/{{$purchase->id}}" class="btn btn-success">
			<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit
		</a>
		<?php
		}
 else if (Entrust::hasRole('Procurement Personnel'))
                                            {
                                 if($purchase->created_by==$cuser){
		 if($purchase->status!="Cancelled"){
			?><a href="../edit/{{$purchase->id}}" class="btn btn-success">
			<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit
		</a>
		<?php } }}
		
		?>
		<!--button type="button" class="btn btn-danger" onclick="window.location.href='../../purchaseRequest/delete'">
			<span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Delete
		</button-->
		<button type="button" class="btn btn-default" onclick="window.location.href='../../purchaseRequest/view'">
			<span class="glyphicon glyphicon-step-backward"></span>&nbsp;Back
		</button>
		
	</div>

	<hr class="clear" />

	<!-- purchase details section 
	<div class="well">
		<table width="100%" class="pr-details-table">
			<tr>
				<td width="25%" class="pr-label">Category:</td>
				<td>
					@if($wfName->work_id == 1)
						Small Value Procurement (Below P50,000)
					@elseif($wfName->work_id == 2)
						Small Value Procurement (Above P50,000 Below P500,000)
					@else
						Bidding (Above P500,000)
					@endif
				</td>
			</tr>
			<tr>
				<td class="pr-label">Requisitioner</td>

				<td>
					<?php $user = User::find($purchase->requisitioner) ?>
					{{ $user->lastname . ", " . $user->firstname }}
				</td>
			</tr>
			<tr>
				<td class="pr-label">Project/Purpose:</td>
				<td>{{ $purchase->projectPurpose }}</td>
			</tr>
			<tr>
				<td class="pr-label">Source of Funds:</td>
				<td>{{ $purchase->sourceOfFund }}</td>
			</tr>
			<tr>
				<td class="pr-label">ABC Amount:</td>
				<td>{{ $purchase->amount }}</td>
			</tr>
		</table>
	</div>-->
	
	<div class="panel panel-success">
		<div class="panel-body">
			<table border="1" class="proc-details">
				<tr>
					<td class="proc-headers" colspan="3"><h4 style="line-height: 25px;">
						@if($wfName->work_id == 1)
							SMALL VALUE PROCUREMENT (BELOW P50,000) &nbsp;&nbsp;
						@elseif($wfName->work_id == 2)
							SMALL VALUE PROCUREMENT (ABOVE P50,000 BELOW P500,000) &nbsp;&nbsp;
						@else
							BIDDING (ABOVE P500,000) &nbsp;&nbsp;
						@endif

						<span class="label {{($purchase->status == 'New') ? 'label-primary' : (($purchase->status == 'In Progress') ? 'label-success' : (($purchase->status == 'Overdue') ? 'label-danger' : 'label-default'))}}">
                    		{{ $purchase->status; }}
                    	</span>
					</h4></td>
					<td colspan="1" width="30%">
						<span class="bac-ctrl-no">BAC CTRL. NO.:</span><br/>
						<h4 align="center" class="ctrl-no">{{ $purchase->controlNo }}</h4>
					</td>
				</tr>
				<tr>
					<td class="proc-headers" width="30%"><h5>REQUISITIONER</h5></td>
					<td class="proc-data" colspan="3">
						<?php $user = User::find($purchase->requisitioner) ?>
						{{ $user->lastname . ", " . $user->firstname }}
					</td>
				</tr>
				<tr>
					<td class="proc-headers" width="30%"><h5>PROJECT / PURPOSE</h5></td>
					<td class="proc-data" colspan="3">{{ $purchase->projectPurpose }}</td>
				</tr>
				<tr>
					<td class="proc-headers" width="30%"><h5>SOURCE OF FUNDS</h5></td>
					<td class="proc-data" colspan="3">{{ $purchase->sourceOfFund }}</td>
				</tr>
				<tr>
					<td class="proc-headers" width="30%"><h5>ABC AMOUNT</h5></td>
					<td class="proc-data" colspan="3">{{ $purchase->amount }}</td>
				</tr>
			</table>
		</div>
	</div>

	<!-- START CHECKLIST SECTION BY JAN SARMIENTO AWESOME -->
	@if($wfName->work_id==1)
		@include('purchaseRequest.purchaseRequest_wf1')
	@elseif($wfName->work_id==2)
		@include('purchaseRequest.purchaseRequest_wf2')
	@elseif($wfName->work_id==3)
		@include('purchaseRequest.purchaseRequest_wf3')
	@elseif($wfName->work_id==4)
		@include('purchaseRequest.purchaseRequest_wf4')
	@endif
	<!-- END CHECKLIST SECTION BY JAN SARMIENTO AWESOME -->
	
	<?php
		function data_uri($image, $mime) 
		{  
		  $base64   = base64_encode($image); 
		  return ('data:' . $mime . ';base64,' . $base64);
		}
	?>

	<!-- images section -->
	
	<div id="img-section">

	<?php
$docs= Document::where('pr_id', $purchase->id)->first();
	  $attachmentc = DB::table('attachments')->where('doc_id', $docs->id)->count();
         if ($attachmentc!=0)
            echo "<h3>"."Attachments"."</h3>";

$luser=Auth::user()->id;
$count= Count::where('doc_id','=', $docs->id)->where('user_id','=', $luser )->delete();


	$attachments = DB::table('attachments')->where('doc_id', $docs->id)->get();	
	$srclink="uploads\\";
	?>
	@foreach ($attachments as $attachment) 
	<div class="image-container">
		<a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="roadtrip">
		<img class="img-thumbnail" src="{{asset('uploads/'.$attachment->data)}}" style="width: 100px; height: 100px;" />
	</a>
		
	</div>

	@endforeach

</div>
{{ Session::forget('notice'); }}
@stop