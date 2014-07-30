<?php

/*
	CODE REVIEW:
		- fix indention
		- remove unnecessary comments
		- remove unnecessary functions
		- make variable names more descriptive
		- abbreviate long variable names, but insert comment to describe
*/

class PurchaseRequestController extends Controller 
{

	public function create()
	{
		$office = Office::all();
		$users = User::all();
		$workflow = Workflow::all();
		return View::make('purchaseRequest.purchaseRequest_create')
		->with('office',$office)
		->with('users',$users)
		->with('workflow',$workflow);
	}

	public function create_submit()
	{

//Image Upload

$puchase=Purchase::orderby('id', 'DESC')->first();

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
				        while ( $newheight > 525) 
				        {
				        	$newheight=$newheight*0.8;
				        	$newwidth=$newwidth*0.8;
						}

	    				$source=$upload_image;
	    				$ext  = strtolower($ext);
						$thumb = imagecreatetruecolor($newwidth, $newheight);
						if ($ext=="jpg"||$ext=="jpeg")
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
						if ($ext=="jpg"||$ext=="jpeg")
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
				if (Session::get('imgerror')&&Input::hasfile('file'))
				{
					
					Session::forget('imgsuccess');
					//Image Error Return
					$purchase->delete();
					
					$task_details= TaskDetails::where('doc_id', $document->id)->delete();
					$document->delete();
					$message = "Failed to create purchase request.";
					$count= Count::where('doc_id', $document->id)->delete();
					// Set Main Error
					$message = "Failed to save purchase request.";
					Session::put('main_error', $message );

					// Get Other Error Messages
					$error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
					$error_projectType = $purchase->validationErrors->first('projectType');
					$error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
					$error_amount = $purchase->validationErrors->first('amount');
					$error_office = $purchase->validationErrors->first('office');
					$error_requisitioner = $purchase->validationErrors->first('requisitioner');
					$error_dateRequested = $purchase->validationErrors->first('dateRequested');
					$error_dateReceived = $purchase->validationErrors->first('dateReceived');

					// Inserting Error Message To a Session
					Session::put('error_projectPurpose', $error_projectPurpose );
					Session::put('error_sourceOfFund', $error_sourceOfFund );
					Session::put('error_amount', $error_amount );
					Session::put('error_office', $error_office );
					Session::put('error_requisitioner', $error_requisitioner );
					Session::put('error_dateRequested', $error_dateRequested );
					Session::put('error_dateReceived', $error_dateReceived );
					Session::put('error_projectType', $error_projectType );



					return Redirect::back()->withInput();
				} 


//End Image Upload
		$cno=Input::get('controlNo');
		$cnp= Purchase::where('controlNo', $cno )->count();


		if ($cnp!=0)
			return Redirect::back();
		

		$control_no = Input::get('controlNo');
		$controlNo_purchase = Purchase::where('controlNo', $control_no )->count();

		if ($controlNo_purchase != 0 )
			return Redirect::back();


		$purchase = new Purchase;
		$document = new Document;
		$purchase->projectPurpose = strip_tags(Input::get( 'projectPurpose' ));
		$purchase->sourceOfFund = strip_tags(Input::get( 'sourceOfFund' ));
		$purchase->amount = Input::get( 'amount' );
		$purchase->office = Input::get( 'office' );
		$purchase->requisitioner = Input::get( 'requisitioner' );
		$purchase->dateRequested = Input::get( 'dateRequested' );
		$purchase->dateReceived = Input::get( 'dateReceived' );
		$purchase->controlNo = Input::get('controlNo');
		$purchase->status = 'Active';
		$purchase->otherType = Input::get('otherType');



		if(Input::get('otherType') == 'pakyaw')
			$purchase->projectType = "None";
		else
			$purchase->projectType = Input::get('projectType');

		// Set creator id
		$user_id = Auth::user()->id;
		$purchase->created_by = $user_id;

		$purchase_save = $purchase->save();

		if($purchase_save)
		{
			
			$document->pr_id = $purchase->id;
			$document->work_id = Input::get('hide_modeOfProcurement');
			$document_save = $document->save();

			if($document_save)
			{
				$doc_id= $document->id;
				$workflow=Workflow::find($document->work_id);
				$section=Section::where('workflow_id',$document->work_id)->orderBy('section_order_id', 'ASC')->get();
				$firstnew=0;

				// Set due date;
				$new_purchase = Purchase::find($purchase->id);
				$workflow_id = Input::get('hide_modeOfProcurement');
				$workflow = Workflow::find($workflow_id);
				$addToDate = $workflow->totalDays;
				date_default_timezone_set("Asia/Manila");
				$dueDate = date('Y-m-d H:i:s', strtotime("+$addToDate days" ));
				$new_purchase->dueDate = $dueDate;
				$new_purchase->save();


				$tasks = Task::where('wf_id', $document->work_id)->orderBy('section_id', 'ASC')->orderBy('order_id', 'ASC')->get();


				
				foreach ($tasks as $task) 
				{
					$task_details = New TaskDetails;
					$task_details->task_id = $task->id;
					$stringamount=$new_purchase->amount;
					$stringamount=str_replace(str_split(','), '', $stringamount);
					$amount = (float)$stringamount;

					if($firstnew == 0)
					 	$task_details->status = "New";
				 	else
				 		$task_details->status = "Pending";
				 	//Project Type 
				 	if($task->taskName=="PRE-PROCUREMENT CONFERENCE"||$task->taskName=="ADVERTISEMENT"||$task->taskName=="PRE-BID CONFERENCE")
				 	{
				 		
				 		$task_details->status="Lock";

				 		if($new_purchase->projectType=="Goods/Services")
						{
							if($task->taskName=="PRE-PROCUREMENT CONFERENCE"||$task->taskName=="ADVERTISEMENT")
							{
								if(($amount>2000000))
								{
				
								$task_details->status="Pending";
				
								}
							}
							else if($task->taskName=="PRE-BID CONFERENCE")				
							{
								if(($amount>1000000))
								{
								$task_details->status="Pending";
								}
							}
						}
						elseif($new_purchase->projectType=="Infrastructure")
						{
							if($task->taskName=="PRE-PROCUREMENT CONFERENCE"||$task->taskName=="ADVERTISEMENT")
							{
								if(($amount>5000000))
								{
								$task_details->status="Pending";
								}
							}
							else if($task->taskName=="PRE-BID CONFERENCE")
							{
								if(($amount>1000000))
								{
								$task_details->status="Pending";
								}
							}				
						}
						elseif($new_purchase->projectType=="Consulting Services")
						{
							if(($amount>1000000))
							{
							$task_details->status="Pending";
							}
						}

				 	}
				 	//End Project Type

						$firstnew=1;
						$task_details->doc_id = $document->id;
						$task_details->save();

				}

				$users = User::all();
				foreach($users as $user)
				{
					$count = new Count;
					$count->user_id = $user->id;
					$count->doc_id = $doc_id;
					$count->save();
				}


				

				
				$pr_id = Session::get('pr_id');


				if (Session::get('doc_id'))
				{
					$doc_id =Session::get('doc_id');
					DB::table('attachments')
						->where('doc_id', $doc_id)
						->update(array( 'saved' => 1));

					DB::table('attachments')->where('saved', '=', 0)->delete();
					DB::table('document')->where('pr_id', '=',$pr_id )->where('id','!=',$doc_id)->delete();
					Session::forget('doc_id');
				}

		    	$connected = @fsockopen("www.google.com", 80);  //website, port  (try 80 or 443)
		    	$connected = false;
		   		if ($connected)
		   		{
					$sendee = DB::table('users')->where('id',$purchase->requisitioner)->first();
					$email = $sendee->email;
					$fname = $sendee->firstname;

					$data = [ 'id' => $purchase->id ];
					Mail::send('emails.template', $data, function($message) use($email, $fname)
					{
						$message->from('procurementTrackingSystem@tarlac.com', 'Procurement Tracking System Tarlac');
						$message->to($email, $fname)->subject('Tarlac Procurement Tracking System: New Purchase Request Created');
					}); 
				
					$notice = "Purchase request created successfully. ";
					// Insert data to reports table
					$date_received = Input::get( 'dateReceived' );
					$date_received = substr($date_received, 0, strrpos($date_received, ' '));

					$reports = Reports::whereDate($date_received)->first();

					if($reports == null)
					{
						$reports = new Reports;
						$reports->date = $date_received;
						$reports->pRequestCount = 1;


					}
					else
					{
						$reports->pRequestCount = $reports->pRequestCount + 1;
					}
					

					$reports->save();
					//End Reports	
				}
				else
				{
					// Insert data to reports table
					$date_received = Input::get( 'dateReceived' );
					$date_received = substr($date_received, 0, strrpos($date_received, ' '));

					$reports = Reports::whereDate($date_received)->first();

					if($reports == null)
					{
						$reports = new Reports;
						$reports->date = $date_received;
						$reports->pRequestCount = 1;


					}
					else
					{
						$reports->pRequestCount = $reports->pRequestCount + 1;
					}
					

					$reports->save();
					//End Reports	
			        $notice = "Purchase request created successfully. Email notice was not sent. ";
			    }

													  
				Session::put('notice', $notice);
				$office = Office::all();
				$users = User::all();
				$workflow = Workflow::all();

				return Redirect::to('purchaseRequest/view');
			} 
			else
			{
				$purchase->delete();
				$message = "Failed to create purchase request.";
				Session::put('main_error', $message );


				// Get Other Error Messages
				$error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
				$error_projectType = $purchase->validationErrors->first('projectType');
				$error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
				$error_amount = $purchase->validationErrors->first('amount');
				$error_office = $purchase->validationErrors->first('office');
				$error_requisitioner = $purchase->validationErrors->first('requisitioner');
				$error_dateRequested = $purchase->validationErrors->first('dateRequested');
				$error_dateReceived = $purchase->validationErrors->first('dateReceived');

				// Inserting Error Message To a Session
				Session::put('error_projectPurpose', $error_projectPurpose );
				Session::put('error_sourceOfFund', $error_sourceOfFund );
				Session::put('error_amount', $error_amount );
				Session::put('error_office', $error_office );
				Session::put('error_requisitioner', $error_requisitioner );
				Session::put('error_dateRequested', $error_dateRequested );
				Session::put('error_dateReceived', $error_dateReceived );
				Session::put('error_projectType', $error_projectType );

				if(Input::get('hide_modeOfProcurement') == "")
				{
					Session::put('m6', 'required' );
				}

				return Redirect::back()->withInput();
			} 
		}
		else
		{
			// Set Main Error
			$message = "Failed to create purchase request.";
			Session::put('main_error', $message );


			// Get Other Error Messages
			$error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
			$error_projectType = $purchase->validationErrors->first('projectType');
			$error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
			$error_amount = $purchase->validationErrors->first('amount');
			$error_office = $purchase->validationErrors->first('office');
			$error_requisitioner = $purchase->validationErrors->first('requisitioner');
			$error_dateRequested = $purchase->validationErrors->first('dateRequested');
			$error_dateReceived = $purchase->validationErrors->first('dateReceived');

			// Inserting Error Message To a Session
			Session::put('error_projectPurpose', $error_projectPurpose );
			Session::put('error_sourceOfFund', $error_sourceOfFund );
			Session::put('error_amount', $error_amount );
			Session::put('error_office', $error_office );
			Session::put('error_requisitioner', $error_requisitioner );
			Session::put('error_dateRequested', $error_dateRequested );
			Session::put('error_dateReceived', $error_dateReceived );
			Session::put('error_projectType', $error_projectType );

			if(Input::get('hide_modeOfProcurement') == "")
			{
				Session::put('error_modeOfProcurement', 'required' );
			}

			

			return Redirect::back()->withInput();

		} 
}

public function edit()
{
	$user_id=Auth::user()->id;
	//Office restriction
   	if (Entrust::hasRole('Administrator'))
    {}
    else if(Entrust::hasRole('Procurement Personnel'))
    {
    	$useroffice=Auth::user()->office_id;
        $maker= User::find( $purchase->requisitioner);
        $docget=Document::where('pr_id', $purchase->id)->first();
        $taskd = TaskDetails::where('doc_id',$docget->id)->where('assignee_id',$user_id)->count();
       
      	if($taskd!=0)
      	{}
        else if ($user_id==$purchase->created_by)
        {}
        else if ($useroffice!=$maker->office_id)
        	return Redirect::to('dashboard');
    }
    else
    {
    	$useroffice=Auth::user()->office_id;
        $maker= User::find( $purchase->requisitioner);
        if ($useroffice!=$maker->office_id)
            return Redirect::to('/dashboard');

    }
    
    //End Office restriction
	return View::make('pr_edit');
}

public function view()
{
	return View::make('pr_view');
}

public function viewAll()
{
	return View::make('purchaseRequest.purchaseRequest_all');
}
public function viewCancelled()
{
	return View::make('purchaseRequest.purchaseRequest_cancelled');
}

public function vieweach($id)
{
		$purchase = Purchase::find($id);
		$wfName = DB::table('document')->where('pr_id',$id)->first();

		//Office restriction
        if (Entrust::hasRole('Administrator'))
		{}
        else if(Entrust::hasRole('Procurement Personnel'))
        {
        	
       	}
  		else
        {
            $useroffice=Auth::user()->office_id;
            $maker= User::find( $purchase->requisitioner);
            if ($useroffice!=$maker->office_id)
            	return Redirect::to('/dashboard');
        }
        //End Office restriction

		// JAN SARMIENTO AWESOME CHECKLIST VARIABLES
		$secName1 = DB::table('section')->where('workflow_id',$wfName->work_id)->where('section_order_id','1')->first();
		$secName2 = DB::table('section')->where('workflow_id',$wfName->work_id)->where('section_order_id','2')->first();
		$secName3 = DB::table('section')->where('workflow_id',$wfName->work_id)->where('section_order_id','3')->first();

		if($wfName->work_id!=4)
		{
			$secName4 = DB::table('section')->where('workflow_id',$wfName->work_id)->where('section_order_id','4')->first();
			return View::make('purchaseRequest.purchaseRequest_view')
			->with('purchase', $purchase)->with('wfName',$wfName)->with('secName1',$secName1)->with('secName2',$secName2)->with('secName3',$secName3)->with('secName4',$secName4);
		}
		else
		{
			return View::make('purchaseRequest.purchaseRequest_view')
			->with('purchase', $purchase)->with('wfName',$wfName)->with('secName1',$secName1)->with('secName2',$secName2)->with('secName3',$secName3);
		}
	
}


public function viewClosed()
{
	return View::make('purchaseRequest.purchaseRequest_closed');
}

public function viewOverdue()
{
	return View::make('purchaseRequest.purchaseRequest_overdue');
}

public function viewSummary()
{

	$prCount = 0;
	$POCount = 0;
	$chequeCount = 0;

	$reports = Reports::all();
	foreach ($reports as $report) 
	{
		$prCount = $prCount + $report->pRequestCount;
		$POCount = $POCount + $report->pOrderCount;
		$chequeCount = $chequeCount + $report->chequeCount;
	}

	return View::make('purchaseRequest.summary')
		->with('prCount',$prCount)
		->with('POCount',$POCount)
		->with('chequeCount',$chequeCount);
}

public function getDateRange()
	{
		$start = Input::get('start');
		$end = Input::get('end');

		return $start.' '.$end;	
	}

public function edit_submit(){

$id = Input::get('id');
$purchase = Purchase::find(Input::get('id'));
$document = Document::where('pr_id', Input::get('id'))->first();
$purchase->projectPurpose = strip_tags(Input::get( 'projectPurpose' ));
$purchase->sourceOfFund = strip_tags(Input::get( 'sourceOfFund' ));
$purchase->office = Input::get( 'office' );
$purchase->requisitioner = Input::get( 'requisitioner' );
$purchase->dateRequested = Input::get( 'dateRequested' );
$purchase->dateReceived = Input::get( 'dateReceived' );
$purchase->controlNo = Input::get('controlNo');
$purchase_save = $purchase->save();

if($purchase_save)
{

	$document->pr_id = $purchase->id;
	$document_save = $document->save();
	
	if($document_save)
	{
		//Image Upload
		$doc_id= $document->id;

		foreach(Input::file('file') as $file)
		{
            $rules = array('file' => 'required|mimes:png,gif,jpeg,jpg|max:90000000000000000000');
            $validator = Validator::make(array('file'=> $file), $rules);
            $destine=public_path()."/uploads";

            if($validator->passes())
            {
              	$ext = $file->guessClientExtension(); 
                $ext = $file->getClientOriginalExtension();
                $filename = $file->getClientOriginalName();
        	
             	$archivo = value(function() use ($file){
      				$date = date('m-d-Y-h-i-s', time());
        			$filename = $date."-". $file->getClientOriginalName();
          			return strtolower($filename);
          		});
   
                $attach = new Attachments;
                $attach->doc_id=$doc_id;
				$attach->data = $archivo;
				$attach->save();

				$filename= $doc_id."_".$attach->id;
                $file->move($destine, $archivo);
   				$target_folder = $destine; 
    			$upload_image = $target_folder."/".$archivo;
   				$thumbnail = $target_folder."/resize".$archivo;
        		$actual = $target_folder."/".$archivo;

      			// THUMBNAIL SIZE
        		list($width, $height) = getimagesize($upload_image);
        		$newwidth = $width; 
        		$newheight = $height;
       	 			
       	 		while ( $newheight> 525) 
       	 		{
        			$newheight=$newheight*0.8;
        			$newwidth=$newwidth*0.8;
				}

    
				$thumb = imagecreatetruecolor($newwidth, $newheight);
				$source=$upload_image;
				$ext  = strtolower($ext);
				if ($ext=="jpg"||$ext=="jpeg")
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
				if ($ext=="jpg"||$ext=="jpeg")
 					imagejpeg($thumb, $thumbnail, 100);
				elseif ($ext=="png")
 					imagepng($thumb, $thumbnail, 9);
				elseif($ext="gif")
 					imagegif($thumb, $thumbnail, 100);
       			else 
       				echo "Invalid image";
					
				unlink($actual);
        			// FILE RENAMES
        		rename($thumbnail, $actual);
            }
            
            else
            {
                //Does not pass validation
                $errors = $validator->errors();
                Session::put('imgerror','Invalid file.');
                break;
            }

        }



		if (Session::get('imgerror')&&Input::hasfile('file'))
		{
			//Image Error Return
			// Set Main Error
			$message = "Failed to save purchase request.";
			Session::put('main_error', $message );


			// Get Other Error Messages
			$error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
			$error_projectType = $purchase->validationErrors->first('projectType');
			$error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
			$error_office = $purchase->validationErrors->first('office');
			$error_requisitioner = $purchase->validationErrors->first('requisitioner');
			$error_dateRequested = $purchase->validationErrors->first('dateRequested');
			$error_dateReceived = $purchase->validationErrors->first('dateReceived');

			// Inserting Error Message To a Session
			Session::put('error_projectPurpose', $error_projectPurpose );
			Session::put('error_sourceOfFund', $error_sourceOfFund );
			Session::put('error_office', $error_office );
			Session::put('error_requisitioner', $error_requisitioner );
			Session::put('error_dateRequested', $error_dateRequested );
			Session::put('error_dateReceived', $error_dateReceived );
			Session::put('error_projectType', $error_projectType );

			return Redirect::back()->withInput();
		}


		Session::put('imgsuccess','Files uploaded.');

		if (Session::get('imgerror'))
			Session::forget('imgerror');


		//End Image Upload

		$pr_id= Session::get('pr_id');

		if (Session::get('doc_id'))
		{
			$doc_id =Session::get('doc_id');
			DB::table('attachments')->where('doc_id', $doc_id)->update(array( 'saved' => 1));
			DB::table('attachments')->where('saved', '=', 0)->delete();
			DB::table('document')->where('pr_id', '=',$pr_id )->where('id','!=',$doc_id)->delete();
			Session::forget('doc_id');
		}

		$notice = "Purchase request saved successfully. ";										  
		Session::put('notice', $notice);

		return Redirect::to("purchaseRequest/vieweach/$id");
	}
		
}
	else
	{

			// Set Main Error
			$message = "Failed to save purchase request.";
			Session::put('main_error', $message );

			// Get Other Error Messages
			$error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
			$error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
			$error_office = $purchase->validationErrors->first('office');
			$error_requisitioner = $purchase->validationErrors->first('requisitioner');
			$error_dateRequested = $purchase->validationErrors->first('dateRequested');
			$error_dateReceived = $purchase->validationErrors->first('dateReceived');
			// Inserting Error Message To a Session
			Session::put('error_projectPurpose', $error_projectPurpose );
			Session::put('error_sourceOfFund', $error_sourceOfFund );
			Session::put('error_office', $error_office );
			Session::put('error_requisitioner', $error_requisitioner );
			Session::put('error_dateRequested', $error_dateRequested );
			Session::put('error_dateReceived', $error_dateReceived );

			// Get Other Error Messages
			$error_projectPurpose = $purchase->validationErrors->first('projectPurpose');
			$error_projectType = $purchase->validationErrors->first('projectType');
			$error_sourceOfFund = $purchase->validationErrors->first('sourceOfFund');
			$error_office = $purchase->validationErrors->first('office');
			$error_requisitioner = $purchase->validationErrors->first('requisitioner');
			$error_dateRequested = $purchase->validationErrors->first('dateRequested');
			$error_dateReceived = $purchase->validationErrors->first('dateReceived');

			// Inserting Error Message To a Session
			Session::put('error_projectPurpose', $error_projectPurpose );
			Session::put('error_sourceOfFund', $error_sourceOfFund );
			Session::put('error_office', $error_office );
			Session::put('error_requisitioner', $error_requisitioner );
			Session::put('error_dateRequested', $error_dateRequested );
			Session::put('error_dateReceived', $error_dateReceived );
			Session::put('error_projectType', $error_projectType );

			return Redirect::back()->withInput();
	}
}





public function changeForm($id)
{
	$reason = Input::get('hide_reason');
	$purchase = Purchase::find($id);
	$purchase->status = "Cancelled";
	$purchase->reason = $reason;
	$purchase->save();
	$doc= Document::where('pr_id',$id)->first();
	TaskDetails::where('doc_id', '=', $doc->id)->where('status','!=','Done')->delete();
	DB::table('count')->where('doc_id',$doc->id)->delete();
	$users= User::get();

foreach ($users as $user) 
{
	$count =new Count;
	$count->user_id=$user->id;
	$count->doc_id=$doc->id;
	$count->save();
}

	Session::put('notice', 'Purchase request has been cancelled.' );
	return Redirect::to("purchaseRequest/view");


}


public function checklistedit()
{

Session::put('goToChecklist', 'true' ); 

//Initializations	
$taskdetails_id= Input::get('taskdetails_id');
$assignee=strip_tags(Input::get('assignee'));
$mydate=Input::get('dateFinished');
$timestamp = strtotime($mydate);
$dateFinished= date("Y-m-d H:i:s", $timestamp);
$daysOfAction=Input::get('daysOfAction');
$remarks=" ".strip_tags(Input::get('remarks'));
$check=0;

//Validation Process

if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$remarks)))
        $check=$check+1;
         
