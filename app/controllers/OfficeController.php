<?php

class OfficeController extends BaseController {

	protected $office;
	
	public function __construct(Office $office)
	{
		
		$this->office = $office;
	}

	public function index()
	{
		//$offices = $this->office->all();
		$offices=$this->office->orderBy('officeName','asc')->paginate(50);
		return View::make('offices', compact('offices'));
	}

	public function store()
	{
		$input=Input::all();

		if(!$this->office->fill($input)->isValid()) 
		{
			return Redirect::back()->withInput()->withErrors($this->office->errors);
		}
		else
		{
			$this->office->save();
			return Redirect::to('/offices')->with('success', 'Successfully created an office');
		}
	}

	public function deleteOffice($id)
	{
		//$offices = $this->office->all();
		/*$offices=$this->office->orderBy('officeName','asc')->paginate(50);
		return View::make('offices', compact('offices'));*/
		$deleteoffice = Office::find($id);
		//dd($editOpcr);
		$deleteoffice->delete();
		return Redirect::to('/offices')->with('success','Successfully deleted');
	}
}