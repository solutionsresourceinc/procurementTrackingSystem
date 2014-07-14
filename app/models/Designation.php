<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use LaravelBook\Ardent\Ardent;

class Designation extends Ardent{
	protected $table = 'designation';

	public $timestamps=false;

	protected $fillable=['designation'];

	public $errors;

	public static $rules = array(
		'designation' => 'required|allNum|alpha_spaces|max:255|unique:designation,designation',
	);

	public static $customMessages = array(
		'alpha_spaces' => 'Designation entry not created.',
	);

	public function isValid()
	{
		$rules = array('designation'=>'required|alpha_spaces|max:255|allNum');

		
		$validation = Validator::make($this->attributes,$rules);

		if($validation->passes()) return true;

		$this->errors = $validation->messages();
		return false;
		
	}

	// RELATIONSHIPS
	public function users()
	{
  		return $this->belongsToMany('User', 'users_id','designation_id');
	}
	public function tasks()
	{
  		return $this->hasOne('Task');
	}
}