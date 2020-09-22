<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMatpelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('matpel', function(Blueprint $table)
		{
			$table->foreign('kelas_id', 'matpel_ibfk_1')->references('id')->on('kelas')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('nip', 'matpel_ibfk_2')->references('nip')->on('guru')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('matpel', function(Blueprint $table)
		{
			$table->dropForeign('matpel_ibfk_1');
			$table->dropForeign('matpel_ibfk_2');
		});
	}

}