if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$assignee)))
        $check=$check+1;
         
if(ctype_digit($daysOfAction))
{
	if($daysOfAction>=0)
		$check=$check+1;
}

if (($check==3||($remarks==" "&&$check==2))&&$assignee!=NULL)
{


	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);


	//PO Section Check
	$taskcurrent=Task::find($taskd->task_id);
	if($taskcurrent->taskName=="BAC (DELIVERY)"||$taskcurrent->taskName=="Governor's Office")
	{
		$dateFinished = substr($dateFinished, 0, strrpos($dateFinished, ' '));

		$reports = Reports::whereDate($dateFinished)->first();

		if($reports == null)
		{
			$reports = new Reports;
			$reports->date = $dateFinished;
			$reports->pOrderCount = 1;
			//return $reports . " aw " . $dateFinished;
		}
		else
		{
			$reports->pOrderCount = $reports->pOrderCount + 1;
		}

		$reports->save();
	}

	//End PO Section Check
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->daysOfAction=$daysOfAction;
	$taskd->dateFinished=$dateFinished;
	$taskd->assignee=$assignee;
	$remarks= ltrim ($remarks,'0');
	$taskd->remarks=$remarks;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}




public function insertaddon(){
//Initialization
Session::put('goToChecklist', 'true' ); 
$otherDetails_id= Input::get('otherDetails_id');
$purchase_request_id= Input::get('purchase_request_id');
$value= strip_tags(Input::get('value'));

if(ctype_alnum(str_replace(str_split(' !\\/:*?".,|'),'',$value)))
{
    $insertvalue= new Values;
    $insertvalue->otherDetails_id=$otherDetails_id;
    $insertvalue->purchase_request_id=$purchase_request_id;
    $insertvalue->value=$value;
    $insertvalue->save();
    Session::put('successlabel', 'Successfully saved.');
    return Redirect::back();
}
else 
{
    Session::put('errorlabel','Invalid input.');
	return Redirect::back();
}
}


