<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionTable extends Migration {

	public function up()
	{
		Schema::create('section', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sectionName', 100)->nullable();
			$table->integer('section_order_id')->unsigned()->nullable();
			$table->integer('workflow_id')->references('id')->on('workflow')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('section');
	}

}
