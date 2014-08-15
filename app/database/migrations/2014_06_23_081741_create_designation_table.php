<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignationTable extends Migration {

	public function up()
	{
		Schema::create('designation', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('designation', 100);
			
		});
	}

	public function down()
	{
		Schema::drop('designation');
	}

}
