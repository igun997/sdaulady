<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToSiswaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('siswa', function(Blueprint $table)
		{
			$table->foreign('kelas_id', 'siswa_ibfk_1')->references('id')->on('kelas')->onUpdate('CASCADE')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('siswa', function(Blueprint $table)
		{
			$table->dropForeign('siswa_ibfk_1');
		});
	}

}
