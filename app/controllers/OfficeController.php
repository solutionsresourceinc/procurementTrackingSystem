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
		$checkofficename=0;

		$offices = new Office;
		$offices = DB::table('offices')->get();

		foreach ($offices as $office){
			if ($office->officeName==Input::get( 'officeName' )){ $checkofficename=1; }
		}
		if ($checkofficename != 0){
			return Redirect::back()->withInput()->with('duplicate-error', 'Office is already exisiting in the list.');
		}

		$rules = ['officeName' => 'required|alpha_spaces|max:100|allNum'];
		$validation = Validator::make(Input::all(), $rules);

		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages())->with('invalid', 'Office entry not created.');
		}

		$input=Input::all();

		if(!$this->office->fill($input)->isValid()) 
		{
			return Redirect::back()->withInput()->withErrors($this->office->errors)->with('invalid', 'Office entry not created.');
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
		$rules = ['ofcname' => 'required|alpha_spaces|max:100|allNum|unique:offices,officeName'];
		$messages = array(
		    'required' => 'The :attribute field is required.',
		    'unique' => 'The office name has already been taken.',
		    'alpha_spaces' => 'Office name should contain spaces or alpha characters.',
		    'max' => 'the maximum lenght for office name is 100',
		);
		$validation = Validator::make(Input::all(), $rules,$messages);

		if($validation->fails())
		{
			//return Redirect::back()->withInput()->withErrors($validation->messages());
			$updateOffice = Office::find($id);
			$message = $validation->messages()->first();
			$data = array(
				"fragments" => array(
					"#other_message" => "<div class='alert alert-danger' id='other_message'>$message.</div>",
					"#message" =>"<div id='message'> </div>"
				),
				"inner-fragments" => array(
					"#display_$id" =>"<span id='insert_$id' class='current-text mode1'> $updateOffice->officeName  </span>"
				),
			);


			return Response::json($data);
		}
		else
		{
			$updateOffice = Office::find($id);
			$oldOfficeName = $updateOffice->officeName;
			$updateOffice->officeName = Input::get('ofcname');
			$updateOffice->save();
			$data = array(
				"fragments" => array(
					"#other_message" => "<div class='alert alert-success' id='other_message'>Changed $oldOfficeName to $updateOffice->officeName. </div>",
					"#message" =>"<div id='message'> </div>"
				),
				"inner-fragments" => array(
					"#display" =>"<span class='current-text mode1'> $updateOffice->officeName  </span>"
				),

			);
			return Response::json($data);
		}
		
		
		//return Redirect::to('/offices')->with('success','Successfully updated office name');
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