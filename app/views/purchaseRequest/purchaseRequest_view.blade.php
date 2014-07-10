@extends('layouts.dashboard')

@section('header')
	<style type="text/css">
		td{
		    
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
			 if($purchase->status!="Cancelled"){
			?><a href="../edit/{{$purchase->id}}" class="btn btn-success">
			<span class="glyphicon glyphicon-edit"></span>&nbsp;&nbsp;Edit
		</a>
		<?php
	}
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

	@if($purchase->status == "Cancelled")
		<div class="alert alert-danger"> Reason: {{ $purchase->reason }}</div> 
	@endif
	
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

						<span class="label {{($purchase->status == 'New') ? 'label-primary' : (($purchase->status == 'Active') ? 'label-success' : (($purchase->status == 'Overdue') ? 'label-danger' : 'label-default'))}}">
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

	<!-- START CHECKLIST SECTION -->
	<?php 
		$docs= Document::where('pr_id', $purchase->id)->first();
		//Cursor Component
		$taskc= TaskDetails::where('doc_id', $docs->id)->where('status', 'New')->orWhere('status', 'Active')->first(); 
		$workflow= Workflow::find($docs->work_id);
		$section= Section::where('workflow_id', $workflow->id)->orderBy('section_order_id','ASC')->get();

		$taskd= TaskDetails::where('doc_id', $docs->id)->orderBy('id', 'ASC')->get();
		$sectioncheck=0;
$prdays=0;
		foreach ($section as $sections) { 
$sectiondays=0;


			$task= Task::where('section_id', $sections->section_order_id)->where('wf_id', $workflow->id)->orderBy('order_id', 'ASC')->get();
			echo "<div class='panel panel-success'><div class='panel-heading'>
				<h3 class='panel-title'>".$sections->section_order_id.". ".$sections->sectionName."</h3>
				</div>";
			echo "<div class='panel-body'>";
			echo "<table border='1' class='workflow-table'>";
		
           //Addon Display
    $otherc=OtherDetails::where('section_id', $sections->id)->count();
if($otherc!=0){
echo "<tr><th  class='workflow-th'>Label</th><th colspan='4'  class='workflow-th'>Input</th></tr>";

$otherd= OtherDetails::where('section_id', $sections->id)->get();
foreach ($otherd as $otherdetails) {
    echo "<tr><td>".$otherdetails->label."</td>";
    $valuesc=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchase->id)->count();
$values=Values::where('otherDetails_id', $otherdetails->id)->where('purchase_request_id', $purchase->id)->first();    
if ($valuesc==0) {

?>
    
<td colspan="4"></td>


<?php

}
else {
echo "<td colspan='4'>".$values->value."</td>";
?>

<?php
}
echo "</tr>";
}


}

//End Addon Display 


			echo " <tr><th width='30%'></th>";
			echo "<th class='workflow-th' width='12.5%'>By:</th>";
			echo "<th class='workflow-th' width='12.5%'>Date:</th>";
			echo "<th class='workflow-th' width='12.5%'>Days of Action</th>";
			echo "<th class='workflow-th'>Remarks</th></tr>";
			foreach ($task as $tasks) {


			    //Displayer 
			      $taskpc =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->count();
if ($taskpc==0)
	continue;
			    $taskp =TaskDetails::where('doc_id', $docs->id)->where('task_id', $tasks->id)->first();

			    echo "<tr><td >".$tasks->order_id.". ".$tasks->taskName."</td>";

			?>

				<td ><?php
					if($taskp->assignee!=NULL){
						$dassignee=chunk_split($taskp->assignee, 20, "<br>");
					   echo $dassignee; }
					else if($taskp->assignee_id!=0){
					    $assign_user=User::find($taskp->assignee_id);
						echo $assign_user->lastname.", ".$assign_user->firstname;
					}
					$date = new DateTime($taskp->dateFinished);
					$datef = $date->format('m/d/y');
					?>
				</td>
				<td  ><?php if($taskp->dateFinished!="0000-00-00 00:00:00") echo $datef; ?></td>
				<td ><?php if($taskp->dateFinished!="0000-00-00 00:00:00") echo $taskp->daysOfAction; ?></td>
				<td ><?php 
				$dremarks=chunk_split($taskp->remarks, 20, "<br>");
				echo $dremarks. "</td></tr>";
				
				$sectiondays=$sectiondays+$taskp->daysOfAction;
				
			}
			echo "<tr><td>Today No. of Days</td><td>".$sectiondays."</td></tr>";
			$prdays=$prdays+$sectiondays;
			echo "</table></div></div>";
		}
        echo "<div class='form-create fc-div'><h4>Total No. of Days: ".$prdays." </h4></div>";   
	?>
	<!--/div-->
	<!-- END CHECKLIST SECTION -->
	
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