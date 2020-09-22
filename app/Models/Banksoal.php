<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 25 Nov 2019 09:57:17 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Banksoal
 * 
 * @property int $id
 * @property int $matpel_id
 * @property string $judul
 * @property string $soal
 * @property string $jenis
 * @property string $jawaban_es
 * @property string $jawaban_pg
 * @property string $pg_a
 * @property string $pg_b
 * @property string $pg_c
 * @property string $pg_d
 * @property string $pg_e
 * @property int $poin
 * @property \Carbon\Carbon $dibuat
 * 
 * @property \App\Models\Matpel $matpel
 * @property \Illuminate\Database\Eloquent\Collection $ujian_items
 *
 * @package App\Models
 */
class Banksoal extends Eloquent
{
	protected $table = 'banksoal';
	public $timestamps = false;

	protected $casts = [
		'matpel_id' => 'int',
		'poin' => 'int'
	];

	protected $dates = [
		'dibuat'
	];

	protected $fillable = [
		'matpel_id',
		'judul',
		'soal',
		'jenis',
		'jawaban_es',
		'jawaban_pg',
		'pg_a',
		'pg_b',
		'pg_c',
		'pg_d',
		'pg_e',
		'poin',
		'dibuat'
	];

	public function matpel()
	{
		return $this->belongsTo(\App\Models\Matpel::class);
	}

	public function ujian_items()
	{
		return $this->hasMany(\App\Models\UjianItem::class);
	}
}
