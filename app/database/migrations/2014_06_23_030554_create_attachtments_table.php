<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachtmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	

 Schema::create('attachments', function($table)
        {
            $table->increments('id');
            $table->binary('data');
            $table->integer('doc_id');
              $table->timestamps();
              $table->integer('saved')->default(0);
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
		Schema::drop('attachments');
	}

}
