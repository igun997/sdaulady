<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToJawabanItemTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('jawaban_item', function(Blueprint $table)
		{
			$table->foreign('jawaban_id', 'jawaban_item_ibfk_1')->references('id')->on('jawaban')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->foreign('ujian_item_id', 'jawaban_item_ibfk_2')->references('id')->on('ujian_item')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('jawaban_item', function(Blueprint $table)
		{
			$table->dropForeign('jawaban_item_ibfk_1');
			$table->dropForeign('jawaban_item_ibfk_2');
		});
	}

}
