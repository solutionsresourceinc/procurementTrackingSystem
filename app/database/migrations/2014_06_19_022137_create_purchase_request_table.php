<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('purchase_request', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('projectPurpose', 255);
			$table->string('sourceOfFund', 255);
			$table->string('amount', 255);
			$table->string('office', 255);
			$table->string('requisitioner', 255);
			$table->string('modeOfProcurement', 255);
			$table->string('ControlNo', 255);
			$table->string('status', 255);
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
		Schema::drop('purchase_request');
	}

}
