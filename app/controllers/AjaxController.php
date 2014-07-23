<?php

class AjaxController extends Controller
{

	public function workflowSubmit()
	{
		$designation = e(Input::get('designa'));

		if($designation == 0)
		{
			$des_name = "";
		}
		else
		{
			$des = Designation::find($designation);
			$des_name = e($des->designation);
		}

		$id = Input::get('task_id');
		$assignd = Task::find($id);
		$assignd->designation_id = Input::get('designa');
		$assignd->save();

		if($designation == 0)
		{
			$data = array(
				"html" => "<div id='insert_$id' class='mode1'> None  </div>"
			);
		}
		else
		{
			$data = array(
				"html" => "<div id='insert_$id' class='mode1'> $des_name  </div>"
			);
		}

		return Response::json($data);
	}

	public function SummarySubmit()
	{
		$start = Input::get('start');
		$end = Input::get('end');

		$prCount = 0;
		$POCount = 0;
		$chequeCount = 0;

		$reports = Reports::whereBetween('date', array($start, $end))->get(); 
		foreach ($reports as $report) 
		{
			$prCount = $prCount + $report->pRequestCount;
			$POCount = $POCount + $report->pOrderCount;
			$chequeCount = $chequeCount + $report->chequeCount;
		}


		$data = array(
		"inner-fragments" => array(
			"#PR" => 
			"
				<span class='summary-panel-title'><strong>Total Number of PR Received:</strong></span><br/>
                <span class='summary-amount' style='color: #246D27'> $prCount </span>
			",
			"#PO" => 
			"
				<span class='summary-panel-title'><strong>Total Number of PO Received:</strong></span><br/>
                <span class='summary-amount' style='color: #4E3A17'> $POCount </span>
			",

			"#Cheque" => 
			"
				<span class='summary-panel-title'><strong>Total Number of Cheque Received:</strong></span><br/>
                <span class='summary-amount' style='color: #1B4F69'> $chequeCount </span>
			",

			"#dateReport" =>
			"
				<div id='dateReport'  class='alert alert-info'>
		            <p>Summary Reports from $start to $end.</p>
		        </div>
			"
			),
		);	

		return Response::json($data);
	}
}
