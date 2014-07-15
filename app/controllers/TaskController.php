<?php

/*
	CODE REVIEW:
		- remove comments
		- fix indention
*/

class TaskController extends Controller {

	public function hehe()
	{

		$desc = Task::find($id);
		$data = array(
			"html" => "<div id='description_body'>  $desc->description </h6> </p></div>"
		);
		return Response::json($data);
	}

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

		$taskd= TaskDetails::find($taskdetails_id);
		$taskd->status="Done";
		$docs=Document::find($taskd->doc_id);

		$delcount= Count::where('doc_id', $docs->id)->delete();
		  
		$userx= User::get();
		foreach($userx as $userv){
			$count= new Count;
			$count->user_id= $userv->id;
			$count->doc_id= $docs->id;
			$count->save();
		}

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
			$purchase= Purchase::find($docs->pr_id);
			$purchase->status="Closed";
			$purchase->save();
		}

		return Redirect::to('task/active');
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
   		if(ctype_alnum(str_replace(str_split(' \\/:*?".,|'),'',$remarks)))
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



public function addtask()
	{
		$section_id= Input::get('section_id');

		$label= Input::get('label');
	if(ctype_alpha(str_replace(str_split(' \\/:*?".,|'),'',$label)))
        {
        	$newtask= new OtherDetails;
        	$newtask->section_id=$section_id;
        	$newtask->label= $label;
        	$newtask->save();
        	Session::put('successlabel', 'Successfully added new task.');
        	return Redirect::back();
        }
    else {
    	Session::put('errorlabel','Invalid label.');
return Redirect::back();

    }

	}
public function deladdtask()
	{
		$otherdetails_id= Input::get('id');

        	$delOD=OtherDetails::find($otherdetails_id);
   $delOD->delete();
  Values::where('otherDetails_id', $otherdetails_id)->delete();
   return Redirect::back();
      }


      public function taskpagecall($id){

	$user_id = Auth::User()->id;
	$taskd = TaskDetails::find($id);

	$task= Task::find($taskd->task_id);
	$desig= UserHasDesignation::where('users_id', $user_id)->where('designation_id', $task->designation_id)->count();

	if ($taskd->status=="New"){
		if($desig==0)
		{
			return Redirect::to('/');
		} 
		else{
			Session::put('taskdetails_id', $id);

		return View::make('tasks.task');
		}
	}
	else{
		if ($taskd->assignee_id==$user_id){
			Session::put('taskdetails_id', $id);
			return View::make('tasks.task');
		}
		else
			return Redirect::to('/');
	}

      }


}