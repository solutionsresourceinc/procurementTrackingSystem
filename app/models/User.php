<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
class User extends ConfideUser implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use HasRole;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	//protected $hidden = array('password', 'remember_token');
	
	public $fillable = ['username','firstname','lastname','email','password','confirmation_code',
						'confirmed','created_at','updated_at','office_id'];

	public function designation()
	{
  		return $this->belongsToMany('Designation','user_has_designation');
	}
	public function purchase()
	{
  		return $this->hasMany('Purchase');
	}
	public function document()
	{
  		return $this->belongsToMany('Document','count');
	}
	public function taskDetails()
	{
  		return $this->hasMany('TaskDetails');
	}
}
}
