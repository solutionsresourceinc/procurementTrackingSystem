<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class Document extends Eloquent{


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	

	 
	protected $table = 'document';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function purchase()
	{
  		return $this->belongsTo('Purchase');
	}
	public function attachments()
	{
  		return $this->hasMany('Attachments');
	}
	public function workflow()
	{
  		return $this->belongsTo('Workflow');
	}

}
