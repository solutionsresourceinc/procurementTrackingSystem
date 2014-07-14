<?php

class Task extends Eloquent{


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	

	 
	protected $table = 'tasks';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function section() //INVERSE OF RELATIONSHIP
	{
  		return $this->belongsToMany('Section');
	}

	public function designation() //INVERSE OF RELATIONSHIP
	{
  		return $this->belongsTo('Designation');
	}

	public function taskDetails() //INVERSE OF RELATIONSHIP
	{
  		return $this->belongsTo('TaskDetails');
	}
}
