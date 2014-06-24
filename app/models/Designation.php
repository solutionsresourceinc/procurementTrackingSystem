<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Designation extends Eloquent{
	protected $table = 'designation';

	public $timestamps=false;

	protected $fillable=['designation'];

	public $errors;

	
	public function isValid()
	{
		$rules = array('designation'=>'required|alpha_spaces|max:255');

		
		$validation = Validator::make($this->attributes,$rules);

		if($validation->passes()) return true;

		$this->errors = $validation->messages();
		return false;
		
	}
}