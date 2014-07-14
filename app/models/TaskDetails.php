<?php

class TaskDetails extends Eloquent{


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	

	 
	protected $table = 'taskDetails';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function tasks() //INVERSE OF RELATIONSHIP
	{
  		return $this->hasMany('tasks');
	}

	public function document() //INVERSE OF RELATIONSHIP
	{
  		return $this->belongsTo('Document');
	}
	
	public function user() //INVERSE OF RELATIONSHIP
	{
  		return $this->belongsTo('User');
	}

}
