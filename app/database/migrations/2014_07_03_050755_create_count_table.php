<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('count', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->references('id')->on('users');
			$table->integer('doc_id')->references('id')->on('document');
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
		Schema::drop('count');
	}

}
