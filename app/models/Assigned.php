<?php
		
class Assigned extends Eloquent{
	
	public $timestamps = false;
	 
	protected $table = 'assigned_roles';

	protected $hidden = array('password', 'remember_token');
}