<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class Attachments extends Eloquent{

	protected $table = 'attachments';

	public function document()
	{
  		return $this->belongsTo('Document');
	}
}
