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
	{/*
		$checkdesignationname=0;

		$designations = new Designation;
		$designations= DB::table('designation')->get();

		foreach ($designations as $dsgn){
			if ($dsgn->designation==Input::get( 'designationName' )){ $checkdesignationname=1; }
		}
		if ($checkdesignationname != 0){
			return Redirect::back()->withInput()->with('duplicate-error', 'Designation is already exisiting in the list.');
		}

		$rules = ['designationName' => 'required|alpha_spaces|max:100'];
		$validation = Validator::make(Input::all(), $rules);
		$checker = 0;

		if($validation->fails()){
			//return Redirect::back()->withInput()->withErrors($validation->messages())->with('invalid', 'Designation entry not created.');
			$checker = 1;
			return 'failed';
		}

		$input=Input::all();
		
		if(!$this->designation->fill($input)->isValid()) 
		{
			return Redirect::back()->withInput()->withErrors($this->designation->errors)->with('invalid', 'Designation entry not created.');
		}
		else
		{
			$this->designation->save();
			return Redirect::to('/designation')->with('success', 'Successfully created a designation');
		}*/

		$designation = new Designation;
		$designation->designation = Input::get( 'designationName' );
		if($designation->save())
		{
			return Redirect::to('/designation')->with('success', 'Successfully created a designation');
		}
		else
		{
			$m1 = $designation->validationErrors->first('designation');
			if($m1 != 'The designation has already been taken.')
			{
				$message = "Invalid input for designation name.";
				Session::put('main_error', $message );
			}

			
			
			return Redirect::back()->withInput()->withErrors($this->designation->errors)->with('invalid', $m1);
		}

	}

	/**
	 * Update the specified designation in the database.
	 *
	 * @return Response
	 */
	public function update($id)
	{
		$rules = ['dsgntn-name' => 'required|alpha_spaces|max:100|allNum'];
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
		$deleteAsignee = UserHasDesignation::where('designation_id', '=', $id);
		$deleteAsignee->delete();
	
		$deletedesignation = Designation::find($id);
		$deletedesignation->delete();

		return Redirect::to('/designation')->with('success','Successfully deleted');
	}

	public function members($id)
	{
		$selected_users = DB::select("select * from users join user_has_designation on users.id = user_has_designation.users_id where user_has_designation.designation_id = $id");
		$notselected_users = DB::select("select * from users where id not in ( select users_id from user_has_designation where designation_id = $id )");

		//return $selected_users;
		return View::make('designation_members')
			->with('designation_id',$id)
			->with('notselected_users',$notselected_users)
			->with('selected_users',$selected_users);
	}

	public function save_members()
	{
		$members_selected = Input::get('members_selected');
		$members = explode(",", $members_selected);


		// Delete all user in user_has_designation table with id = x;
		$designation_id = Input::get('designation_id');
		UserHasDesignation::where('designation_id', '=', $designation_id )->delete();
		
		//get all the values in select and convert it to array
		

		// saving to database
		foreach ($members as $key) 
		{
			if($key != 0)
			{
				$uhd = new UserHasDesignation;
				$uhd->users_id = $key;
				$uhd->designation_id = $designation_id;
				$uhd->save();
			}
			
		}

		$designation_name = Designation::find($designation_id);
		$name = $designation_name->designation;
		$message = "Successfully updated the members in $name.";
		Session::put('success_members', $message );
		return Redirect::to('designation');

	}
	public function assign()
	{
		$id= Input::get('task_id');
		$assignd = Task::find($id);
		$assignd->d_id = Input::get('designa');
		$assignd->save();
				return Redirect::back()->with('success','Successfully deleted');
	}
}