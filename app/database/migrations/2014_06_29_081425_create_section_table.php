<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('section', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('sectionName', 255);
			$table->integer('workflow_id')->unsigned();
			$table->integer('section_order_id')->unsigned();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('section');
	}

}
