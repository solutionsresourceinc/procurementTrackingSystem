<?php

class TaskController extends Controller {

	public function newTask()
	{
		$user_id = Auth::user()->id;
		$user_designations = UserHasDesignation::whereUsersId($user_id)->get();

		return View::make('tasks.new_tasks')
				->with('user_designations',$user_designations);
		//return $user_designations;
	}

	public function active()
	{


		$user_id = Auth::user()->id;
		$user_designations = UserHasDesignation::whereUsersId($user_id)->get();

		return View::make('tasks.active_tasks')
				->with('user_designations',$user_designations);
	}

	public function overdue()
	{
		$user_id = Auth::user()->id;
		$user_designations = UserHasDesignation::whereUsersId($user_id)->get();

		return View::make('tasks.overdue_tasks')
				->with('user_designations',$user_designations);
	}



public function done(){
$taskdetails_id=Input::get('taskdetails_id');
$pr_id=Input::get('pr_id');
$taskd= TaskDetails::find($taskdetails_id);
$taskd->status="Done";
  
$birth = new DateTime($taskd->dateReceived); 
$today = new DateTime(); 
$diff = $birth->diff($today); 
$aDays= $diff->format('%d');
$taskd->daysOfAction=$aDays;
$taskd->dateFinished=$today;
$taskd->save();
$tasknext=TaskDetails::find($taskdetails_id+1);
if ($tasknext->doc_id==$taskd->doc_id)
{
$tasknext->status="New";
$tasknext->save();
}
else
{
$purchase= Purchase::find($pr_id);
$purchase->status="Closed";
$purchase->save();
}

return Redirect::back();
}


	public function viewTask()
	{
		return View::make('tasks.task');
	}


	public function assignTask()
	{
		$id = Input::get('hide_taskid');
		$user_id = Auth::user()->id;
		$taskDetails = TaskDetails::find($id);
		$taskDetails->assignee_id = $user_id;
		$taskDetails->status = "Active";
		
		$task_row = Task::find($taskDetails->task_id);
		$addToDateReceived = $task_row->maxDuration;

		// Get date today and the due date;
		$dateReceived = date('Y-m-d H:i:s');
		$dueDate = date('Y-m-d H:i:s', strtotime("$addToDateReceived days" ));

		$taskDetails->dateReceived = $dateReceived;
		$taskDetails->dueDate = $dueDate;

		$taskDetails->save();

		return Redirect::to("task/$id");
	}
public function remarks()
 	{
 $id= Input::get('taskdetails_id');
 	$remarks =Input::get('remarks');
   if(ctype_alpha(str_replace(array(' ', '-', '.'),'',$remarks)))
         {
 $taskd=TaskDetails::find($id);
 $taskd->remarks=$remarks;
         $taskd->save();
         Session::put('successremark', 'Remarks saved.');
         return Redirect::back();
         }
     else{
 
    Session::put('errorremark', 'Invalid remarks.');
         return Redirect::back();
 
     }
 
 
 
 	}
}