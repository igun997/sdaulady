<?php

/**
 * Created by Reliese Model.
 * Date: Thu, 05 Dec 2019 11:21:01 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Jawaban
 * 
 * @property int $id
 * @property int $ujian_id
 * @property string $nis
 * @property \Carbon\Carbon $dibuat
 * @property string $essay
 * 
 * @property \App\Models\Siswa $siswa
 * @property \App\Models\Ujian $ujian
 * @property \Illuminate\Database\Eloquent\Collection $jawaban_items
 *
 * @package App\Models
 */
class Jawaban extends Eloquent
{
	protected $table = 'jawaban';
	public $timestamps = false;

	protected $casts = [
		'ujian_id' => 'int'
	];

	protected $dates = [
		'dibuat'
	];

	protected $fillable = [
		'ujian_id',
		'nis',
		'dibuat',
		'essay'
	];

	public function siswa()
	{
		return $this->belongsTo(\App\Models\Siswa::class, 'nis');
	}

	public function ujian()
	{
		return $this->belongsTo(\App\Models\Ujian::class);
	}

	public function jawaban_items()
	{
		return $this->hasMany(\App\Models\JawabanItem::class);
	}
}
