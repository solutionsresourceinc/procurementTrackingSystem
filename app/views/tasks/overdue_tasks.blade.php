@extends('layouts.dashboard')

@section('header')

@stop

@section('content')
    <h1 class="page-header">Overdue Tasks</h1>
    <div class="list-group">

        @foreach($user_designations as $designation)
        
            <?php 
                // Get User id
                $user_id = Auth::user()->id;
                // Fetching a row from designation table
                $designation_row = Designation::find($designation->designation_id);
                // Get all task in the assigned to that designation
                $task_row = Task::whereDesignationId($designation->designation_id)->get();
            ?>

            @foreach($task_row as $task) 
                <!-- Get all task details with id = task->id -->
                <?php 
                	$date_today =date('Y-m-d H:i:s');
                    $taskDetails_row = TaskDetails::whereTaskId($task->id)->whereStatus("Active")->where("dueDate","<",$date_today)->whereAssigneeId($user_id)->get(); 
                    $workflow_id = $task->wf_id;
                    $workflow_row = Workflow::find($workflow_id);
                    $workflowName = $workflow_row->workFlowName;
                ?>

				@foreach($taskDetails_row as $taskDetail)
                    <?php 
                        $doc_id = $taskDetail->doc_id;
                        $document_row = Document::find($doc_id);
                        $purchase_id = $document_row->pr_id;
                        $purchase_row = Purchase::find($purchase_id);
                        $projectName = $purchase_row->projectPurpose;
                    ?>
                    
                    <a href="/task/{{$taskDetail->id}}" class="list-group-item tasks">
                        <div class="pull-left task-desc" style="margin-left: 10px;">
                            <span class="list-group-item-heading">{{ $task->taskName  }}</span> &nbsp;<small><i> </small><br/>
                            <span class="list-group-item-text">{{ $task->description }}</span> &nbsp; Project: <small><font color="blue">{{$projectName}}</font></small>
                        </div> 
                        {{ Form::open() }}
                        {{ Form::hidden('hide_taskid',$taskDetail->id) }}
                        {{Form::submit('Accept Task', ['class' => 'btn btn-sm btn-primary accept-button'])}}     

                        {{ Form::close() }}
                    </a>
                    <br><br><br><br>
                @endforeach
            @endforeach 
                 <br><br><br><br><br>
        @endforeach

    </div>
    
@stop    