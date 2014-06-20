<?php

class OfficeController extends BaseController {

	protected $office;
	
	public function __construct(Office $office)
	{
		
		$this->office = $office;
	}

	/**
	 * Display an arranged listing of all offices in the database.
	 *
	 * @return Response
	 */
	public function index()
	{
		$offices=$this->office->orderBy('officeName','asc')->paginate(50);
		return View::make('offices', compact('offices'));
	}

	/**
	 * Store a newly created office in the database.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules = ['officeName' => 'required|alpha_spaces'];
		$validation = Validator::make(Input::all(), $rules);

		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		}

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

	/**
	 * Update the specified office in the database.
	 *
	 * @return Response
	 */
	public function update($id)
	{
		$rules = ['ofcname' => 'required|alpha_spaces'];
		$validation = Validator::make(Input::all(), $rules);

		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		}
		
		$updateOffice = Office::find($id);
		$updateOffice->officeName = Input::get('ofcname');
		$updateOffice->save();
		return Redirect::to('/offices')->with('success','Successfully deleted');
	}

	/**
	 * Remove a specific office in the database.
	 *
	 * @return Response
	 */
	public function deleteOffice($id)
	{
		$deleteoffice = Office::find($id);
		$deleteoffice->delete();
		return Redirect::to('/offices')->with('success','Successfully deleted');
	}
}