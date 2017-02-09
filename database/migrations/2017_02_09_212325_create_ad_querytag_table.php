<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdQuerytagTable extends Migration {

	public function up()
	{
		Schema::create('ad_querytag', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('querytag_id')->unsigned();
			$table->integer('ad_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('ad_querytag');
	}
}