<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValuesTable extends Migration {

	public function up()
	{
		Schema::create('values', function($table)
		{
			$table->increments('id');
			$table->string('value', 100);
			$table->integer('otherDetails_id')->references('id')->on('otherDetails')->nullable();
			$table->integer('purchase_request_id')->references('id')->on('purchase_request')->nullable();
		});
	}		

	public function down()
	{
		Schema::drop('values');
	}

}
