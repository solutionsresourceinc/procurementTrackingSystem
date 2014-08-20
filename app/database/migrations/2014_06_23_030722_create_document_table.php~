<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration {

	public function up()
	{
		Schema::create('document', function($table)
        {
            $table->increments('id');
            $table->string('doctitle',255)->nullable();
            $table->integer('work_id')->references('id')->on('workflow')->nullable();
            $table->integer('pr_id')->references('id')->on('purchase_request')->nullable();
            $table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('document');
	}

}
