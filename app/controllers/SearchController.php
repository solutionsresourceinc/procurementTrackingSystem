<?php

class SearchController extends BaseController {
	
	public function completeTableActive()
	{
		// $requests = DB::table('purchase_request')->paginate(20);
		$requests = DB::table('purchase_request')
		->join('offices', 'purchase_request.office', '=', 'offices.id')
		->join('document', 'purchase_request.id', '=', 'document.pr_id')
		->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
		->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active')->paginate(20);

		$pageCounter = DB::table('purchase_request')
		->join('offices', 'purchase_request.office', '=', 'offices.id')
		->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('purchase_request.status', '=', 'Active')->count();

		return View::make('purchaseRequest.purchaseRequest_completeTable')->with('requests',$requests)->with('pageCounter',$pageCounter);
	}

	public function search()
	{
		$link = "completeTable/active";
		$pageName = "List of Active Purchase Requests";
		$searchBy = Input::get('searchBy');
		$date_today =date('Y-m-d H:i:s');
		
		// return $searchBy;
		if(Entrust::hasRole('Requisitioner'))
		{
			if($searchBy == '0')
			{
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);
				
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
			else if($searchBy == 'all')
			{
				// return $this->view();
				return App::make('PurchaseRequestController')->view();
			}
			else if($searchBy == 'dateReceived')
			{
				$link = "completeTable/closed";
				$start = Input::get('start');
				$end = Input::get('end');

				if($start == $end)
				{
					$starty = (new \DateTime($start))->format('Y');
					$startm = (new \DateTime($start))->format('m');
					$startd = (new \DateTime($start))->format('d');
					// return $start;
					$pageCounter = DB::table('purchase_request')->where('status', '=', 'Active')->where('requisitioner', '=', Auth::user()->id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('status', '<=', 'Active')->where('requisitioner', '=', Auth::user()->id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				else
				{
					$pageCounter = DB::table('purchase_request')->where('status', '=', 'Active')->where('requisitioner', '=', Auth::user()->id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('status', '=', 'Active')->where('requisitioner', '=', Auth::user()->id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
			else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				if($searchBy == 'controlNo')
					$searchTerm = str_replace(' ', '', $searchTerm);
				if($searchBy == 'amount')
				{
					$searchTerm = number_format($searchTerm);
					$searchTerm .= ".00";
				}
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
			else if($searchBy == 'Shopping')
			{
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
			else
			{
				$searchTerm = trim(Input::get('searchTerm'));

				$pageCounter = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('requisitioner', '=', Auth::user()->id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->count();

				$requests = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('requisitioner', '=', Auth::user()->id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
		}
		else
		{
			if($searchBy == '0')
			{
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);
				
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
			else if($searchBy == 'all')
			{
				// return $this->view();
				return App::make('PurchaseRequestController')->view();
			}
			else if($searchBy == 'dateReceived')
			{
				$start = Input::get('start');
				$end = Input::get('end');

				if($start == $end)
				{
					$starty = (new \DateTime($start))->format('Y');
					$startm = (new \DateTime($start))->format('m');
					$startd = (new \DateTime($start))->format('d');
					// return $start;
					$pageCounter = DB::table('purchase_request')->where('status', '=', 'Active')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('status', '<=', 'Active')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				else
				{
					$pageCounter = DB::table('purchase_request')->where('status', '=', 'Active')->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('status', '=', 'Active')->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
			else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				if($searchBy == 'controlNo')
					$searchTerm = str_replace(' ', '', $searchTerm);
				if($searchBy == 'amount')
				{
					$searchTerm = number_format($searchTerm);
					$searchTerm .= ".00";
				}
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
			else if($searchBy == 'Shopping')
			{
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
			else
			{
				$searchTerm= trim(Input::get('searchTerm'));

				$pageCounter = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->count();

				$requests = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
			}
		}
	}

	public function searchClosed()
	{
		$pageName = "List of Closed Purchase Requests";
		$searchBy = Input::get('searchBy');
		$date_today =date('Y-m-d H:i:s');
		
		// return $searchBy;
		if(Entrust::hasRole('Requisitioner'))
		{
			if($searchBy == '0')
			{
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->paginate(10);
				
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'all')
			{
				// return $this->viewClosed();
				return App::make('PurchaseRequestController')->viewClosed();
			}
			else if($searchBy == 'dateReceived')
			{
				$start = Input::get('start');
				$end = Input::get('end');

				if($start == $end)
				{
					$starty = (new \DateTime($start))->format('Y');
					$startm = (new \DateTime($start))->format('m');
					$startd = (new \DateTime($start))->format('d');
					// return $start;
					$pageCounter = DB::table('purchase_request')->where('status', '=', 'Closed')->where('requisitioner', '=', Auth::user()->id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('status', '<=', 'Closed')->where('requisitioner', '=', Auth::user()->id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				else
				{
					$pageCounter = DB::table('purchase_request')->where('status', '=', 'Closed')->where('requisitioner', '=', Auth::user()->id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('status', '=', 'Closed')->where('requisitioner', '=', Auth::user()->id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				if($searchBy == 'controlNo')
					$searchTerm = str_replace(' ', '', $searchTerm);
				if($searchBy == 'amount')
				{
					$searchTerm = number_format($searchTerm);
					$searchTerm .= ".00";
				}
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'Shopping')
			{
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else
			{
				$searchTerm= trim(Input::get('searchTerm'));
				$pageCounter = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('requisitioner', '=', Auth::user()->id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->count();

				$requests = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('requisitioner', '=', Auth::user()->id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
		}
		else
		{
			if($searchBy == '0')
			{
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->paginate(10);
				
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'all')
			{
				// return $this->viewClosed();
				return App::make('PurchaseRequestController')->viewClosed();
			}
			else if($searchBy == 'dateReceived')
			{
				$start = Input::get('start');
				$end = Input::get('end');

				if($start == $end)
				{
					$starty = (new \DateTime($start))->format('Y');
					$startm = (new \DateTime($start))->format('m');
					$startd = (new \DateTime($start))->format('d');
					// return $start;
					$pageCounter = DB::table('purchase_request')->where('status', '=', 'Closed')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('status', '<=', 'Closed')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				else
				{
					$pageCounter = DB::table('purchase_request')->where('status', '=', 'Closed')->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('status', '=', 'Closed')->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
			{
				if($searchBy == 'controlNo')
					$searchTerm = str_replace(' ', '', $searchTerm);
				$searchTerm= trim(Input::get('searchTerm'));
				if($searchBy == 'amount')
				{
					$searchTerm = number_format($searchTerm);
					$searchTerm .= ".00";
				}
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'Shopping')
			{
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else
			{
				$searchTerm= trim(Input::get('searchTerm'));

				$pageCounter = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->count();

				$requests = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
		}
	}

	public function searchOverdue()
	{
		$pageName = "List of Overdue Purchase Requests";
		$searchBy = Input::get('searchBy');
		$date_today =date('Y-m-d H:i:s');
		
		// return $searchBy;
		if(Entrust::hasRole('Requisitioner'))
		{
			if($searchBy == '0')
			{
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '<-', Auth::user()->id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);
				
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'all')
			{
				// return $this->viewOverdue();
				return App::make('PurchaseRequestController')->viewOverdue();
			}
			else if($searchBy == 'dateReceived')
			{
				$start = Input::get('start');
				$end = Input::get('end');

				if($start == $end)
				{
					$starty = (new \DateTime($start))->format('Y');
					$startm = (new \DateTime($start))->format('m');
					$startd = (new \DateTime($start))->format('d');
					// return $start;
					$pageCounter = DB::table('purchase_request')->where('dueDate', '<=', $date_today)->where('requisitioner', '=', Auth::user()->id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('dueDate', '<=', $date_today)->where('requisitioner', '=', Auth::user()->id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				else
				{
					$pageCounter = DB::table('purchase_request')->where('dueDate', '<=', $date_today)->where('requisitioner', '=', Auth::user()->id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('dueDate', '<=', $date_today)->where('requisitioner', '=', Auth::user()->id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				if($searchBy == 'controlNo')
					$searchTerm = str_replace(' ', '', $searchTerm);
				if($searchBy == 'amount')
				{
					$searchTerm = number_format($searchTerm);
					$searchTerm .= ".00";
				}
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'Shopping')
			{
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else
			{
				$searchTerm= trim(Input::get('searchTerm'));

				$pageCounter = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('requisitioner', '=', Auth::user()->id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->orderBy('dateReceived', 'DESC')->count();

				$requests = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('requisitioner', '=', Auth::user()->id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
		}
		else
		{
			if($searchBy == '0')
			{
				$pageCounter = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);
				
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'all')
			{
				// return $this->viewOverdue();
				return App::make('PurchaseRequestController')->viewOverdue();
			}
			else if($searchBy == 'dateReceived')
			{
				$start = Input::get('start');
				$end = Input::get('end');

				// $start = (new \DateTime($starts))->format('Y-m-d');
				// $end = (new \DateTime($ends))->format('Y-m-d');

				if($start == $end)
				{
					$starty = (new \DateTime($start))->format('Y');
					$startm = (new \DateTime($start))->format('m');
					$startd = (new \DateTime($start))->format('d');
					// return $start;
					$pageCounter = DB::table('purchase_request')->where('dueDate', '<=', $date_today)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('dueDate', '<=', $date_today)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				else
				{
					$pageCounter = DB::table('purchase_request')->whereBetween('dateRequested', array($start, $end))->where('dueDate', '<=', $date_today)->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->whereBetween('dateRequested', array($start, $end))->where('dueDate', '<=', $date_today)->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				if($searchBy == 'controlNo')
					$searchTerm = str_replace(' ', '', $searchTerm);
				if($searchBy == 'amount')
				{
					$searchTerm = number_format($searchTerm);
					$searchTerm .= ".00";
				}
				$pageCounter = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'Shopping')
			{
				$pageCounter = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else
			{
				$searchTerm= trim(Input::get('searchTerm'));

				$pageCounter = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->count();

				$requests = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
		}
	}

	public function searchCancelled()
	{
		$pageName = "List of Cancelled Purchase Requests";
		$searchBy = Input::get('searchBy');
		$date_today =date('Y-m-d H:i:s');
		
		// return $searchBy;
		if(Entrust::hasRole('Requisitioner'))
		{
			if($searchBy == '0')
			{
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);
				
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'all')
			{
				// return $this->viewCancelled();
				return App::make('PurchaseRequestController')->viewCancelled();
			}
			else if($searchBy == 'dateReceived')
			{
				$start = Input::get('start');
				$end = Input::get('end');

				// $start = (new \DateTime($starts))->format('Y-m-d');
				// $end = (new \DateTime($ends))->format('Y-m-d');

				if($start == $end)
				{
					$starty = (new \DateTime($start))->format('Y');
					$startm = (new \DateTime($start))->format('m');
					$startd = (new \DateTime($start))->format('d');
					// return $start;
					$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				else
				{
					$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->whereBetween('dateRequested', array($start, $end))->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->whereBetween('dateRequested', array($start, $end))->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				if($searchBy == 'controlNo')
					$searchTerm = str_replace(' ', '', $searchTerm);
				if($searchBy == 'amount')
				{
					$searchTerm = number_format($searchTerm);
					$searchTerm .= ".00";
				}
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'Shopping')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				$pageCounter = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('requisitioner', '=', Auth::user()->id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else
			{
				$searchTerm= trim(Input::get('searchTerm'));

				$pageCounter = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('requisitioner', '=', Auth::user()->id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->count();

				$requests = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('requisitioner', '=', Auth::user()->id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
		}
		else
		{
			if($searchBy == '0')
			{
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);
				
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'all')
			{
				// return $this->viewCancelled();
				return App::make('PurchaseRequestController')->viewCancelled();
			}
			else if($searchBy == 'dateReceived')
			{
				$start = Input::get('start');
				$end = Input::get('end');

				// $start = (new \DateTime($starts))->format('Y-m-d');
				// $end = (new \DateTime($ends))->format('Y-m-d');

				if($start == $end)
				{
					$starty = (new \DateTime($start))->format('Y');
					$startm = (new \DateTime($start))->format('m');
					$startd = (new \DateTime($start))->format('d');
					// return $start;
					$pageCounter = DB::table('purchase_request')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				else
				{
					$pageCounter = DB::table('purchase_request')->whereBetween('dateRequested', array($start, $end))->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->count();
					$requests = DB::table('purchase_request')->whereBetween('dateRequested', array($start, $end))->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);
				}
				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				if($searchBy == 'controlNo')
					$searchTerm = str_replace(' ', '', $searchTerm);
				if($searchBy == 'amount')
				{
					$searchTerm = number_format($searchTerm);
					$searchTerm .= ".00";
				}
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else if($searchBy == 'Shopping')
			{
				$searchTerm= trim(Input::get('searchTerm'));
				$pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
				$requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
			else
			{
				$searchTerm= trim(Input::get('searchTerm'));

				$pageCounter = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->count();

				$requests = DB::table('purchase_request')
				->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);

				return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
			}
		}
	}

}