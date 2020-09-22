<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('siswa', function(Blueprint $table)
		{
			$table->string('nis', 15)->primary();
			$table->string('nama', 80);
			$table->text('alamat', 65535)->nullable();
			$table->string('no_hp', 15)->nullable();
			$table->integer('jk');
			$table->string('foto', 100)->nullable();
			$table->string('email', 100)->nullable();
			$table->string('password');
			$table->integer('kelas_id')->nullable()->index('kelas_id');
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
		Schema::drop('siswa');
	}

}
