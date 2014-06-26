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
	
	$purchase->ControlNo = Input::get('ControlNo');

		$purchase->status = 'Pending';

		if($purchase->save())
		{
			

			$pr_id= Session::get('pr_id');
			if (Session::get('doc_id')){
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
	
			// Inserting Error Message To a Session
			Session::put('m1', $m1 );
			Session::put('m2', $m2 );
			Session::put('m3', $m3 );
			Session::put('m4', $m4 );
			Session::put('m5', $m5 );
			Session::put('m6', $m6 );

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

	public function edit_submit()
	{
		$id = Input::get('id');
	$purchase = Purchase::find($id);


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
if (Session::get('doc_id')){

$doc_id =Session::get('doc_id');

DB::table('attachments')
            ->where('doc_id', $doc_id)
            ->update(array( 'saved' => 1));
DB::table('attachments')->where('saved', '=', 0)->delete();
DB::table('document')->where('pr_id', '=',$pr_id )->where('id','!=',$doc_id)->delete();
Session::forget('doc_id');

}
$notice = "Purchase request edited successfullly! ";  


			Session::put('notice', $notice);
			$office = Office::all();      
			$users = User::all();
			return Redirect::back()->with('office', $office)
				->with('users',$users);


		}
		else
		{
			//return 'Failed to create purchase request! <br>' . $purchase;
			
			// Set Main Error
			$message = "Failed to edit purchase Request";
			Session::put('main_error', $message );

			// Get Other Error Messages
			$m1 = $purchase->validationErrors->first('projectPurpose');
			$m2 = $purchase->validationErrors->first('sourceOfFund');
			$m3 = $purchase->validationErrors->first('amount');
			$m4 = $purchase->validationErrors->first('office');
			$m5 = $purchase->validationErrors->first('requisitioner');
			$m6 = $purchase->validationErrors->first('modeOfProcurement');
		

			// Inserting Error Message To a Session
			Session::put('m1', $m1 );
			Session::put('m2', $m2 );
			Session::put('m3', $m3 );
			Session::put('m4', $m4 );
			Session::put('m5', $m5 );
			Session::put('m6', $m6 );

			return Redirect::back()->withInput();
		}
	}

}