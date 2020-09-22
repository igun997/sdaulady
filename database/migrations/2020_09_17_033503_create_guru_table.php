<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGuruTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('guru', function(Blueprint $table)
		{
			$table->string('nip', 25)->primary();
			$table->string('nama', 80);
			$table->text('alamat', 65535)->nullable();
			$table->string('no_hp', 15)->nullable();
			$table->string('email', 100)->nullable();
			$table->string('password', 100);
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
		Schema::drop('guru');
	}

}
