<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJawabanItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jawaban_item', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('jawaban_id')->index('jawaban_id');
			$table->integer('ujian_item_id')->index('ujian_item_id');
			$table->string('jawaban', 100);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jawaban_item');
	}

}
