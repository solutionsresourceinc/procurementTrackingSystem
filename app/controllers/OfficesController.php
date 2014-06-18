<?php

class OfficesController extends BaseController {
	public function index()
	{
		return View::make('offices');
	}
}