<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOwnersTable extends Migration {

	public function up()
	{
		Schema::create('owners', function(Blueprint $table) {
			$table->increments('id');
			$table->string('remote_id', 255);
			$table->string('name', 255);
			$table->string('email', 255)->unique()->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('owners');
	}
}