public function editaddon()
{
	Session::put('goToChecklist', 'true' ); 
	$values_id= Input::get('values_id');
    $insertvalue= Values::find($values_id);
    $insertvalue->delete();   
    return Redirect::back();
}
  
public function editpagecall($id)
{

	return View::make('pr_edit')->with('id',$id);

}

public function delimage()
{
	$id = Input::get('hide');
	$attachn=DB::table('attachments')->where('id', $id)->first();
	$destine= public_path()."/uploads/";
	unlink($destine.$attachn->data);
	$attach = DB::table('attachments')->where('id', $id)->delete();
	$notice="Attachment successfully deleted.";
	return Redirect::back()->with('notice', $notice);
}


//Other Tasks Functions
public function certification()
{

Session::put('goToChecklist', 'true' ); 
//Initializations
$radio=Input::get('radio');
$by=strip_tags(Input::get('by'));
$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process
if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
        $check=$check+1;

if ($check==1)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$radio;
	$taskd->custom2=$by;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}


public function posting()
{
Session::put('goToChecklist', 'true' ); 
//Initializations
$date=Input::get('date');
$referenceno=strip_tags(Input::get('referenceno'));
$by=strip_tags(Input::get('by'));
$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process

         
if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
        $check=$check+1;
if(ctype_alnum(str_replace(array(' ', '-', '.'),'',$referenceno)))
        $check=$check+1;

if ($check==2)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$referenceno;
	$taskd->custom2=$date;
	$taskd->custom3=$by;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}


