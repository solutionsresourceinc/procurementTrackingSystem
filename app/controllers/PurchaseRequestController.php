<?php

class PurchaseRequestController extends Controller {

	public function create()
	{
		$office = Office::all();
		return View::make('purchaseRequest.purchaseRequest_create')->with('office',$office);
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
			$notice = "Purchase request created successfullly! ";  

			Session::put('notice', $notice);
			$office = Office::all();      
			return View::make('purchaseRequest.purchaseRequest_create')
				->with('office', $office);
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
				->with('purchase',$purchase);
		//return $purchase;
	}

	public function edit_submit()
	{
		$edit = new Purchase;
		$edit->projectPurpose = Input::get('projectPurpose');
		$edit->sourceOfFund = Input::get('sourceOfFund');
		$edit->amount = Input::get( 'amount' );
		$edit->office = Input::get( 'office' );
		$edit->requisitioner = Input::get( 'requisitioner' );
		$edit->modeOfProcurement = Input::get( 'modeOfProcurement' );
		$edit->ControlNo = Input::get( 'ControlNo' );
		$edit->status = "Pending";

		if($edit->save())
		{
			$notice = "Purchase request has been edited! ";  

			Session::put('notice', $notice);
			$office = Office::all();      
			return Redirect::back();
		}
		else
		{
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

			return Redirect::back()->withInput();
		}
	}

}