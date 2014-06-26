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
@stop

@section('content')		
	<!-- put control number (id) here -->
	<h2 class="pull-left"> {{ $purchase->ControlNo }} </h2>

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
			$doc = DB::table('document')->where('pr_id', $purchase->id)->get();
		?>
		@foreach($doc as $docs)
			<?php
			 $attachments = DB::table('attachments')->where('doc_id', $docs->id)->get(); 
			?>

			@foreach ($attachments as $attachment) 
				<img data-src="holder.js/200x200" class="img-thumbnail" alt="200x200" src="<?php echo data_uri( $attachment->data, 'image/png'); ?>" style="width: 200px; height: 200px;" >
			@endforeach
		@endforeach
	</div>
@stop