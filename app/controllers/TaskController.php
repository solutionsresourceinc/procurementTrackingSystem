<?php

class TaskController extends Controller {

	public function newTask()
	{
		return View::make('tasks.new_tasks');
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