<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUjianTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ujian', function(Blueprint $table)
		{
			$table->foreign('matpel_id', 'ujian_ibfk_1')->references('id')->on('matpel')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ujian', function(Blueprint $table)
		{
			$table->dropForeign('ujian_ibfk_1');
		});
	}

}
