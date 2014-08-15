<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserHasDesignation extends Migration {

	public function up()
	{
		Schema::create('user_has_designation', function(Blueprint $table)
		{
			$table->integer('users_id')->references('id')->on('users');
			$table->integer('designation_id')->references('id')->on('designation');
		});
	}

	public function down()
	{
		Schema::drop('user_has_designation');
	}

}