public function supplier()
{
Session::put('goToChecklist', 'true' ); 
//Initializations
$supplier=Input::get('supplier');
$amount=Input::get('amount');
$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process
if(ctype_alnum(str_replace(array(' ', '-', '.'),'',$supplier)))
        $check=$check+1;
if(ctype_digit(str_replace(array(' ', ',', '.'),'',$amount)))
        $check=$check+1;

if ($check==2)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$supplier;
	$taskd->custom2=$amount;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}




public function cheque()
{
Session::put('goToChecklist', 'true' ); 
//Initializations
$amt=Input::get('amt');
$num=Input::get('num');
$date=Input::get('date');
$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process
if(ctype_alnum(str_replace(array(' ', '-', '.'),'',$num)))
        $check=$check+1;
if(ctype_digit(str_replace(array(' ', ',', '.'),'',$amt)))
        $check=$check+1;

if ($check==2)
{

	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);

	//Cheque

	$timestamp = strtotime($date);
	$dateFinished = date("Y-m-d H:i:s", $timestamp);

	$dateFinished = substr($dateFinished, 0, strrpos($dateFinished, ' '));
	
	$reports = Reports::whereDate($dateFinished)->first();

	if($reports == null)
	{
		$reports = new Reports;
		$reports->date = $dateFinished;
		$reports->chequeCount = 1;

	}
	else
	{
		$reports->chequeCount = $reports->chequeCount + 1;
	}

	$reports->save();



	//End Cheque
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$amt;
	$taskd->custom2=$num;
	$taskd->custom3=$date;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}


