<?php

class PurchaseRequestController extends Controller {

	public function create()
	{
		$office = Office::all();
		return View::make('purchaseRequest.purchaseRequest_create')->with('office',$office);
	}

	public function create_submit()
	{
		
	}







}