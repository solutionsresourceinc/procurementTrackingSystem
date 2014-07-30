<?php

class TaskController extends Controller {

	public function newTask()
	{
		$user_id = Auth::user()->id;
		$user_designations = UserHasDesignation::whereUsersId($user_id)->where('designation_id','!=','0')->get();

		$userDesignations = "";
		$counter = 1;

		foreach ($user_designations as $designation) 
		{
			if($counter == 1)
				$userDesignations = $userDesignations . "$designation->designation_id";
			else
				$userDesignations = $userDesignations . ",$designation->designation_id";
			$counter++;
		}

		$task_row = Task::whereDesignationId($designation->designation_id)->where('designation_id','!=', '0')->get();
		$task_row  = DB::select("select * from tasks where designation_id in ( $userDesignations )");

		$taskIds = array();
		$counter = 1;
		foreach($task_row as $task)
		{
			array_push($taskIds, $task->id);
		}

		return View::make('tasks.new_tasks')->with('taskIds',$taskIds);
	}

	public function active()
	{
		$user_id = Auth::user()->id;
		$user_designations = UserHasDesignation::whereUsersId($user_id)->where('designation_id','!=','0')->get();

		$userDesignations = "";
		$counter = 1;

		foreach ($user_designations as $designation) 
		{
			if($counter == 1)
				$userDesignations = $userDesignations . "$designation->designation_id";
			else
				$userDesignations = $userDesignations . ",$designation->designation_id";
			$counter++;
		}

		$task_row = Task::whereDesignationId($designation->designation_id)->where('designation_id','!=', '0')->get();
		$task_row  = DB::select("select * from tasks where designation_id in ( $userDesignations )");

		$taskIds = array();
		$counter = 1;
		foreach($task_row as $task)
		{
			array_push($taskIds, $task->id);
		}

		return View::make('tasks.active_tasks')->with('taskIds',$taskIds);
	}

	public function overdue()
	{
		$user_id = Auth::user()->id;
		$user_designations = UserHasDesignation::whereUsersId($user_id)->where('designation_id','!=','0')->get();

		$userDesignations = "";
		$counter = 1;

		foreach ($user_designations as $designation) 
		{
			if($counter == 1)
				$userDesignations = $userDesignations . "$designation->designation_id";
			else
				$userDesignations = $userDesignations . ",$designation->designation_id";
			$counter++;
		}

		$task_row = Task::whereDesignationId($designation->designation_id)->where('designation_id','!=', '0')->get();
		$task_row  = DB::select("select * from tasks where designation_id in ( $userDesignations )");

		$taskIds = array();
		$counter = 1;
		foreach($task_row as $task)
		{
			array_push($taskIds, $task->id);
		}

		return View::make('tasks.overdue_tasks')->with('taskIds',$taskIds);
	}

