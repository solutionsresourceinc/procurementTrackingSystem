<?php

class DesignationController extends BaseController {

	protected $designation;

	public function __construct(Designation $designation)
	{
		
		$this->designation = $designation;
	}

	/**
	 * Display an arranged listing of all designations in the database.
	 *
	 * @return Response
	 */
	public function index()
	{
		$designations=$this->designation->orderBy('designation','asc')->paginate(50);
		return View::make('designation', compact('designations'));
	}

	/**
	 * Store a newly created designation in the database.
	 *
	 * @return Response
	 */
	public function store()
	{
		$checkdesignationname=0;

		$designations = new Designation;
		$designations= DB::table('designation')->get();

		foreach ($designations as $dsgn){
			if ($dsgn->designation==Input::get( 'designationName' )){ $checkdesignationname=1; }
		}
		if ($checkdesignationname != 0){
			return Redirect::back()->withInput()->with('duplicate-error', 'Designation is already exisiting in the list.');
		}

		/*$rules = ['designationName' => 'required|alpha_spaces|max:100'];
		$validation = Validator::make(Input::all(), $rules);
		$checker = 0;

		if($validation->fails()){
			//return Redirect::back()->withInput()->withErrors($validation->messages())->with('invalid', 'Designation entry not created.');
			$checker = 1;
			return 'failed';
		}*/

		$input=Input::all();
		
		if(!$this->designation->fill($input)->isValid()) 
		{
			return Redirect::back()->withInput()->withErrors($this->designation->errors)->with('invalid', 'Designation entry not created.');
		}
		else
		{
			$this->designation->save();
			return Redirect::to('/designation')->with('success', 'Successfully created a designation');
		}
	}

	/**
	 * Update the specified designation in the database.
	 *
	 * @return Response
	 */
	public function update($id)
	{
		$rules = ['dsgntn-name' => 'required|alpha_spaces|max:100'];
		$validation = Validator::make(Input::all(), $rules);

		if($validation->fails()){
			return Redirect::back()->withInput()->withErrors($validation->messages());
		}
		
		$updateDesignation = Designation::find($id);
		$updateDesignation->designation = Input::get('dsgntn-name');
		$updateDesignation->save();
		return Redirect::to('/designation')->with('success','Successfully updated office name');
	}

	/**
	 * Remove a specific office in the database.
	 *
	 * @return Response
	 */
	public function deleteDesignation($id)
	{
		$deletedesignation = Designation::find($id);
		$deletedesignation->delete();
		return Redirect::to('/designation')->with('success','Successfully deleted');
	}
}