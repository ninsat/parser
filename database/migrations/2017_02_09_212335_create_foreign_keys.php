<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{
		Schema::table('ads', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('ads', function(Blueprint $table) {
			$table->foreign('owner_id')->references('id')->on('owners')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('ad_query', function(Blueprint $table) {
			$table->foreign('query_id')->references('id')->on('querys')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
		Schema::table('ad_query', function(Blueprint $table) {
			$table->foreign('ad_id')->references('id')->on('ads')
						->onDelete('restrict')
						->onUpdate('restrict');
		});
	}

	public function down()
	{
		Schema::table('ads', function(Blueprint $table) {
			$table->dropForeign('ads_user_id_foreign');
		});
		Schema::table('ads', function(Blueprint $table) {
			$table->dropForeign('ads_owner_id_foreign');
		});
		Schema::table('ad_query', function(Blueprint $table) {
			$table->dropForeign('ad_query_query_id_foreign');
		});
		Schema::table('ad_query', function(Blueprint $table) {
			$table->dropForeign('ad_query_ad_id_foreign');
		});
	}
}