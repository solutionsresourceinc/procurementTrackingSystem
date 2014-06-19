<?php


class Assigned extends Eloquent{


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	

	 
	protected $table = 'assigned_roles';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');



}