	public function done()
	{
		$taskdetails_id=Input::get('taskdetails_id');
		$taskd= TaskDetails::find($taskdetails_id);
		$taskd->status="Done";
		$docs=Document::find($taskd->doc_id);
		$delcount= Count::where('doc_id', $docs->id)->delete();
		$users = User::get();
		foreach($users as $user)
		{
			$count= new Count;
			$count->user_id= $user->id;
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
		//Project Type Filter
		$counter=1;
		$tasknext=TaskDetails::find($taskdetails_id+$counter);
	
		while($tasknext->status=="Lock")
		{
			$counter=$counter+1;
			$tasknext=TaskDetails::find($taskdetails_id+$counter);
		}
	
		$tasknext->status="New";
		$tasknext->save();
		//End Project Type Filter
		}
		else
		{
			$purchase= Purchase::find($docs->pr_id);
			$purchase->status="Closed";
			$purchase->save();
		}
		// return Redirect::to('task/active');
		$request_id = Input::get('pr_id');
		return Redirect::to("purchaseRequest/vieweach/$request_id");
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
	 	$remarks =" ".Input::get('remarks');

	   	if(ctype_alnum(str_replace(str_split(' \\/:*?".,|-'),'',$remarks))||$remarks==" ")
	    {
			$taskd=TaskDetails::find($id);
			$taskd->remarks=$remarks;
		    $taskd->save();
		    Session::put('successremark', 'Remarks saved.');
		    return Redirect::back();
	    }
	    else
	    {
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
	    else 
	    {
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
	  	Session::put('successlabel', 'Successfully deleted task.');
	   	return Redirect::back();
	}

	public function taskpagecall($id)
	{
		$user_id = Auth::User()->id;
		$taskd = TaskDetails::find($id);
		$task= Task::find($taskd->task_id);
		$desig= UserHasDesignation::where('users_id', $user_id)->where('designation_id', $task->designation_id)->count();
		if ($taskd->status=="New")
		{
			if($desig==0)
				return Redirect::to('/');
			else
			{
				Session::put('taskdetails_id', $id);
				return View::make('tasks.task');
			}
		}
		else
		{
			if ($taskd->assignee_id==$user_id)
			{
				Session::put('taskdetails_id', $id);
				return View::make('tasks.task');
			}
			else
				return Redirect::to('/');
		}
	}

	public function taskimage()
	{

		$doc_id= Input::get('doc_id');
		//Image Upload
		foreach(Input::file('file') as $file)
		{
       		$rules = array(
         		'file' => 'required|mimes:png,gif,jpeg,jpg|max:900000000000000000000'
           	);

			$validator = \Validator::make(array('file'=> $file), $rules);
	        $destine = public_path()."/uploads";
	        if($validator->passes())
	        {
		    	$ext = $file->guessClientExtension(); // (Based on mime type)
		        $ext = $file->getClientOriginalExtension(); // (Based on filename)
		        $filename = $file->getClientOriginalName();
	            $archivo = value(function() use ($file)
				{
		        	$filename = str_random(10) . '.' . $file->getClientOriginalExtension();
		        	return strtolower($filename);
	   			});
				$archivo = value(function() use ($file)
				{
					$date = date('m-d-Y-h-i-s', time());
					$filename = $date."-". $file->getClientOriginalName();
					return strtolower($filename);
				});
		        $attach = new Attachments;
		        $attach->doc_id=$doc_id;
				$attach->data = $archivo;
				$attach->save();
				$filename = $doc_id."_".$attach->id;
		        $file->move($destine, $archivo);
		   		$target_folder = $destine; 
		   		$upload_image = $target_folder."/".$archivo;
				$thumbnail = $target_folder."/resize".$archivo;
		        $actual = $target_folder."/".$archivo;
	
		     	// THUMBNAIL SIZE
				list($width, $height) = getimagesize($upload_image);
				$newwidth = $width; 
				$newheight = $height;
				while( $newheight > 525) 
				{
					$newheight=$newheight*0.8;
					$newwidth=$newwidth*0.8;
				}
   				$source=$upload_image;
   				$ext  = strtolower($ext);
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				if($ext=="jpg"||$ext=="jpeg")
					$source = imagecreatefromjpeg($upload_image);
				elseif ($ext=="png")
					$source = imagecreatefrompng($upload_image);
				elseif ($ext=="gif")
					$source = imagecreatefromgif($upload_image);
				else
        			continue;	      

				// RESIZE 
				imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
				
				// MAKE NEW FILES
				if($ext=="jpg"||$ext=="jpeg")
					imagejpeg($thumb, $thumbnail, 100);
				elseif ($ext=="png")
					imagepng($thumb, $thumbnail, 9);
				elseif($ext=="gif")
					imagegif($thumb, $thumbnail, 100);
				else
					echo "An invalid image";

				unlink($actual);
				
				// FILE RENAMES
				rename($thumbnail, $actual);
			}
		    else
		    {
		    	$errors = $validator->errors();
		        Session::put('imgerror','Invalid file.');
		    }

        }
		Session::put('imgsuccess','Files uploaded.');
		
		if (Session::get('imgerror'))
			Session::forget('imgsuccess');
					
		return Redirect::back();
		//End Image Upload
	}
}