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

	public static $rules = array(
		'work_id' => 'required',
		//'pr_id' => 'required',
	);


	public function purchase()
	{
  		return $this->belongsTo('Purchase');
	}

	public function attachments()
	{
  		return $this->hasMany('Attachments');
	}

	public function workflow()
	{
  		return $this->belongsTo('Workflow');
	}

	public function users()
	{
  		return $this->belongsToMany('User', 'users_id','document_id');
	}
	
	public function tasks_details()
	{
  		return $this->hasMany('TaskDetails');
	}
}

