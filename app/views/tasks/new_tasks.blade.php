@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">New Tasks</h1>
    <div class="list-group">

        <!-- Get all task details with id = task->id -->
        <?php
            $user_id = Auth::user()->id;
            
            $taskDetails_row = TaskDetails::whereIn('task_id',$taskIds)->whereStatus("New")->paginate(10); 
        ?>

        @foreach($taskDetails_row as $taskDetail)
            {{Session::put('$taskDetails_row','true');}}
            <?php 

                $task = Task::find($taskDetail->task_id);
                $doc_id = $taskDetail->doc_id;
                $document_row = Document::find($doc_id);
                $purchase_id = $document_row->pr_id;
                $purchase_row = Purchase::find($purchase_id);
                $projectName = $purchase_row->projectPurpose;
                $controlNo = $purchase_row->controlNo;
            ?>

            <a href="/task/{{$taskDetail->id}}" class="list-group-item tasks">
                <div class="pull-left task-desc" style="margin-left: 10px;">
                    <span class="list-group-item-heading">{{ $task->taskName  }}</span> &nbsp;<small><i> </small><br/>
                    <span class="list-group-item-text">{{ $task->description   }}</span> &nbsp;
                    <br>Control No. : <small><font color="blue"><?php echo str_pad($controlNo, 5, '0', STR_PAD_LEFT); ?></font></small>
                    <br>Project/Purpose: <small><font color="blue">{{$projectName}}</font></small>
                </div> 
                {{ Form::open() }}
                    {{ Form::hidden('hide_taskid',$taskDetail->id) }}
                    {{ Form::submit('Accept Task', ['class' => 'btn btn-sm btn-primary accept-button'])}}     
                {{ Form::close() }}
            </a>
               
        @endforeach

        <div>
            @if(Session::get('$taskDetails_row'))
                <center>{{ $taskDetails_row->links(); }}</center>
            @endif
        </div>
    </div>
@stop    