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
	public function workflow()
	{
  		return $this->belongsTo('Workflow');
	}
}
