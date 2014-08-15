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
				"html" => "<div id='insert_$id' class='mode1'> None  </div> <input type='hidden' id='hide_currentDesignation' class='hide_currentDesignation' value='0' > "
			);
		}
		else
		{
			$data = array(
				"html" => "<div id='insert_$id' class='mode1'> $des_name  </div> <input type='hidden' id='hide_currentDesignation' class='hide_currentDesignation' value='$assignd->designation_id' > "

			);
		}

		return Response::json($data);
	}

	public function SummarySubmit()
	{
 
		$start = date('Y-m-d', strtotime(Input::get('start')));
		$end = date('Y-m-d', strtotime(Input::get('end')));
		$date_today = date('Y-m-d');

		$prCount = 0;
		$POCount = 0;
		$chequeCount = 0;

		if($end == '1970-01-01' && $start == '1970-01-01') // if TO and FROM is empty
		{
			$end = '9998-01-01';
			$start = '0001-01-01';

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
			            <p>Summary Reports from beginning to $date_today.</p>
			        </div>
				"
				),
			);	

			return Response::json($data);
		}

		else if($end == '1970-01-01' ) // if TO is empty
		{
			$end = '9998-01-01';

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
			            <p>Summary Reports $start to $date_today.</p>
			        </div>
				"
				),
			);	

			return Response::json($data);
		}

		else if( $start == '1970-01-01') // if TO and FROM is empty
		{
			$start = '0001-01-01';

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
			            <p>Summary Reports from beginning to $end.</p>
			        </div>
				"
				),
			);	

			return Response::json($data);
		}

		else
		{
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
}
