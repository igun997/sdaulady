<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMatpelTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('matpel', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('nama', 100);
			$table->integer('kelas_id')->index('kelas_id');
			$table->string('nip', 25)->nullable()->index('nip');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('matpel');
	}

}
