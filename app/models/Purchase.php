<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use LaravelBook\Ardent\Ardent;
class Purchase extends Ardent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	public static $rules = array(
		'projectPurpose' => 'required',
		'sourceOfFund' => 'required',
		'amount' => 'required | price',
		'requisitioner' => 'required',
		'office' => 'required',
		'requisitioner' => 'required',
		'dateRequested' => 'required',

	);

	protected $table = 'purchase_request';

	// Customized Error Message
	public static $customMessages = array(
	//	'alpha_spaces' => 'The :attribute field should only contain letters, numbers and spaces',
		'price' => 'The :attribute field should only contain numbers, dot, comma',


	);
	public function users()
	{
  		return $this->belongsToMany('User');
	}
	public function document()
	{
  		return $this->hasOne('Document');
	}
	public function taskDetails()
	{
  		return $this->hasMany('taskDetails');
	}
}
