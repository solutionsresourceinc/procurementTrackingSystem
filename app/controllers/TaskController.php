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
		return View::make('tasks.overdue_tasks');
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
		$taskDetails->save();
		return Redirect::to('task/task-id');
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