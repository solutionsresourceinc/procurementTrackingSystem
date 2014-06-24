<?php

class PurchaseRequestController extends Controller {

	public function create()
	{
		$office = Office::all();
		$users = User::all();
		return View::make('purchaseRequest.purchaseRequest_create')->with('office',$office)->with('users',$users);
	}

	public function create_submit()
	{
		$purchase = new Purchase;
		$purchase->projectPurpose = Input::get( 'projectPurpose' );
		$purchase->sourceOfFund = Input::get( 'sourceOfFund' );
		$purchase->amount = Input::get( 'amount' );
		$purchase->office = Input::get( 'office' );
		$purchase->requisitioner = Input::get( 'requisitioner' );
		$purchase->modeOfProcurement = Input::get( 'modeOfProcurement' );
		$purchase->ControlNo = Input::get( 'ControlNo' );
		$purchase->status = 'Pending';

		if($purchase->save())
		{
			$pr_id= Session::get('pr_id');

$doc_id =Session::get('doc_id');
$notice = "Purchase request created successfullly! ";  

DB::table('attachments')
            ->where('doc_id', $doc_id)
            ->update(array( 'saved' => 1));
DB::table('attachments')->where('saved', '=', 0)->delete();
DB::table('document')->where('pr_id', '=',$pr_id )->where('id','!=',$doc_id)->delete();



			Session::put('notice', $notice);
			$office = Office::all();      
			$users = User::all();
			return View::make('purchaseRequest.purchaseRequest_create')
				->with('office', $office)
				->with('users',$users);


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
			$m6 = $purchase->validationErrors->first('modeOfProcurement');
			$m7 = $purchase->validationErrors->first('ControlNo');

			// Inserting Error Message To a Session
			Session::put('m1', $m1 );
			Session::put('m2', $m2 );
			Session::put('m3', $m3 );
			Session::put('m4', $m4 );
			Session::put('m5', $m5 );
			Session::put('m6', $m6 );
			Session::put('m7', $m7 );

			return Redirect::back()->withInput(Input::except('password'));
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

	public function edit_submit()
	{
		$id = Input::get('purch_id');

		$edit = new Purchase;
		$edit->projectPurpose = Input::get('projectPurpose');
		$edit->sourceOfFund = Input::get('sourceOfFund');
		$edit->amount = Input::get( 'amount' );
		$edit->office = Input::get( 'office' );
		$edit->requisitioner = Input::get( 'requisitioner' );
		$edit->modeOfProcurement = Input::get( 'modeOfProcurement' );
		$edit->ControlNo = Input::get( 'ControlNo' );
		$edit->status = "Pending";

		$e_val = true;

		if(!ctype_alnum(str_replace(' ','',$edit->projectPurpose)))
			$e_val = false;
		if(!ctype_alnum(str_replace(' ','',$edit->sourceOfFund)))
			$e_val = false;
		if(!preg_match("/^[0-9,.]+$/", $edit->amount))
			$e_val = false;
		if(!is_numeric($edit->ControlNo))
			$e_val = false;

		if($e_val)
		{
			//Session::put('notice', $notice);
			//$office = Office::all();      
			//return Redirect::back();

			$vari = Purchase::find($id);
			$vari->projectPurpose = Input::get('projectPurpose');
			$vari->sourceOfFund = Input::get('sourceOfFund');
			$vari->amount = Input::get( 'amount' );
			$vari->office = Input::get( 'office' );
			$vari->requisitioner = Input::get( 'requisitioner' );
			$vari->modeOfProcurement = Input::get( 'modeOfProcurement' );
			$vari->ControlNo = Input::get( 'ControlNo' );
			$vari->status = 'Pending';
			$vari->save();

			DB::table('purchase_request')
            ->where('id', $id)
            ->update(array( 'projectPurpose' => $edit->projectPurpose,
            				'sourceOfFund' => $edit->sourceOfFund, 
            				'amount' => $edit->amount, 
            				'office' => $edit->office, 
            				'requisitioner' => $edit->requisitioner,
            				'modeOfProcurement' => $edit->modeOfProcurement, 
            				'ControlNo' => $edit->ControlNo));

           	$notice = "Successfully edited Purchase Request.";
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
            return Redirect::back()->with( 'notice', $notice );
		}
		else
		{
			$edit->save();
			//return 'Failed to create purchase request! <br>' . $purchase;
			// Set Main Error
			$message = "Failed to edit purchase Request";
			Session::put('main_error', $message );

			// Get Other Error Messages
			$m1 = $edit->validationErrors->first('projectPurpose');
			$m2 = $edit->validationErrors->first('sourceOfFund');
			$m3 = $edit->validationErrors->first('amount');
			$m4 = $edit->validationErrors->first('modeOfProcurement');
			$m5 = $edit->validationErrors->first('ControlNo');

			// Inserting Error Message To a Session
			Session::put('m1', $m1 );
			Session::put('m2', $m2 );
			Session::put('m3', $m3 );
			Session::put('m4', $m4 );
			Session::put('m5', $m5 );

			$office = Office::all();   
			return Redirect::back()->withInput();
		}
	}

}