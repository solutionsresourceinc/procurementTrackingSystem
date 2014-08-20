<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration {

	public function up()
	{
		Schema::create('reports', function(Blueprint $table)
		{
			$table->increments('id');
			$table->date('date');
			$table->integer('pRequestCount')->nullable();
			$table->integer('pOrderCount')->nullable();
			$table->integer('chequeCount')->nullable();

		});
	}

	public function down()
	{
		Schema::drop('reports');
	}

}
