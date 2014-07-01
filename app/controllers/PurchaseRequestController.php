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

				$notice = "Purchase request created successfullly! ";										  


				Session::put('notice', $notice);
				$office = Office::all();
				$users = User::all();
				$workflow = Workflow::all();
return Redirect::to('purchaseRequest/view');
			}
			else
			{
				$purchase->delete();
				
			}
		}
		else
		{
			//return 'Failed to create purchase request! <br>' . $purchase;
			
			// Set Main Error
			$message = "Failed to create purchase Request";
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
		return View::make('purchaseRequest.purchaseRequest_view')
		->with('purchase', $purchase);
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

				$notice = "Purchase request saved successfullly! ";										  


				Session::put('notice', $notice);
				$office = Office::all();
				$users = User::all();
				$workflow = Workflow::all();

				return Redirect::to('purchaseRequest/view');

			}
			else
			{
		
				
			}
		}
		else
		{
			//return 'Failed to create purchase request! <br>' . $purchase;
			
			// Set Main Error
			$message = "Failed to save purchase Request";
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
                'file' => 'required|mimes:png,gif,jpeg|max:20000000'
                );
            $validator = \Validator::make(array('file'=> $file), $rules);
            $destine=public_path()."/uploads";
            if($validator->passes()){
                $ext = $file->guessClientExtension(); // (Based on mime type)
                //$ext = $file->getClientOriginalExtension(); // (Based on filename)
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
  
                
             }else{

                //Does not pass validation

                $errors = $validator->errors();
                return Redirect::back()->with('imgerror','Invalid upload.');
            }

        }

          return Redirect::back()->with('imgsuccess','Files uploaded.');



}


}