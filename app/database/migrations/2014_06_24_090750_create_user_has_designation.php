<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasDesignation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_has_designation', function(Blueprint $table)
		{
			$table->integer('users_id')->references('id')->on('users')->onDelete('cascade');
			$table->integer('designation_id')->references('id')->on('designation')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_has_designation');
	}

}
