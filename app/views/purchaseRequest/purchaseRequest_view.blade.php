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
	<!-- put control number (id) here -->
	<h2 class="pull-left"> {{ $purchase->controlNo }} </h2>

	<!-- change urls when when purchase request functions are final -->
	<div class="btn-group pull-right options">
		<a href="../edit/{{$purchase->id}}" class="btn btn-success">
			<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit
		</a>
		<!--button type="button" class="btn btn-danger" onclick="window.location.href='../../purchaseRequest/delete'">
			<span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Delete
		</button-->
		<button type="button" class="btn btn-default" onclick="window.location.href='../../purchaseRequest/view'">
			<span class="glyphicon glyphicon-step-backward"></span>&nbsp;Back
		</button>
		
	</div>

	<hr class="clear" />

	<!-- purchase details section -->
	<div class="well">
		<table width="100%" class="pr-details-table">
			<tr>
				<td width="25%" class="pr-label">Category:</td>
				<td>
					@if($wfName->id == 1)
						Small Value Procurement (Below P50,000)
					@elseif($wfName->id == 2)
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
	</div>
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
	$attachments = DB::table('attachments')->where('doc_id', $docs->id)->get();	
	$srclink="uploads\\";
	?>
	@foreach ($attachments as $attachment) 
	<div class="image-container">
		<a href="{{asset('uploads/'.$attachment->data)}}" data-lightbox="roadtrip">
		<img class="img-thumbnail" src="{{asset('uploads/'.$attachment->data)}}" style="width: 70px; height: 70px;" />
	</a>
		
	</div>

	@endforeach

</div>
{{ Session::forget('notice'); }}
@stop