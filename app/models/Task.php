<?php

class Task extends Eloquent{

	protected $table = 'tasks';

	// RELATIONSHIPS
	public function section()
	{
  		return $this->belongsToMany('Section');
	}

	public function designation()
	{
  		return $this->belongsTo('Designation');
	}

	public function taskDetails()
	{
  		return $this->belongsTo('TaskDetails');
	}
}
