<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttachtmentsTable extends Migration {

	public function up()
	{
		Schema::create('attachments', function($table)
        {
            $table->increments('id');
            $table->string('data', 255)->nullable();
            $table->timestamps();
            $table->integer('saved')->default(0);
            $table->integer('doc_id')->references('id')->on('document')->nullable();
		});
	}

	public function down()
	{
		Schema::drop('attachments');
	}

}
