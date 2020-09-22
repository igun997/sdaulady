<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToBanksoalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('banksoal', function(Blueprint $table)
		{
			$table->foreign('matpel_id', 'banksoal_ibfk_1')->references('id')->on('matpel')->onUpdate('CASCADE')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('banksoal', function(Blueprint $table)
		{
			$table->dropForeign('banksoal_ibfk_1');
		});
	}

}
