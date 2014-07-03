<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskDetailsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('taskDetails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('status', 45);
			$table->string('remarks', 255);
			$table->integer('daysOfAction');
			$table->integer('pr_id')->references('id')->on('purchase_request');
			$table->dateTime('otherDate');
			$table->dateTime('dateReceived');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

	
}
