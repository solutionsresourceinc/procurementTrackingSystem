<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration {

	public function up()
	{
		Schema::create('document', function($table)
        {
            $table->increments('id');
            $table->string('doctitle',255);
            $table->integer('work_id')->references('id')->on('workflow');
            $table->integer('pr_id')->references('id')->on('purchase_request');
            $table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('document');
	}

}
