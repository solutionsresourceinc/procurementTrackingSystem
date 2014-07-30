@extends('layouts.dashboard')

@section('content')
    <h1 class="page-header">New Tasks</h1>
    <div class="list-group">

        @foreach($user_designations as $designation)
        
            <?php 
                // Get User id
                $user_id = Auth::user()->id;
                // Fetching a row from designation table
                $designation_row = Designation::find($designation->designation_id);
                // Get all task in the assigned to that designation
                $task_row = Task::whereDesignationId($designation->designation_id)->where('designation_id','!=', '0')->get();
            ?>
            
            @foreach($task_row as $task) 
                <!-- Get all task details with id = task->id -->
                <?php
                    $taskDetails_row = TaskDetails::whereTaskId($task->id)->whereStatus("New")->whereAssigneeId(0)->paginate(10); 
                    $show_pagination = 1;

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
                        $controlNo = $purchase_row->controlNo;
                    ?>

                    <a href="/task/{{$taskDetail->id}}" class="list-group-item tasks">
                        <div class="pull-left task-desc" style="margin-left: 10px;">
                            <span class="list-group-item-heading">{{ $task->taskName  }}</span> &nbsp;<small><i> </small><br/>
                            <span class="list-group-item-text">{{ $task->description }}</span> &nbsp;
                            <br>Control No. : <small><font color="blue"><?php echo str_pad($controlNo, 5, '0', STR_PAD_LEFT); ?></font></small>
                            <br> Project/Purpose: <small><font color="blue">{{$projectName}}</font></i></small>
                        </div> 
                        {{ Form::open() }}
                            {{ Form::hidden('hide_taskid',$taskDetail->id) }}
                            {{ Form::submit('Accept Task', ['class' => 'btn btn-sm btn-primary accept-button'])}}     
                        {{ Form::close() }}
                    </a>
               
                @endforeach
            @endforeach 
              
        @endforeach

        <div>
               {{Session::forget('imgerror');}}
                   {{Session::forget('imgsuccess');}}

            @if(Session::get('$taskDetails_row'))
                <center>{{ $taskDetails_row->links(); }}</center>
                
             @endif
        </div>
    </div>
@stop    