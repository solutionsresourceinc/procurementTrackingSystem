<?php

class AjaxController extends Controller
{

	public function workflowSubmit()
	{
		$designation = e(Input::get('designa'));

		if($designation == 0)
		{
			$des_name = "";
		}
		else
		{
			$des = Designation::find($designation);
			$des_name = e($des->designation);
		}

		$id = Input::get('task_id');
		$assignd = Task::find($id);
		$assignd->designation_id = Input::get('designa');
		$assignd->save();

		if($designation == 0)
		{
			$data = array(
				"html" => "<div id='insert_$id' class='mode1'> None  </div>"
			);
		}
		else
		{
			$data = array(
				"html" => "<div id='insert_$id' class='mode1'> $des_name  </div>"
			);
		}

		return Response::json($data);
	}

}