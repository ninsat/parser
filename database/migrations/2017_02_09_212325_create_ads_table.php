<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdsTable extends Migration {

	public function up()
	{
		Schema::create('ads', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('owner_id')->unsigned();
			$table->string('remote_id', 255);
			$table->string('url', 255)->unique();
			$table->string('title', 255);
			$table->text('description');
			$table->integer('price_uah')->nullable();
			$table->integer('price_usd')->nullable();
			$table->integer('price_eur')->nullable();
			$table->timestamp('date');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('ads');
	}
}