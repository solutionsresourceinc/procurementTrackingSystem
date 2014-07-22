@extends('layouts.dashboard')

@section('content')

    <!--CODE REVIEW:
        - use laravel for conditions
    -->

	<!-- Creates the form -->
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

    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-check fa-5x"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">12</p>
                        <p class="announcement-text">To-Do Items</p>
                    </div>
                </div>
            </div>
            <!--a href="#">
                <div class="panel-footer announcement-bottom">
                    <div class="row">
                        <div class="col-xs-6">
                            Total No. of PR Received
                        </div>
                        <div class="col-xs-6 text-right">
                            <i class="fa fa-arrow-circle-right"></i>
                        </div>
                    </div>
                </div>
            </a-->
        </div>
    </div>

    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-check fa-5x"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">12</p>
                        <p class="announcement-text">To-Do Items</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-check fa-5x"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">12</p>
                        <p class="announcement-text">To-Do Items</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <i class="fa fa-check fa-5x"></i>
                    </div>
                    <div class="col-xs-6 text-right">
                        <p class="announcement-heading">12</p>
                        <p class="announcement-text">To-Do Items</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-top: 10px;">
        <div class="panel panel-default">
            <div class="panel-heading">
                <span style="font-size: 18px;">Latest Purchase Requests<a href="{{ URL::to('purchaseRequest/create') }}" class="btn btn-sm btn-info" style="float: right; padding: 3px 10px;">Create New</a></span>
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