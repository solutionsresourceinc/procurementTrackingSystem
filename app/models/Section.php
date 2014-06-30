<?php

class Section extends Eloquent{


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	

	public $timestamps = false;
	protected $table = 'section';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function worklflow()
	{
  		return $this->belongsToMany('Worklflow','workflow_id');
	}
	public function task()
	{
  		return $this->hasMany('Task');
	}
}
