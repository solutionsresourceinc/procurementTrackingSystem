<?php

class OtherDetails extends Eloquent{


	public $timestamps=false;
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	

	protected $table = 'otherdetails';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	public function section() //INVERSE OF RELATIONSHIP
	{
  		return $this->belongsTo('Section');
	}

	public function purchase()
	{
  		return $this->belongsToMany('Purchase','purchase_request_id','otherDetails_id');
	}
}