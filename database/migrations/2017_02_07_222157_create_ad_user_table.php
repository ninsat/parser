<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdUserTable extends Migration {

	public function up()
	{
		Schema::create('ad_user', function(Blueprint $table) {
			$table->increments('id');
			$table->string('user_id', 255);
			$table->string('user_name');
			$table->string('user_tel', 255);
			$table->string('user_tel_second', 255)->nullable();
			$table->string('user_tel_third', 255)->nullable();
			$table->string('user_tel_fourth', 255)->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('ad_user');
	}
}