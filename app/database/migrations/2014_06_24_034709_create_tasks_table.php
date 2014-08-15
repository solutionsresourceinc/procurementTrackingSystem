<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration {

	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('wf_id')->unsigned();
			$table->string('taskName', 255);
			$table->string('description', 255);
			$table->integer('assignee_id');
			$table->integer('maxDuration')->unsigned();
			$table->timestamps();
			$table->integer('section_id')->references('id')->on('section')->onDelete('cascade');
			$table->integer('designation_id')->references('id')->on('designation');
			$table->integer('taskDetails_id')->references('id')->on('taskDetails');
			$table->integer('order_id')->unsigned();
			$table->string('taskType', 255);
		});
	}

	public function down()
	{
		Schema::drop('tasks');
	}

}