public function published()
{
Session::put('goToChecklist', 'true' ); 
//Initializations
$datepublished=Input::get('datepublished');
$enddate=Input::get('enddate');
$by=Input::get('by');
$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process
if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
        $check=$check+1;


if ($check==1)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$datepublished;
	$taskd->custom2=$enddate;
	$taskd->custom3=$by;

	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}

public function documents()
{
Session::put('goToChecklist', 'true' ); 
//Initializations
$date=Input::get('date');
$biddingdate=Input::get('biddingdate');
$by=Input::get('by');
$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process
if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
        $check=$check+1;


if ($check==1)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$date;
	$taskd->custom2=$biddingdate;
	$taskd->custom3=$by;

	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}



public function evaluations()
{
Session::put('goToChecklist', 'true' ); 
//Initializations
$date=Input::get('date');
$noofdays=Input::get('noofdays');
$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process
if(ctype_digit($noofdays))
        $check=$check+1;


if ($check==1)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$date;
	$taskd->custom2=$noofdays;


	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}


public function conference()
{
Session::put('goToChecklist', 'true' ); 
//Initializations

$date=Input::get('date');
$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process



if ($check==0)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$date;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}



//End Other Tasks Functions

public function contractmeeting()
{
Session::put('goToChecklist', 'true' ); 
//Initializations
$date=Input::get('date');
$noofdays=Input::get('noofdays');
$contractmeeting=Input::get('contractmeeting');

$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process
if(ctype_alnum(str_replace(array(' ', '-', '.'),'',$contractmeeting)))
        $check=$check+1;
if(ctype_digit($noofdays))
        $check=$check+1;

if ($check==2)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$date;
	$taskd->custom2=$noofdays;
	$taskd->custom3=$contractmeeting;

	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}

