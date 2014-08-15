<?php

class BaseController extends Controller {

	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	public function janisawesome()
	{
		function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    	for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    	}
	    	return $randomString;
		}

		function generateRandomAmount() 
		{
			$length = rand(4, 8);
	    	$randomString = '';
	    	for ($i = 0; $i < $length; $i++) 
	    	{
	        	$randomString .= rand(1,9);
	    	}
	    	$randomString .= ".00";
	    	return number_format($randomString);
		}

		$numLoop = 1;
		while($numLoop < 301)
		{
			$amtControl = generateRandomAmount();
			$purchase = new Purchase;
			$document = new Document;
			$purchase->projectPurpose = generateRandomString();
			$purchase->sourceOfFund = generateRandomString();
			$purchase->amount = generateRandomAmount();
			$purchase->office = "1";
			$purchase->requisitioner = "1";
			$purchase->dateRequested = date('Y-m-d H:i:s');
			$purchase->dateReceived = date('Y-m-d H:i:s');
			$purchase->status = 'Cancelled';
			$purchase->otherType = " ";

			// Get latest control number
			$cn = 0;
			$purchase_controlNo = Purchase::orderBy('ControlNo', 'DESC')->first();
			if(!$purchase_controlNo == NuLL)
			{
				$cn = $purchase_controlNo->controlNo;
			}
			$cn += 1;
			$purchase->controlNo = $cn;
			if(Input::get('otherType') == ' ')
				$purchase->projectType = "None";
			else
				$purchase->projectType = "None";
			// Set creator id
			$user_id = Auth::user()->id;
			$purchase->created_by = $user_id;
			$purchase_save = $purchase->save();
			if($purchase_save)
			{
				if($purchase->amount <= 50000)
					$amtControl = 1;
				else if($purchase->amount > 50000 && $purchase->amount < 500000)
					$amtControl = 2;
				else if($purchase->amount >= 500000)
					$amtControl = 3;

				$document->pr_id = $purchase->id;
				$document->work_id = $amtControl;
				$document_save = $document->save();
				if($document_save)
				{
					$doc_id= $document->id;
					$workflow=Workflow::find($document->work_id);
					$section=Section::where('workflow_id',$document->work_id)->orderBy('section_order_id', 'ASC')->get();
					$firstnew=0;
					// Set due date;
					$new_purchase = Purchase::find($purchase->id);
					$workflow_id = "1";
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


						
						DB::table('attachments')
							->where('doc_id', $doc_id)
							->update(array( 'saved' => 1));

						DB::table('attachments')->where('saved', '=', 0)->delete();
					Session::forget('doc_id');
					

			    	$connected = true;
			    	// $connected = @fsockopen("www.google.com", 80);  //website, port  (try 80 or 443)
			   		if (!$connected)
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

					//return Redirect::to('purchaseRequest/view');
					return Redirect::to('janisawesome');
					Session::put('imgsuccess','Files uploaded.');
					


				} 
				else
				{

					
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

					 Session::put('imgsuccess','Files uploaded.');
					


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

				if (Session::get('imgerror')&&Input::hasfile('file'))
				{
					$failedpurchasecount=Purchase::where('id', $purchase->id)->count();
					if ($failedpurchasecount!=0){
					$failedpurchase=Purchase::find($purchase->id);
					$failedpurchase->delete();}

						
						Session::forget('imgsuccess');
						//Image Error Return
						
						
						$task_details= TaskDetails::where('doc_id', $document->id)->delete();
						$document->delete();
						$message = "Failed to create purchase request.";
				
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



						
				} 

				return Redirect::back()->withInput();

			} 
			$numLoop++;
		}

	} // End of looping

}
