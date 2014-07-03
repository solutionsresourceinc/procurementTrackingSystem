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
		

		$purchase_save = $purchase->save();

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
                Session::put('imgerror','Invalid file.');

            }

        }

         Session::put('imgsuccess','Files uploaded.');
if (Session::get('imgerror'))
	       Session::forget('imgsuccess'); 


		if($purchase_save)
		{
			$document->pr_id = $purchase->id;
			$document->work_id = Input::get('modeOfProcurement');
			$document_save = $document->save();

			if($document_save)
			{
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
					$message->from('procurementTrackingSystem@tarlac.com', 'PTS Tarlac');
					$message->to($email, $fname)->subject('Tarlac Procurement Tracking System');
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

	public function vieweach($id)
	{
		$purchase = Purchase::find($id);
		$wfName = DB::table('document')->where('pr_id',$id)->first();
		
		return View::make('purchaseRequest.purchaseRequest_view')
		->with('purchase', $purchase)->with('wfName',$wfName);
		//return $purchase;
	}

	public function viewAll()
	{
		return View::make('purchaseRequest.purchaseRequest_all');
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
		$purchase->amount = Input::get( 'amount' );
		$purchase->office = Input::get( 'office' );
		$purchase->requisitioner = Input::get( 'requisitioner' );
		$purchase->dateRequested = Input::get( 'dateRequested' );
		$purchase->controlNo = Input::get('controlNo');
		$purchase->status = 'New';
		

//Image Module 

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
                Session::put('imgerror','Invalid file.');
            }

        }

         Session::put('imgsuccess','Files uploaded.');
if(Session::get('imgerror'))
Session::forget('imgsuccess');
          //End Image Module

		$purchase_save = $purchase->save();



		if($purchase_save)
		{
			$document->pr_id = $purchase->id;
			$document->work_id = Input::get('modeOfProcurement');
			$document_save = $document->save();

			if($document_save)
			{
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


}