<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdNodeTable extends Migration {

	public function up()
	{
		Schema::create('ad_node', function(Blueprint $table) {
			$table->increments('id');
			$table->string('ad_url', 255);
			$table->mediumInteger('ad_user_id');
			$table->string('additional_id', 255);
			$table->string('title', 255);
			$table->mediumInteger('price_uah')->nullable();
			$table->mediumInteger('price_usd')->nullable();
			$table->mediumInteger('price_eur')->nullable();
			$table->smallInteger('city_Id');
			$table->smallInteger('region_id');
			$table->smallInteger('area_id');
			$table->datetime('date');
			$table->text('description');
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('ad_node');
	}
}