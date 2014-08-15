<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskDetailsTable extends Migration {

	public function up()
	{
		Schema::create('taskdetails', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('status', 45);
			$table->string('remarks', 255);
			$table->string('assignee', 255);
			$table->integer('daysOfAction');
			$table->integer('task_id')->references('id')->on('tasks');
			$table->integer('assignee_id')->references('id')->on('users');
			$table->integer('doc_id')->references('id')->on('purchase_request');
			$table->dateTime('otherDate');
			$table->dateTime('dateFinished');
			$table->dateTime('dateReceived');
			$table->dateTime('dueDate');
			$table->string('custom1', 255);
			$table->string('custom2', 255);
			$table->string('custom3', 255);
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('taskDetails');
	}

	
}
