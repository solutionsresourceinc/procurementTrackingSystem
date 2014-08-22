<?php

class DesignationController extends BaseController {

	protected $designation;

	public function __construct(Designation $designation)
	{
		$this->designation = $designation;
	}

	public function index()
	{
		$designations=$this->designation->orderBy('designation','asc')->paginate(50);
		return View::make('designation', compact('designations'));
	}

	public function store()
	{
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

			return Redirect::back()->withInput()->withErrors($this->designation->errors)->with('invalid', $message);
		}

	}

	public function update($id)
	{
		$rules = ['dsgntn-name' => 'required|alpha_spaces|max:100|allNum|unique:designation,designation'];
		$messages = array(
		    'required' => 'The :attribute field is required.',
		    'unique' => 'The designation name has already been taken.',
		    'alpha_spaces' => 'Designation name should contain spaces or alpha characters.',
		    'max' => 'the maximum lenght for office name is 100',
		);
		$validation = Validator::make(Input::all(), $rules,$messages);

		if($validation->fails())
		{
			$updateDesignation = Designation::find($id);
			$message = $validation->messages()->first();

			if($updateDesignation->designation == Input::get('dsgntn-name'))
			{
				$updateDesignation = Designation::find($id);
				$oldDesignation = $updateDesignation->designation;
				$updateDesignation->designation = Input::get('dsgntn-name');
				$updateDesignation->save();
				$data = array(
					"inner-fragments" => array(
						"#display" =>"<span class='current-text mode1'> $updateDesignation->designation  </span>"
					),

				);
				return Response::json($data);
			}
			else
			{
				$data = array(
					"fragments" => array(
						"#other_message" => "<div class='alert alert-danger' id='other_message'>$message</div>",
						"#message" =>"<div id='message'> </div>"
					),
					"inner-fragments" => array(
						"#display_$id" =>"<span id='insert_$id' class='current-text mode1'> $updateDesignation->designation </span>"
					),

				);
			}

			return Response::json($data);
		}
		else
		{
			$updateDesignation = Designation::find($id);
			$oldDesignation = $updateDesignation->designation;
			$updateDesignation->designation = Input::get('dsgntn-name');
			$updateDesignation->save();
			$data = array(
				"fragments" => array(
					"#other_message" => "<div class='alert alert-success' id='other_message'>Changed $oldDesignation to $updateDesignation->designation </div>",
					"#message" =>"<div id='message'> </div>"
				),
				"inner-fragments" => array(
					"#display" =>"<span class='current-text mode1'> $updateDesignation->designation  </span>"
				),

			);
			return Response::json($data);
		}
	}


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
		$notselected_users = DB::select("select * from users where id not in ( select users_id from user_has_designation where designation_id = $id) and confirmed = 1");

		//Block URL access for non existing designation
		$existence = Designation::where('id',$id)->get();
		$exist=0;
		foreach ($existence as $designate)
		{
			$exist= $exist+1;
		}

		if ($exist==0)
		{
			return Redirect::to('/designation');
		}
		else
		{
			return View::make('designation_members')
			->with('designation_id',$id)
			->with('notselected_users',$notselected_users)
			->with('selected_users',$selected_users);
		}
	}

	public function save_members()
	{
		$members_selected = Input::get('members_selected');
		$members = explode(",", $members_selected);

		$designation_id = Input::get('designation_id');
		UserHasDesignation::where('designation_id', '=', $designation_id )->delete();

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