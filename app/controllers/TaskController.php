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
		return View::make('tasks.active_tasks');
	}

	public function overdue()
	{
		return View::make('tasks.overdue_tasks');
	}

	public function viewTask()
	{
		return View::make('tasks.task');
	}
}