<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUjianItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ujian_item', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('ujian_id')->index('ujian_id');
			$table->integer('banksoal_id')->index('banksoal_id');
			$table->integer('poin')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ujian_item');
	}

}
