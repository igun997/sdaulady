<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBanksoalTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('banksoal', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('matpel_id')->index('matpel_id');
			$table->string('judul', 100);
			$table->text('soal', 65535);
			$table->enum('jenis', array('pg','es'));
			$table->string('jawaban_es', 100)->nullable();
			$table->string('jawaban_pg', 1)->nullable();
			$table->text('pg_a', 65535)->nullable();
			$table->text('pg_b', 65535)->nullable();
			$table->text('pg_c', 65535)->nullable();
			$table->text('pg_d', 65535)->nullable();
			$table->text('pg_e', 65535)->nullable();
			$table->integer('poin')->nullable();
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
		Schema::drop('banksoal');
	}

}
