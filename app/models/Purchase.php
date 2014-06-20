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
		'projectPurpose' => 'required | alpha_spaces',
		'sourceOfFund' => 'required | alpha_spaces',
		'amount' => 'required | price',
		'office' => 'required',
		'requisitioner' => 'required',
		'modeOfProcurement' => 'required',
		'ControlNo' => 'required | numeric | min:6',
	);

	protected $table = 'purchase_request';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password', 'remember_token');

}
