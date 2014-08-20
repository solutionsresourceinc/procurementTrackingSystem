<?php

class SearchController extends BaseController {

    public function completeActiveSearch()
    {
        $searchBy = Input::get('searchBy');
        $searchTerm = trim(Input::get('searchTerm'));
        $flag = 0;
        $supplierFlag = 0;

        if($searchBy == 'dateReceived')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(purchase_request.dateReceived)'), '=', $starty)->where(DB::raw('MONTH(purchase_request.dateReceived)'), '=', $startm)->where(DB::raw('DAY(purchase_request.dateReceived)'), '=', $startd)->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');

                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('purchase_request.dateReceived', array($start, $end))->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
        }
        else if($searchBy == 'all' || $searchBy == '0')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            return View::make('purchaseRequest.purchaseRequest_completeTable')->with('requests',$requests)->with('pageCounter',$pageCounter)->with('searchBy','0')->with('pageNumber', Input::get('pageNumber'));
        }
        else if($searchBy == 'controlNo')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('purchase_request.controlNo', 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'office')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('offices.officeName', 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'projectPurpose' || $searchBy == 'sourceOfFund' || $searchBy == 'amount')
        {
            if($searchBy == 'amount' && is_numeric($searchTerm))
            {
                $stringSamples = array(' ', ',', ' ');
                $searchTerm = str_replace($stringSamples, '', $searchTerm);
                $searchTerm = number_format($searchTerm);
                $searchTerm .= ".00";
            }

            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where($searchBy, 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'budgetdate')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(taskdetails.dateFinished)'), '=', $starty)->where(DB::raw('MONTH(taskdetails.dateFinished)'), '=', $startm)->where(DB::raw('DAY(taskdetails.dateFinished)'), '=', $startd)->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('taskdetails.dateFinished', array($start, $end))->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            $flag = 1;
        }
        else if($searchBy == 'dateapproved')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(taskdetails.dateFinished)'), '=', $starty)->where(DB::raw('MONTH(taskdetails.dateFinished)'), '=', $startm)->where(DB::raw('DAY(taskdetails.dateFinished)'), '=', $startd)->where('tasks.taskName', '=', 'SIGNED BY GOV')->where('purchase_request.status', '=', 'Active');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('taskdetails.dateFinished', array($start, $end))->where('tasks.taskName', '=', 'SIGNED BY GOV')->where('purchase_request.status', '=', 'Active');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            $flag = 1;
        }
        else if($searchBy == 'supplier')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'LCRB / HRB / SUPPLIER')->where('purchase_request.status', '=', 'Active')->where('taskdetails.custom1', 'LIKE', "%$searchTerm%");
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            $supplierFlag = 1;
        }
        else if($searchBy == '1' || $searchBy == '2' || $searchBy == '3' || $searchBy == '4' || $searchBy == '5')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('document.work_id', '=', $searchBy)->where('purchase_request.projectPurpose', 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else
            return App::make('SearchController')->completeTableActive();

        return View::make('purchaseRequest.purchaseRequest_completeTable')->with('pageNumber', Input::get('pageNumber'))->with('requests',$requests)->with('pageCounter',$pageCounter)->with('searchBy', $searchBy)->with('flag', $flag)->with('supplierFlag', $supplierFlag);
    }

    public function completeClosedSearch()
    {
        if(Session::get('pagination'))
            $pagination = Session::get('pagination');
        else
            $pagination = 15;

        $searchBy = Input::get('searchBy');
        $searchTerm = trim(Input::get('searchTerm'));
        $flag = 0;
        $supplierFlag = 0;

        if($searchBy == 'dateReceived')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(purchase_request.dateReceived)'), '=', $starty)->where(DB::raw('MONTH(purchase_request.dateReceived)'), '=', $startm)->where(DB::raw('DAY(purchase_request.dateReceived)'), '=', $startd)->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Closed');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('purchase_request.dateReceived', array($start, $end))->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Closed');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
        }
        else if($searchBy == 'all')
        { return App::make('SearchController')->completeTableActive(); }
        else if($searchBy == 'controlNo')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('purchase_request.controlNo', 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Closed');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'office')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('offices.officeName', 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Closed');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'projectPurpose' || $searchBy == 'sourceOfFund' || $searchBy == 'amount')
        {
            if($searchBy == 'amount' && is_numeric($searchTerm))
            {
                $stringSamples = array(' ', ',', ' ');
                $searchTerm = str_replace($stringSamples, '', $searchTerm);
                $searchTerm = number_format($searchTerm);
                $searchTerm .= ".00";
            }

            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where($searchBy, 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Closed');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'budgetdate')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(taskdetails.dateFinished)'), '=', $starty)->where(DB::raw('MONTH(taskdetails.dateFinished)'), '=', $startm)->where(DB::raw('DAY(taskdetails.dateFinished)'), '=', $startd)->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Closed');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('taskdetails.dateFinished', array($start, $end))->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Closed');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            $flag = 1;
        }
        else if($searchBy == 'dateapproved')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(taskdetails.dateFinished)'), '=', $starty)->where(DB::raw('MONTH(taskdetails.dateFinished)'), '=', $startm)->where(DB::raw('DAY(taskdetails.dateFinished)'), '=', $startd)->where('tasks.taskName', '=', 'SIGNED BY GOV')->where('purchase_request.status', '=', 'Closed');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('taskdetails.dateFinished', array($start, $end))->where('tasks.taskName', '=', 'SIGNED BY GOV')->where('purchase_request.status', '=', 'Closed');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            $flag = 1;
        }
        else if($searchBy == 'supplier')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'LCRB / HRB / SUPPLIER')->where('purchase_request.status', '=', 'Closed')->where('taskdetails.custom1', 'LIKE', "%$searchTerm%");
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            $supplierFlag = 1;
        }
        else
            return App::make('SearchController')->completeTableClosed();

        return View::make('purchaseRequest.purchaseRequest_completeTable')->with('requests',$requests)->with('pageCounter',$pageCounter)->with('searchBy', $searchBy)->with('flag', $flag)->with('supplierFlag', $supplierFlag);
    }

    public function completeCancelledSearch()
    {
        if(Session::get('pagination'))
            $pagination = Session::get('pagination');
        else
            $pagination = 15;

        $searchBy = Input::get('searchBy');
        $searchTerm = trim(Input::get('searchTerm'));
        $flag = 0;
        $supplierFlag = 0;

        if($searchBy == 'dateReceived')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where(DB::raw('YEAR(purchase_request.dateReceived)'), '=', $starty)->where(DB::raw('MONTH(purchase_request.dateReceived)'), '=', $startm)->where(DB::raw('DAY(purchase_request.dateReceived)'), '=', $startd)->where('purchase_request.status', '=', 'Cancelled');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->whereBetween('purchase_request.dateReceived', array($start, $end))->where('purchase_request.status', '=', 'Cancelled');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
        }
        else if($searchBy == 'all')
        { return App::make('SearchController')->completeTableCancelled(); }
        else if($searchBy == 'controlNo')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('purchase_request.controlNo', 'LIKE', "%$searchTerm%")->where('purchase_request.status', '=', 'Cancelled');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'office')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('offices.officeName', 'LIKE', "%$searchTerm%")->where('purchase_request.status', '=', 'Cancelled');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'projectPurpose' || $searchBy == 'sourceOfFund' || $searchBy == 'amount')
        {
            if($searchBy == 'amount' && is_numeric($searchTerm))
            {
                $stringSamples = array(' ', ',', ' ');
                $searchTerm = str_replace($stringSamples, '', $searchTerm);
                $searchTerm = number_format($searchTerm);
                $searchTerm .= ".00";
            }

            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where($searchBy, 'LIKE', "%$searchTerm%")->where('purchase_request.status', '=', 'Cancelled');
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else
            return App::make('SearchController')->completeTableCancelled();
        return View::make('purchaseRequest.purchaseRequest_completeTable')->with('cancelled', '0')->with('requests',$requests)->with('pageCounter',$pageCounter)->with('searchBy', $searchBy)->with('flag', $flag)->with('supplierFlag', $supplierFlag);
    }

    public function completeOverdueSearch()
    {
        if(Session::get('pagination'))
            $pagination = Session::get('pagination');
        else
            $pagination = 15;

        $searchBy = Input::get('searchBy');
        $searchTerm = trim(Input::get('searchTerm'));
        $flag = 0;
        $supplierFlag = 0;

        if($searchBy == 'dateReceived')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(purchase_request.dateReceived)'), '=', $starty)->where(DB::raw('MONTH(purchase_request.dateReceived)'), '=', $startm)->where(DB::raw('DAY(purchase_request.dateReceived)'), '=', $startd)->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('purchase_request.dateReceived', array($start, $end))->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
        }
        else if($searchBy == 'all')
        { return App::make('SearchController')->completeTableOverdue(); }
        else if($searchBy == 'controlNo')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('purchase_request.controlNo', 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'office')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('offices.officeName', 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
        }
        else if($searchBy == 'projectPurpose' || $searchBy == 'sourceOfFund' || $searchBy == 'amount')
        {
            if($searchBy == 'amount' && is_numeric($searchTerm))
            {
                $stringSamples = array(' ', ',', ' ');
                $searchTerm = str_replace($stringSamples, '', $searchTerm);
                $searchTerm = number_format($searchTerm);
                $searchTerm .= ".00";
            }

            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where($searchBy, 'LIKE', "%$searchTerm%")->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            // return $requests;
        }
        else if($searchBy == 'budgetdate')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(taskdetails.dateFinished)'), '=', $starty)->where(DB::raw('MONTH(taskdetails.dateFinished)'), '=', $startm)->where(DB::raw('DAY(taskdetails.dateFinished)'), '=', $startd)->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('taskdetails.dateFinished', array($start, $end))->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'))->where('purchase_request.status', '=', 'Active');
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            $flag = 1;
        }
        else if($searchBy == 'dateapproved')
        {
            $start = Input::get('start');
            $end = Input::get('end');

            if($start == $end)
            {
                $starty = (new \DateTime($start))->format('Y');
                $startm = (new \DateTime($start))->format('m');
                $startd = (new \DateTime($start))->format('d');

                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where(DB::raw('YEAR(taskdetails.dateFinished)'), '=', $starty)->where(DB::raw('MONTH(taskdetails.dateFinished)'), '=', $startm)->where(DB::raw('DAY(taskdetails.dateFinished)'), '=', $startd)->where('tasks.taskName', '=', 'SIGNED BY GOV')->where('purchase_request.status', '=', 'Active')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            else
            {
                $requests = DB::table('purchase_request')
                    ->join('offices', 'purchase_request.office', '=', 'offices.id')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                    ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                    ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->whereBetween('taskdetails.dateFinished', array($start, $end))->where('tasks.taskName', '=', 'SIGNED BY GOV')->where('purchase_request.status', '=', 'Active')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
                $pageCounter = $requests->count();
                $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            }
            $flag = 1;
        }
        else if($searchBy == 'supplier')
        {
            $requests = DB::table('purchase_request')
                ->join('offices', 'purchase_request.office', '=', 'offices.id')
                ->join('document', 'purchase_request.id', '=', 'document.pr_id')
                ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
                ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'LCRB / HRB / SUPPLIER')->where('purchase_request.status', '=', 'Active')->where('taskdetails.custom1', 'LIKE', "%$searchTerm%")->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'));
            $pageCounter = $requests->count();
            $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();
            $supplierFlag = 1;
        }
        else
            return App::make('SearchController')->completeTableOverdue();
        return View::make('purchaseRequest.purchaseRequest_completeTable')->with('requests',$requests)->with('pageCounter',$pageCounter)->with('searchBy', $searchBy)->with('flag', $flag)->with('supplierFlag', $supplierFlag);
    }

    public function completeTableActive()
    {
        $requests = DB::table('purchase_request')
            ->join('offices', 'purchase_request.office', '=', 'offices.id')
            ->join('document', 'purchase_request.id', '=', 'document.pr_id')
            ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
            ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Active');
        $pageCounter = $requests->count();
        $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();

        return View::make('purchaseRequest.purchaseRequest_completeTable')->with('requests',$requests)->with('pageCounter',$pageCounter)->with('searchBy','0');
    }

    public function completeTableClosed()
    {

        $requests = DB::table('purchase_request')
            ->join('offices', 'purchase_request.office', '=', 'offices.id')
            ->join('document', 'purchase_request.id', '=', 'document.pr_id')
            ->join('taskdetails'
                , 'taskdetails.doc_id', '=', 'document.id')
            ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.status', '=', 'Closed');
        $pageCounter = $requests->count();
        $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();

        return View::make('purchaseRequest.purchaseRequest_completeTable')->with('searchBy','0')->with('requests',$requests)->with('pageCounter',$pageCounter);
    }

    public function completeTableOverdue()
    {
        if(Session::get('pagination'))
            $pagination = Session::get('pagination');
        else
            $pagination = 15;

        $requests = DB::table('purchase_request')
            ->join('offices', 'purchase_request.office', '=', 'offices.id')
            ->join('document', 'purchase_request.id', '=', 'document.pr_id')
            ->join('taskdetails', 'taskdetails.doc_id', '=', 'document.id')
            ->join('tasks', 'tasks.id', '=', 'taskdetails.task_id')->where('tasks.taskName', '=', 'BUDGET / ACTG')->where('purchase_request.dueDate', '<=', date('Y-m-d H:i:s'))->where('purchase_request.status', '=', 'Active');
        $pageCounter = $requests->count();
        $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();

        return View::make('purchaseRequest.purchaseRequest_completeTable')->with('searchBy','0')->with('requests',$requests)->with('pageCounter',$pageCounter);
    }

    public function completeTableCancelled()
    {
        if(Session::get('pagination'))
            $pagination = Session::get('pagination');
        else
            $pagination = 5;

        $requests = DB::table('purchase_request')
            ->join('offices', 'purchase_request.office', '=', 'offices.id')
            ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('purchase_request.status', '=', 'Cancelled');
        $pageCounter = $requests->count();
        $requests = $requests->orderBy('purchase_request.dateReceived', 'DESC')->get();

        return View::make('purchaseRequest.purchaseRequest_completeTable')->with('requests',$requests)->with('pageCounter',$pageCounter)->with('searchBy','0')->with('cancelled','0');
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
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
            else if($searchBy == 'all')
            {
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
                    $requests = DB::table('purchase_request')->where('status', '<=', 'Active')->where('office', '=', Auth::user()->office_id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                else
                {
                    $requests = DB::table('purchase_request')->where('status', '=', 'Active')->where('office', '=', Auth::user()->office_id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
            else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                if($searchBy == 'controlNo')
                    $searchTerm = str_replace(' ', '', $searchTerm);
                if($searchBy == 'amount' && is_numeric($searchTerm))
                {
                    $stringSamples = array(' ', ',', ' ');
                    $searchTerm = str_replace($stringSamples, '', $searchTerm);
                    $searchTerm = number_format($searchTerm);
                    $searchTerm .= ".00";
                }
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
            else if($searchBy == 'Shopping')
            {
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
            else
            {
                $searchTerm = trim(Input::get('searchTerm'));

                $requests = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('office', '=', Auth::user()->office_id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
        }
        else
        {
            if($searchBy == '0')
            {
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

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
                    $requests = DB::table('purchase_request')->where('status', '<=', 'Active')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                else
                {
                    $requests = DB::table('purchase_request')->where('status', '=', 'Active')->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
            else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                if($searchBy == 'controlNo')
                    $searchTerm = str_replace(' ', '', $searchTerm);
                if($searchBy == 'amount' && is_numeric($searchTerm))
                {
                    $stringSamples = array(' ', ',', ' ');
                    $searchTerm = str_replace($stringSamples, '', $searchTerm);
                    $searchTerm = number_format($searchTerm);
                    $searchTerm .= ".00";
                }
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
            else if($searchBy == 'Shopping')
            {
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
            else
            {
                $searchTerm= trim(Input::get('searchTerm'));

                $requests = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link',$link);
            }
        }
    }

    public function searchClosed()
    {
        $searchTerm = trim(Input::get('searchTerm'));
        $pageName = "List of Closed Purchase Requests";
        $searchBy = Input::get('searchBy');
        $date_today =date('Y-m-d H:i:s');

        // return $searchBy;
        if(Entrust::hasRole('Requisitioner'))
        {
            if($searchBy == '0')
            {
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'all')
            {
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

                    $requests = DB::table('purchase_request')->where('status', '<=', 'Closed')->where('office', '=', Auth::user()->office_id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                else
                {
                    $requests = DB::table('purchase_request')->where('status', '=', 'Closed')->where('office', '=', Auth::user()->office_id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                if($searchBy == 'controlNo')
                    $searchTerm = str_replace(' ', '', $searchTerm);
                if($searchBy == 'amount' && is_numeric($searchTerm))
                {
                    $stringSamples = array(' ', ',', ' ');
                    $searchTerm = str_replace($stringSamples, '', $searchTerm);
                    $searchTerm = number_format($searchTerm);
                    $searchTerm .= ".00";
                }
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'Shopping')
            {
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else
            {
                $searchTerm= trim(Input::get('searchTerm'));
                $requests = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('office', '=', Auth::user()->office_id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
        }
        else
        {
            if($searchBy == '0')
            {
                $pageCounter = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->count();
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC')->paginate(10);

                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'all')
            {
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
                    $requests = DB::table('purchase_request')->where('status', '<=', 'Closed')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                else
                {
                    $requests = DB::table('purchase_request')->where('status', '=', 'Closed')->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
            {
                if($searchBy == 'controlNo')
                    $searchTerm = str_replace(' ', '', $searchTerm);
                $searchTerm= trim(Input::get('searchTerm'));
                if($searchBy == 'amount' && is_numeric($searchTerm))
                {
                    $stringSamples = array(' ', ',', ' ');
                    $searchTerm = str_replace($stringSamples, '', $searchTerm);
                    $searchTerm = number_format($searchTerm);
                    $searchTerm .= ".00";
                }
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'Shopping')
            {
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else
            {
                $searchTerm= trim(Input::get('searchTerm'));

                $requests = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Closed')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/closed')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
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
                $requests = DB::table('purchase_request')->where('requisitioner', '<-', Auth::user()->id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'all')
            {
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

                    $requests = DB::table('purchase_request')->where('dueDate', '<=', $date_today)->where('office', '=', Auth::user()->office_id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                else
                {
                    $requests = DB::table('purchase_request')->where('dueDate', '<=', $date_today)->where('office', '=', Auth::user()->office_id)->whereBetween('dateRequested', array($start, $end))->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                if($searchBy == 'controlNo')
                    $searchTerm = str_replace(' ', '', $searchTerm);
                if($searchBy == 'amount' && is_numeric($searchTerm))
                {
                    $stringSamples = array(' ', ',', ' ');
                    $searchTerm = str_replace($stringSamples, '', $searchTerm);
                    $searchTerm = number_format($searchTerm);
                    $searchTerm .= ".00";
                }
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'Shopping')
            {
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else
            {
                $searchTerm= trim(Input::get('searchTerm'));

                $requests = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('office', '=', Auth::user()->office_id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
        }
        else
        {
            if($searchBy == '0')
            {
                $pageCounter = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->count();
                $requests = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);

                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
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
                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                if($searchBy == 'controlNo')
                    $searchTerm = str_replace(' ', '', $searchTerm);
                if($searchBy == 'amount' && is_numeric($searchTerm))
                {
                    $stringSamples = array(' ', ',', ' ');
                    $searchTerm = str_replace($stringSamples, '', $searchTerm);
                    $searchTerm = number_format($searchTerm);
                    $searchTerm .= ".00";
                }
                $pageCounter = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->count();
                $requests = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC')->paginate(10);

                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'Shopping')
            {
                $pageCounter = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->count();
                $requests = DB::table('purchase_request')->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC')->paginate(10);

                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else
            {
                $searchTerm= trim(Input::get('searchTerm'));

                $pageCounter = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->count();

                $requests = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','<=',$date_today)->where('status', '=', 'Active')->orderBy('dateReceived', 'DESC')->paginate(10);

                return View::make('pr_view')->with('link','completeTable/overdue')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
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
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName)->with('link','completeTable/cancelled');
            }
            else if($searchBy == 'all')
            {
                return App::make('PurchaseRequestController')->viewCancelled();
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

                    $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                else
                {
                    $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->whereBetween('dateRequested', array($start, $end))->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                if($searchBy == 'controlNo')
                    $searchTerm = str_replace(' ', '', $searchTerm);
                if($searchBy == 'amount' && is_numeric($searchTerm))
                {
                    $stringSamples = array(' ', ',', ' ');
                    $searchTerm = str_replace($stringSamples, '', $searchTerm);
                    $searchTerm = number_format($searchTerm);
                    $searchTerm .= ".00";
                }
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'Shopping')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                $requests = DB::table('purchase_request')->where('office', '=', Auth::user()->office_id)->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else
            {
                $searchTerm= trim(Input::get('searchTerm'));

                $requests = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('office', '=', Auth::user()->office_id)->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
        }
        else
        {
            if($searchBy == '0')
            {
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC')->paginate(10);
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'all')
            {
                return App::make('PurchaseRequestController')->viewCancelled();
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

                    $requests = DB::table('purchase_request')->where(DB::raw('YEAR(dateRequested)'), '=', $starty)->where(DB::raw('MONTH(dateRequested)'), '=', $startm)->where(DB::raw('DAY(dateRequested)'), '=', $startd)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                else
                {
                    $requests = DB::table('purchase_request')->whereBetween('dateRequested', array($start, $end))->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
                    $pageCounter = $requests->count();
                    $requests = $requests->paginate(10);
                }
                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'controlNo' || $searchBy == 'projectPurpose' || $searchBy == 'amount')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                if($searchBy == 'controlNo')
                    $searchTerm = str_replace(' ', '', $searchTerm);
                if($searchBy == 'amount' && is_numeric($searchTerm))
                {
                    $stringSamples = array(' ', ',', ' ');
                    $searchTerm = str_replace($stringSamples, '', $searchTerm);
                    $searchTerm = number_format($searchTerm);
                    $searchTerm .= ".00";
                }
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where($searchBy, 'LIKE', "%$searchTerm%")->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else if($searchBy == 'Shopping')
            {
                $searchTerm= trim(Input::get('searchTerm'));
                $requests = DB::table('purchase_request')->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('otherType', '=', $searchBy)->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
            else
            {
                $searchTerm= trim(Input::get('searchTerm'));

                $requests = DB::table('purchase_request')
                    ->join('document', 'purchase_request.id', '=', 'document.pr_id')->where('document.work_id', '=', $searchBy)->where('projectPurpose', 'LIKE', "%$searchTerm%")->where('dueDate','>',$date_today)->where('status', '=', 'Cancelled')->orderBy('dateReceived', 'DESC');
                $pageCounter = $requests->count();
                $requests = $requests->paginate(10);

                return View::make('pr_view')->with('link','completeTable/cancelled')->with('requests',$requests)->with('searchBy',$searchBy)->with('pageCounter',$pageCounter)->with('pageName' ,$pageName);
            }
        }
    }
}