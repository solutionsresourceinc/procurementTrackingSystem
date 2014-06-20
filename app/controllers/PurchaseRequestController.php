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
			return 'Failed to create purchase request! <br>' . $purchase;
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
}