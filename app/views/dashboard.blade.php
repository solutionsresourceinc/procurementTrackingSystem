@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">Dashboard</h1>

    <?php 
        $id=Auth::User()->id;
        $role= DB::table('assigned_roles')->where('user_id',$id)->first();
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

    @if($role->role_id!=1)
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
    @endif

    <!-- Table of latest purchase requests -->
    <!-- changed to Newly Updated Purchase Request -->
    <div class="col-md-12" style="margin-top: 10px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span style="font-size: 18px;">Latest Update
                    @if($role->role_id!=1) 
                        <a href="{{ URL::to('purchaseRequest/create') }}" class="btn btn-sm btn-primary" style="float: right; padding: 3px 10px;">Create New</a>
                    @endif
                </span>
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
                    if(Entrust::hasRole('Requisitioner'))
                        $requests =  DB::table('purchase_request')->where('requisitioner', $id)->orderBy('updated_at', 'DESC')->take(5)->get();
                    else
                        $requests =  DB::table('purchase_request')->orderBy('updated_at', 'DESC')->take(5)->get();
                ?>

                <tbody>
                    @if(count($requests))
                        @foreach ($requests as $request)
                            <tr>
                            <td width="10%"> <?php echo str_pad($request->controlNo, 5, '0', STR_PAD_LEFT); ?></td>
                            <td width="30%">
                                @if($role->role_id!=1) 
                                    <a data-toggle="tooltip" data-placement="top" class="purpose" href="{{ URL::to('purchaseRequest/vieweach/'. $request->id) }}" title="View Project Details">
                                    @endif
                                        {{ $request->projectPurpose; }}
                                @if($role->role_id!=1)</a>@endif
                            </td>
                            <?php 
                                $doc = new Purchase; 
                                $doc = DB::table('document')->where('pr_id', $request->id)->get(); 
                            ?>
                            <td width="30%">
                                @foreach ($doc as $docs) 
                                    <?php  
                                    $workflow = Workflow::find($docs->work_id)->workFlowName; 
                                    if($workflow == "Small Value Procurement (Below P50,000)")
                                    {
                                        echo "SVP (Below P50,000)";

                                    }
                                    else if($workflow == "Small Value Procurement (Above P50,000 Below P500,000)")
                                    {
                                        echo "SVP (Above P50,000 Below P500,000)";
                                    }
                                    else
                                    {
                                        echo $workflow = Workflow::find($docs->work_id)->workFlowName;
                                    }

                                    if($request->otherType != "")
                                    {
                                            echo "<br> <i>$request->otherType</i>";
                                    }
                                ?>
                                 @endforeach
                            </td>
                            <td width="15%"><span class="label {{($request->status == 'New') ? 'label-primary' : (($request->status == 'Active') ? 'label-success' : (($request->status == 'Overdue') ? 'label-danger' : 'label-default'))}}">{{ $request->status; }}</span></td>
                            <td width="15%">{{{ $request->amount }}}</td>
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
@stop