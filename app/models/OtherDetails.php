<?php

class OtherDetails extends Eloquent{


	public $timestamps=false;

	protected $table = 'otherdetails';

	// RELATIONSHIPS
	public function section()
	{
  		return $this->belongsTo('Section');
	}

	public function purchase()
	{
  		return $this->belongsToMany('Purchase','purchase_request_id','otherDetails_id');
	}
}