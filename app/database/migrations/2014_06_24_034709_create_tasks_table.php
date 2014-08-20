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
			$table->string('taskName', 255)->nullable();
			$table->string('description', 255)->nullable();
			$table->integer('assignee_id')->nullable();
			$table->integer('maxDuration')->unsigned();
			$table->timestamps();
			$table->integer('section_id')->references('id')->on('section')->onDelete('cascade')->unsigned();
			$table->integer('designation_id')->references('id')->on('designation')->nullable();
			$table->integer('taskDetails_id')->references('id')->on('taskDetails')->nullable();
			$table->integer('order_id')->unsigned()->unsigned();
			$table->string('taskType', 255);
		});
	}

	public function down()
	{
		Schema::drop('tasks');
	}

}
