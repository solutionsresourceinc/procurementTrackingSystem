<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use LaravelBook\Ardent\Ardent;

class Section extends Ardent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	public $timestamps = false;
	protected $table = 'section';

	public function worklflow()
	{
  		return $this->belongsToMany('Worklflow','workflow_id');
	}
	public function task()
	{
  		return $this->hasMany('Task');
	}

}