public function rfq()
{
Session::put('goToChecklist', 'true' ); 
//Initializations
$noofsuppliers=Input::get('noofsuppliers');
$date=Input::get('date');

$by=Input::get('by');

$taskdetails_id=Input::get('taskdetails_id');
$check=0;

//Validation Process
if(ctype_alnum(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$by)))
        $check=$check+1;
if(ctype_digit($noofsuppliers))
        $check=$check+1;

if ($check==2)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->custom1=$noofsuppliers;
	$taskd->custom2=$date;
	$taskd->custom3=$by;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}


public function datebyremark()
{
Session::put('goToChecklist', 'true' ); 
//Initializations	
$taskdetails_id= Input::get('taskdetails_id');
$assignee=strip_tags(Input::get('assignee'));
$mydate=Input::get('dateFinished');
$timestamp = strtotime($mydate);
$dateFinished= date("Y-m-d H:i:s", $timestamp);

$remarks=" ".strip_tags(Input::get('remarks'));
$check=0;

//Validation Process

if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$remarks)))
        $check=$check+1;
         
if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$assignee)))
        $check=$check+1;
         

if (($check==2||($remarks==" "&&$check==1))&&$assignee!=NULL)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";

	$taskd->dateFinished=$dateFinished;
	$taskd->assignee=$assignee;
	$remarks= ltrim ($remarks,'0');
	$taskd->remarks=$remarks;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}


