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
            $table->string('data', 255);
            $table->timestamps();
            $table->integer('saved')->default(0);
            $table->integer('doc_id')->references('id')->on('document')->onDelete('cascade');
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
