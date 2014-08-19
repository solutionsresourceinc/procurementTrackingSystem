<?php

class TaskDetails extends Eloquent{

	// RELATIONSHIPS
	protected $table = 'taskdetails';

	public function tasks()
	{
  		return $this->hasMany('tasks');
	}

	public function document() 
	{
  		return $this->belongsTo('Document');
	}
	
	public function user()
	{
  		return $this->belongsTo('User');
	}

}
