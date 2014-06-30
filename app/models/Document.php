<?php
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Zizaco\Confide\ConfideUser;
use Zizaco\Entrust\HasRole;
use LaravelBook\Ardent\Ardent;

class Document extends Ardent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table = 'document';

	public function purchase()
	{
		return $this->hasOne('Purchase');
	}

	public static $rules = array(
		'work_id' => 'required',
		//'pr_id' => 'required',
	);

}

