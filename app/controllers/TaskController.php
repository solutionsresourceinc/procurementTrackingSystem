<?php

class TaskController extends Controller {

	public function active()
	{
		return View::make('tasks.active_tasks');
	}

	public function overdue()
	{
		return View::make('tasks.overdue_tasks');
	}

}