public function dateby()
{
Session::put('goToChecklist', 'true' ); 
//Initializations	
$taskdetails_id= Input::get('taskdetails_id');
$assignee=strip_tags(Input::get('assignee'));
$mydate=Input::get('dateFinished');
$timestamp = strtotime($mydate);
$dateFinished= date("Y-m-d H:i:s", $timestamp);
$check=0;

//Validation Process


         
if(ctype_alpha(str_replace(array(' ', '-', '.', ',', 'ñ', 'Ñ'),'',$assignee)))
        $check=$check+1;
         


if (($check==1)&&$assignee!=NULL)
{
	$taskd= TaskDetails::find($taskdetails_id);
	$docs=Document::find($taskd->doc_id);
	$delcount= Count::where('doc_id', $docs->id)->delete();
	$userx= User::get();
	foreach($userx as $userv)
	{
		$count= new Count;
		$count->user_id= $userv->id;
		$count->doc_id= $docs->id;
		$count->save();
	}

	Session::put('successchecklist','Saved.');

	$taskd= TaskDetails::find($taskdetails_id);
	$taskd->status="Done";
	$taskd->dateFinished=$dateFinished;
	$taskd->assignee=$assignee;
	$taskd->save();
	$tasknext=TaskDetails::find($taskdetails_id+1);
	$tasknextc=TaskDetails::where('id', $taskdetails_id+1)->where('doc_id', $docs->pr_id)->count();

	if ($tasknextc!=0)
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
}
else
	Session::put('errorchecklist','Invalid input.');
	
return Redirect::back();

}



}