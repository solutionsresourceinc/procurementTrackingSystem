<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('document', function($table)
        {
            $table->increments('id');
            $table->string('doctitle');
            $table->integer('pr_id');
            $table->integer('work_id');
              $table->timestamps();
		}
	);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
				Schema::drop('document');
	}

}
