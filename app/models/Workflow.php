<?php

class Workflow extends Eloquent{


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	

	protected $table = 'workflow';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function task()
	{
  		return $this->hasMany('Task');
	}
}
