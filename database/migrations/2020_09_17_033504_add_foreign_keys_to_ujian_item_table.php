<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUjianItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('ujian_item', function(Blueprint $table)
		{
			$table->foreign('ujian_id', 'ujian_item_ibfk_1')->references('id')->on('ujian')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('banksoal_id', 'ujian_item_ibfk_2')->references('id')->on('banksoal')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('ujian_item', function(Blueprint $table)
		{
			$table->dropForeign('ujian_item_ibfk_1');
			$table->dropForeign('ujian_item_ibfk_2');
		});
	}

}
