@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">New Tasks</h1>

    <div class="list-group">
        <a href="#" class="list-group-item tasks"><span class="glyphicon glyphicon-unchecked pull-left" style="color: rgb(200, 213, 200);margin-top: 3px;"></span>
            <div class="pull-left task-desc" style="margin-left: 10px;">
                <span class="list-group-item-heading">PPMP CERTIFICATION</span> &nbsp;<small><i>Due Date: July 31, 2014</i></small><br/>
                <span class="list-group-item-text">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</span>
            </div>
            {{Form::button('Accept Task', ['class' => 'btn btn-sm btn-primary accept-button'])}}
        </a>
    </div>
@stop    