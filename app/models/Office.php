<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class Office extends Eloquent{
	protected $table = 'offices';

	public $timestamps=false;

	protected $fillable=['officeName'];

	public $errors;

	
	public function isValid()
	{
		$rules = array('officeName'=>'required|max:255');

		
		$validation = Validator::make($this->attributes,$rules);

		if($validation->passes())
		{
			return true;
		}

		$this->errors = $validation->messages();
		return false;
		
	}
}