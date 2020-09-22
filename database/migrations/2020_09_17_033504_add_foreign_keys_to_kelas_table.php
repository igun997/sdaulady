<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToKelasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('kelas', function(Blueprint $table)
		{
			$table->foreign('rombel_id', 'kelas_ibfk_1')->references('id')->on('rombel')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('kelas_id', 'kelas_ibfk_2')->references('id')->on('kelas')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('kelas', function(Blueprint $table)
		{
			$table->dropForeign('kelas_ibfk_1');
			$table->dropForeign('kelas_ibfk_2');
		});
	}

}
