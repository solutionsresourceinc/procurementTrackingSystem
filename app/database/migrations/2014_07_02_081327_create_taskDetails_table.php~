<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskDetailsTable extends Migration {

	public function up()
	{
		Schema::create('taskdetails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('status', 45)->nullable();
			$table->string('remarks', 255)->nullable();
			$table->string('assignee', 255)->nullable();
			$table->integer('daysOfAction')->nullable();
			$table->integer('task_id')->references('id')->on('tasks')->nullable();
			$table->integer('assignee_id')->references('id')->on('users')->nullable();
			$table->integer('doc_id')->references('id')->on('purchase_request')->nullable();
			$table->dateTime('otherDate')->default('00:00:00 00:00:00');
			$table->dateTime('dateFinished')->default('00:00:00 00:00:00');
			$table->dateTime('dateReceived')->default('00:00:00 00:00:00');
			$table->dateTime('dueDate')->default('00:00:00 00:00:00');
			$table->string('custom1', 255)->nullable();
			$table->string('custom2', 255)->nullable();
			$table->string('custom3', 255)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('taskDetails');
	}

	
}
