<?php

class PurchaseRequestController extends Controller {

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

		$purchase = new Purchase;
		$document = new Document;
		$purchase->projectPurpose = Input::get( 'projectPurpose' );
		$purchase->sourceOfFund = Input::get( 'sourceOfFund' );
		$purchase->amount = Input::get( 'amount' );
		$purchase->office = Input::get( 'office' );
		$purchase->requisitioner = Input::get( 'requisitioner' );
		$purchase->dateRequested = Input::get( 'dateRequested' );
		$purchase->controlNo = Input::get('controlNo');
		$purchase->status = 'New';

		// Set due date;
		$workflow_id = Input::get('modeOfProcurement');
		$workflow = Workflow::find($workflow_id);
		$addToDate = $workflow->totalDays;

		date_default_timezone_set("Asia/Manila");

		$dueDate = date('Y-m-d H:i:s', strtotime("+$addToDate days" ));
		$purchase->dueDate = $dueDate;


		// Set creator id
		$user_id = Auth::user()->id;
		$purchase->created_by = $user_id;

		$purchase_save = $purchase->save();

		if($purchase_save)
		{
			$document->pr_id = $purchase->id;
			$document->work_id = Input::get('modeOfProcurement');
			$document_save = $document->save();

			if($document_save)
			{

$doc_id= $document->id;
$workflow=Workflow::find($document->work_id);
$section=Section::where('workflow_id',$document->work_id)->orderBy('section_order_id', 'ASC')->get();
$firstnew=0;


	$task=Task::where('wf_id', $document->work_id)->orderBy('section_id', 'ASC')->orderBy('order_id', 'ASC')->get();


	foreach ($task as $tasks) {


 $taskd= New TaskDetails;
 $taskd->task_id=$tasks->id;


	if($firstnew==0)
	 	$taskd->status="New";
 	else
 		$taskd->status="Pending";
		$firstnew=1;
		$taskd->doc_id= $document->id;
		$taskd->save();
	}





$userx= User::get();
foreach($userx as $userv){
$count= new Count;
$count->user_id= $userv->id;
$count->doc_id= $doc_id;
$count->save();
}


//Image Upload


foreach(Input::file('file') as $file){
            $rules = array(
                'file' => 'required|mimes:png,gif,jpeg|max:900000000'
                );
            $validator = \Validator::make(array('file'=> $file), $rules);
            $destine=public_path()."/uploads";
            if($validator->passes()){
                $ext = $file->guessClientExtension(); // (Based on mime type)
                $ext = $file->getClientOriginalExtension(); // (Based on filename)
                $filename = $file->getClientOriginalName();
             


$archivo = value(function() use ($file){
        $filename = str_random(10) . '.' . $file->getClientOriginalExtension();
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
        while ( $newheight> 525) {
        	$newheight=$newheight*0.8;
        	$newwidth=$newwidth*0.8;
}

    
$thumb = imagecreatetruecolor($newwidth, $newheight);
if ($ext=="jpg"||$ext=="jpeg")
        $source = imagecreatefromjpeg($upload_image);
elseif ($ext=="png")
 $source = imagecreatefrompng($upload_image);
else
 $source = imagecreatefromgif($upload_image);
        // RESIZE 
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // MAKE NEW FILES
if ($ext=="jpg"||$ext=="jpeg")
 imagejpeg($thumb, $thumbnail, 100);
elseif ($ext=="png")
 imagepng($thumb, $thumbnail, 9);
else
 imagegif($thumb, $thumbnail, 100);
       
unlink($actual);
        // FILE RENAMES
        rename($thumbnail, $actual);


           
             }else{

                //Does not pass validation

                $errors = $validator->errors();
                Session::put('imgerror','Invalid file.');
            }

        }

          Session::put('imgsuccess','Files uploaded.');
if (Session::get('imgerror')){
Session::forget('imgerror');

}





		//Image Upload



				$pr_id= Session::get('pr_id');

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

				// START MAILER BY JAN SARMIENTO AWESOME
				$sendee = DB::table('users')->where('id',$purchase->requisitioner)->first();
				$email = $sendee->email;
				$fname = $sendee->firstname;

				$data = [ 'id' => $purchase->id ];
				Mail::send('emails.template', $data, function($message) use($email, $fname)
				{
					$message->from('procurementTrackingSystem@tarlac.com', 'Procurement Tracking System Tarlac');
					$message->to($email, $fname)->subject('Tarlac Procurement Tracking Systems');
				}); 
				// END MAILER BY JAN SARMIENTO AWESOME
				
				$notice = "Purchase request created successfully. ";										  
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
			$m1 = $purchase->validationErrors->first('projectPurpose');
			$m2 = $purchase->validationErrors->first('sourceOfFund');
			$m3 = $purchase->validationErrors->first('amount');
			$m4 = $purchase->validationErrors->first('office');
			$m5 = $purchase->validationErrors->first('requisitioner');
			$m7 = $purchase->validationErrors->first('dateRequested');
			// Inserting Error Message To a Session
			Session::put('m1', $m1 );
			Session::put('m2', $m2 );
			Session::put('m3', $m3 );
			Session::put('m4', $m4 );
			Session::put('m5', $m5 );
			Session::put('m7', $m7 );

			if(Input::get('modeOfProcurement') == "")
			{
				Session::put('m6', 'required' );
			}

			return Redirect::back()->withInput();


			}
		}
		else
		{
			//return 'Failed to create purchase request! <br>' . $purchase;
			
			// Set Main Error
			$message = "Failed to create purchase request.";
			Session::put('main_error', $message );

			// Get Other Error Messages
			$m1 = $purchase->validationErrors->first('projectPurpose');
			$m2 = $purchase->validationErrors->first('sourceOfFund');
			$m3 = $purchase->validationErrors->first('amount');
			$m4 = $purchase->validationErrors->first('office');
			$m5 = $purchase->validationErrors->first('requisitioner');
			$m7 = $purchase->validationErrors->first('dateRequested');
			// Inserting Error Message To a Session
			Session::put('m1', $m1 );
			Session::put('m2', $m2 );
			Session::put('m3', $m3 );
			Session::put('m4', $m4 );
			Session::put('m5', $m5 );
			Session::put('m7', $m7 );

			if(Input::get('modeOfProcurement') == "")
			{
				Session::put('m6', 'required' );
			}

			return Redirect::back()->withInput();
		}
	}

	public function edit()
	{
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
		//return $purchase;
	}

	
	public function viewClosed()
	{
		return View::make('purchaseRequest.purchaseRequest_closed');
	}

	public function viewOverdue()
	{
		return View::make('purchaseRequest.purchaseRequest_overdue');
	}

	public function edit_submit()
	{
		
		$id = Input::get('id');
		$purchase = Purchase::find(Input::get('id'));
		$document = Document::where('pr_id', Input::get('id'))->first();

		$purchase->projectPurpose = Input::get( 'projectPurpose' );
		$purchase->sourceOfFund = Input::get( 'sourceOfFund' );
		//$purchase->amount = Input::get( 'amount' );
		$purchase->office = Input::get( 'office' );
		$purchase->requisitioner = Input::get( 'requisitioner' );
		$purchase->dateRequested = Input::get( 'dateRequested' );
		$purchase->controlNo = Input::get('controlNo');
		$purchase->status = 'New';
		

		$purchase_save = $purchase->save();

		if($purchase_save)
		{
			$document->pr_id = $purchase->id;
			//$document->work_id = Input::get('modeOfProcurement');
			$document_save = $document->save();

			if($document_save)
			{


//Image Upload




$doc_id= $document->id;

foreach(Input::file('file') as $file){
            $rules = array(
                'file' => 'required|mimes:png,gif,jpeg|max:900000000'
                );
            $validator = \Validator::make(array('file'=> $file), $rules);
            $destine=public_path()."/uploads";
            if($validator->passes()){
                $ext = $file->guessClientExtension(); // (Based on mime type)
                $ext = $file->getClientOriginalExtension(); // (Based on filename)
                $filename = $file->getClientOriginalName();
             


$archivo = value(function() use ($file){
        $filename = str_random(10) . '.' . $file->getClientOriginalExtension();
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
        while ( $newheight> 525) {
        	$newheight=$newheight*0.8;
        	$newwidth=$newwidth*0.8;
}

    
$thumb = imagecreatetruecolor($newwidth, $newheight);
if ($ext=="jpg"||$ext=="jpeg")
        $source = imagecreatefromjpeg($upload_image);
elseif ($ext=="png")
 $source = imagecreatefrompng($upload_image);
else
 $source = imagecreatefromgif($upload_image);
        // RESIZE 
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // MAKE NEW FILES
if ($ext=="jpg"||$ext=="jpeg")
 imagejpeg($thumb, $thumbnail, 100);
elseif ($ext=="png")
 imagepng($thumb, $thumbnail, 9);
else
 imagegif($thumb, $thumbnail, 100);
       
unlink($actual);
        // FILE RENAMES
        rename($thumbnail, $actual);


           
             }else{

                //Does not pass validation

                $errors = $validator->errors();
                Session::put('imgerror','Invalid file.');
            }

        }

          Session::put('imgsuccess','Files uploaded.');
if (Session::get('imgerror')){
Session::forget('imgerror');

}





		//Image Upload

				$pr_id= Session::get('pr_id');

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

				$notice = "Purchase request saved successfully. ";										  


				Session::put('notice', $notice);
				$office = Office::all();
				$users = User::all();
				$workflow = Workflow::all();

				return Redirect::to("purchaseRequest/vieweach/$id");

			}
			else
			{
		
				
			}
		}
		else
		{
			//return 'Failed to create purchase request! <br>' . $purchase;
			
			// Set Main Error
			$message = "Failed to save purchase request.";
			Session::put('main_error', $message );

			// Get Other Error Messages
			$m1 = $purchase->validationErrors->first('projectPurpose');
			$m2 = $purchase->validationErrors->first('sourceOfFund');
			$m3 = $purchase->validationErrors->first('amount');
			$m4 = $purchase->validationErrors->first('office');
			$m5 = $purchase->validationErrors->first('requisitioner');
			$m7 = $purchase->validationErrors->first('dateRequested');
			// Inserting Error Message To a Session
			Session::put('m1', $m1 );
			Session::put('m2', $m2 );
			Session::put('m3', $m3 );
			Session::put('m4', $m4 );
			Session::put('m5', $m5 );
			Session::put('m7', $m7 );

			if(Input::get('modeOfProcurement') == "")
			{
				Session::put('m6', 'required' );
			}

			return Redirect::back()->withInput();
		}
	}




public function addimage(){
foreach(Input::file('file') as $file){
            $rules = array(
                'file' => 'required|mimes:png,gif,jpeg|max:900000000'
                );
            $validator = \Validator::make(array('file'=> $file), $rules);
            $destine=public_path()."/uploads";
            if($validator->passes()){
                $ext = $file->guessClientExtension(); // (Based on mime type)
                $ext = $file->getClientOriginalExtension(); // (Based on filename)
                $filename = $file->getClientOriginalName();
                $doc_id=input::get('doc_id');


$archivo = value(function() use ($file){
        $filename = str_random(10) . '.' . $file->getClientOriginalExtension();
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
        while ( $newheight> 525) {
        	$newheight=$newheight*0.8;
        	$newwidth=$newwidth*0.8;
}

    
$thumb = imagecreatetruecolor($newwidth, $newheight);
if ($ext=="jpg"||$ext=="jpeg")
        $source = imagecreatefromjpeg($upload_image);
elseif ($ext=="png")
 $source = imagecreatefrompng($upload_image);
else
 $source = imagecreatefromgif($upload_image);
        // RESIZE 
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        // MAKE NEW FILES
if ($ext=="jpg"||$ext=="jpeg")
 imagejpeg($thumb, $thumbnail, 100);
elseif ($ext=="png")
 imagepng($thumb, $thumbnail, 9);
else
 imagegif($thumb, $thumbnail, 100);
       
unlink($actual);
        // FILE RENAMES
        rename($thumbnail, $actual);


           
             }else{

                //Does not pass validation

                $errors = $validator->errors();
                return Redirect::back()->with('imgerror','Invalid file.');
            }

        }

          return Redirect::back()->with('imgsuccess','Files uploaded.');

}

public function changeForm($id)
{
	//return "good $id";
	$reason = Input::get('hide_reason');

	$purchase = Purchase::find($id);
	$purchase->status = "Cancelled";
	$purchase->reason = $reason;
	$purchase->save();
	TaskDetails::where('doc_id', '=', $id)->delete();
	Session::put('notice', 'Purchase request has been cancelled.' );
	return Redirect::to("purchaseRequest/view");


}


public function checklistedit(){


$taskdetails_id= Input::get('taskdetails_id');
$assignee=Input::get('assignee');
$dateFinished=Input::get('dateFinished');
$daysOfAction=Input::get('daysOfAction');
$remarks=Input::get('remarks')." ";
$remarkchange=0;
$check=0;
if($remarks==" ")
	$remarkchange=1;

if(ctype_alpha(str_replace(array(' ', '-', '.'),'',$remarks)))
         {
         	$check=$check+1;
         }
if(($remarkchange==1)||ctype_alpha(str_replace(array(' ', '-', '.'),'',$assignee)))
         {
         	$check=$check+1;
         }
if(ctype_digit($daysOfAction))
		{
		  	$check=$check+1;
         }

if ($check==3){

Session::put('successchecklist','Saved.');

$taskd= TaskDetails::find($taskdetails_id);
$taskd->status="Done";
$taskd->daysOfAction=$daysOfAction;
$taskd->dateFinished=$dateFinished;
$taskd->assignee=$assignee;
if($remarkchange==0)
$taskd->remarks=$remarks;
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


}
else
Session::put('errorchecklist','Invalid input.');
return Redirect::back();

}


}
