@extends('layouts.default')

@section('header')
	<style type="text/css">
		td{
    border:0px solid #ccc;
    padding:5px 10px;
    vertical-align:top;
    word-break:break-word;
}


	</style>
	
@stop

@section('content')
	
	<ol class="breadcrumb">
		<li>{{ link_to('/', 'Home') }}</li>
		<li>{{ link_to('purchaseRequest/view', 'Purchase Requests') }}</li>
		<li class="active">Project Name</li>
	</ol>
	
	<!-- put control number (id) here -->


	</span><h2 class="pull-left"> {{ $purchase->ControlNo }} </h2>

	<!-- change urls when when purchase request functions are final -->
	<div class="btn-group pull-right options">
		<button type="button" class="btn btn-success" onclick="window.location.href='purchaseRequest/edit'">
			<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit
		</button>
		<button type="button" class="btn btn-danger" onclick="window.location.href='purchaseRequest/delete'">
			<span class="glyphicon glyphicon-trash"></span>&nbsp;&nbsp;Delete
		</button>
		<button type="button" class="btn btn-default" onclick="window.location.href='purchaseRequest/view'">
			<span class="glyphicon glyphicon-step-backward"></span>&nbsp;Back
		</button>
	</div>

	<hr class="clear" />

	<!-- purchase details section -->
	<div class="well">
		<table width="100%" class="pr-details-table">
			<tr>
				<td width="25%" class="pr-label">Category:</td>
				<td>{{ $purchase->modeOfProcurement }}</td>
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

	<!-- images section -->
	/
	<div id="img-section">
		<img data-src="holder.js/200x200" class="img-thumbnail" alt="200x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwMCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEzcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L3N2Zz4=" style="width: 200px; height: 200px;">
		<img data-src="holder.js/200x200" class="img-thumbnail" alt="200x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwMCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEzcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L3N2Zz4=" style="width: 200px; height: 200px;">
		<img data-src="holder.js/200x200" class="img-thumbnail" alt="200x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwMCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEzcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L3N2Zz4=" style="width: 200px; height: 200px;">
		<img data-src="holder.js/200x200" class="img-thumbnail" alt="200x200" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iI2VlZSIvPjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9IjEwMCIgeT0iMTAwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEzcHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MjAweDIwMDwvdGV4dD48L3N2Zz4=" style="width: 200px; height: 200px;">
<!--
<?php

$document= new Document; 
 $document = DB::table('document')->where('pr_id', '=', $purchase->id)->first(); 

$attachments= new Attachments; 
 $attachments = DB::table('attachments')->where('doc_id', '=', $document->id); 

?>
@foreach ($attachments as $attachment)
	<img data-src="holder.js/200x200" class="img-thumbnail" alt="200x200" src="data:image/svg+xml;base64,{{$attachment->data}}" style="width: 200px; height: 200px;">
@endforeach
-->
</div>
@stop