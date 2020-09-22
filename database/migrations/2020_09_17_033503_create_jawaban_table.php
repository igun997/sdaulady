<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJawabanTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('jawaban', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('ujian_id')->index('ujian_id');
			$table->integer('nis')->index('nis');
			$table->integer('essay')->nullable();
			$table->timestamp('dibuat')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('jawaban');
	}

}
