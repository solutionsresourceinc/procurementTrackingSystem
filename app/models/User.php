<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use LaravelBook\Ardent\Ardent;
class User extends ConfideUser implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	use HasRole;

	protected $table = 'users';

	public $fillable = ['username','firstname','lastname','email','password','confirmation_code',
						'confirmed','created_at','updated_at','office_id'];

	// RELATIONSHIPS
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

	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}
}
