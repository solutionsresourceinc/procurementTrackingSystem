@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">Overdue Tasks</h1>

    <div class="list-group">
    	<a href="/task/task-id" class="list-group-item tasks">
            <div class="pull-left task-desc" style="margin-left: 10px;">
                <span class="list-group-item-heading">PPMP CERTIFICATION</span> &nbsp;<small class="overdue-task"><i>Due Date: July 31, 2014</i></small><br/>
                <span class="list-group-item-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span>
            </div>
        </a>
    </div>
@stop    