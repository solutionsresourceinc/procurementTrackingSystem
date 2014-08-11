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

	public $timestamps = true;
	protected $table = 'purchase_request';

	public static $rules = array(
		'controlNo' => 'required|numeric',
		'projectPurpose' => 'required',
		'projectType' => 'required',
		'sourceOfFund' => 'required',
		'amount' => 'required | price',
		'requisitioner' => 'required',
		'office' => 'required',
		'requisitioner' => 'required',
		'dateReceived' => 'required'
		
	);

	

	// CUSTOMIZABLE ERROR MESSAGE
	public static $customMessages = array(
		'price' => 'The :attribute field should only contain numbers, dot, comma',
	);

	// RELATIONSHIPS
	public function users()
	{
  		return $this->belongsToMany('User');
	}

	public function document()
	{
  		return $this->hasOne('Document');
	}

	public function otherDetails()
	{
		return $this->belongsToMany('OtherDetails','values');
	}

	public function office()
	{
		return $this->belongsTo('Office');
	}

}
