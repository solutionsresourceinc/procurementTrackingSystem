@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">Dashboard</h1>

    <?php 
        $id=Auth::User()->id;
        $role= DB::table('assigned_roles')->where('user_id',$id)->first();
        if ($role->role_id==1)
        {

        }
        else
        {
    ?>

    <?php
        $purchaseRequest = Purchase::all();
        $prCount = 0;
        $POCount = 0;
        $chequeCount = 0;

        $reports = Reports::all();
        foreach ($reports as $report) 
        {
            $POCount = $POCount + $report->pOrderCount;
            $chequeCount = $chequeCount + $report->chequeCount;
        }
    ?>

    <!-- Prints total number of purchase requests received -->
    <div class="col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <div>
                        <i class="fa fa-file-text-o" style="margin: 10px 5px; font-size: 3.7em;"></i>
                    </div>
                    </div>
                    <div class="col-xs-6 text-right">

                        <p class="announcement-heading">{{ $purchaseRequest->count(); }}</p>
                    </div>
                    <p class="announcement-text">@if($purchaseRequest->count() == 1) Purchase Request @else Purchase Requests @endif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Prints total number of purchase orders received -->
    <div class="col-md-4">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="glyphicon glyphicon-list-alt fa-3x" style="margin: 10px 5px"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">{{ $POCount }}</p>
                    </div>
                    <p class="announcement-text">@if($POCount == 1) Purchase Order @else Purchase Orders @endif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Prints total number of cheques received -->
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-credit-card" style="margin: 10px 5px; font-size: 3.5em;"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">{{ $chequeCount }}</p>
                    </div>
                    <p class="announcement-text">@if($chequeCount == 1) Cheque @else Cheques @endif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Table of latest purchase requests -->
    <div class="col-md-12" style="margin-top: 10px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span style="font-size: 18px;">Latest Purchase Requests<a href="{{ URL::to('purchaseRequest/create') }}" class="btn btn-sm btn-primary" style="float: right; padding: 3px 10px;">Create New</a></span>
            </div>
            <div class="panel-body">
                <table class="table">
                <thead>
                    <th>Control No.</th>
                    <th>Project/Purpose</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Amount</th>
                </thead>

                <?php
                    $requests =  DB::table('purchase_request')->orderBy('created_at', 'DESC')->take(5)->get();
                ?>

                <tbody>
                    @if(count($requests))
                        @foreach ($requests as $request)
                            <tr>
                            <td width="10%">{{ $request->controlNo }}</td>
                            <td width="30%"><a data-toggle="tooltip" data-placement="top" class="purpose" href="{{ URL::to('purchaseRequest/vieweach/'. $request->id) }}" title="View Project Details">{{ $request->projectPurpose; }}</a></td>
                            <?php 
                                $doc = new Purchase; 
                                $doc = DB::table('document')->where('pr_id', $request->id)->get(); 
                            ?>
                            <td width="30%">
                                @foreach ($doc as $docs) {{ Workflow::find($docs->work_id)->workFlowName; }} @endforeach
                            </td>
                            <td width="15%"><span class="label {{($request->status == 'New') ? 'label-primary' : (($request->status == 'Active') ? 'label-success' : (($request->status == 'Overdue') ? 'label-danger' : 'label-default'))}}">{{ $request->status; }}</span></td>
                            <td width="15%">P{{{ $request->amount }}}</td>
                            </tr>
                        @endforeach
                    @else
                        <td colspan="5"><span class="error-view">No data available.</span></td>
                    @endif
                </tbody>
            </table>
            </div>
        </div>  
    </div>
    <?php
        }
    ?>
@stop