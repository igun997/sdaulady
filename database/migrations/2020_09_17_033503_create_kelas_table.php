<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateKelasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kelas', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('nama', 80);
			$table->integer('rombel_id')->index('rombel_id_fk');
			$table->integer('kelas_id')->nullable()->index('kelas_id_fk');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kelas');
	}

}
