<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUjianTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ujian', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('matpel_id')->index('matpel_id');
			$table->string('nama_ujian', 100);
			$table->dateTime('tgl_dibuka');
			$table->dateTime('tgl_ditutup')->nullable();
			$table->integer('waktu');
			$table->integer('pin');
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
		Schema::drop('ujian');
	}

}
