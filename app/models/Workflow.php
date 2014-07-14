<?php

class Workflow extends Eloquent{

	protected $table = 'workflow';

	// RELATIONSHIPS
	public function section()
	{
  		return $this->hasMany('Section');
	}
	public function document()
	{
  		return $this->hasMany('Document');
	}
}
