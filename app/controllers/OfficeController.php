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

	public function update($id)
	{
		$rules = ['ofcname' => 'required'];
		$validation = Validator::make(Input::all(), $rules);

		if($validation->fails()){
			return Redirect::back();
		}
		
		$updateOffice = Office::find($id);
		$updateOffice->officeName = Input::get('ofcname');
		$updateOffice->save();
		return Redirect::to('/offices')->with('success','Successfully deleted');
	}

	public function deleteOffice($id)
	{
		$deleteoffice = Office::find($id);
		$deleteoffice->delete();
		return Redirect::to('/offices')->with('success','Successfully deleted');
	}
}