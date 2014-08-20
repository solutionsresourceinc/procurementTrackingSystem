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
			$table->string('projectPurpose', 255)->nullable();
			$table->string('projectType', 255)->nullable();
			$table->string('sourceOfFund', 255)->nullable();
			$table->string('amount', 45)->nullable();
			$table->integer('controlNo')->nullable();
			$table->string('status', 255)->nullable();
			$table->integer('requisitioner')->references('id')->on('users')->nullable();
			$table->integer('office')->references('id')->on('offices')->nullable();
			$table->string('reason', 255)->nullable();
			$table->dateTime('dateRequested')->default("00:00:00 00:00:00");
			$table->dateTime('dateReceived')->default("00:00:00 00:00:00");
			$table->dateTime('dueDate')->default("00:00:00 00:00:00");
			$table->string('otherType', 255)->nullable();
			$table->integer('created_by')->unsigned();
			$table->timestamps();
		});

		DB::unprepared("ALTER TABLE  purchase_request CHANGE  controlNo  controlNo INT( 5 ) ZEROFILL");
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
