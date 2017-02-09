<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdQueryTable extends Migration {

	public function up()
	{
		Schema::create('ad_query', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('query_id')->unsigned();
			$table->integer('ad_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('ad_query');
	}
}