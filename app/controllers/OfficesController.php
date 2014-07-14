<?php

/*
	CODE REVIEW:
		- remove this file
*/

class OfficesController extends BaseController {
	public function index()
	{
		return View::make('offices');
	}
}