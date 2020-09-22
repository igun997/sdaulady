<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Nov 2019 18:36:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class UjianItem
 * 
 * @property int $id
 * @property int $ujian_id
 * @property int $banksoal_id
 * @property int $poin
 * 
 * @property \App\Models\Ujian $ujian
 * @property \App\Models\Banksoal $banksoal
 * @property \Illuminate\Database\Eloquent\Collection $jawaban_items
 *
 * @package App\Models
 */
class UjianItem extends Eloquent
{
	protected $table = 'ujian_item';
	public $timestamps = false;

	protected $casts = [
		'ujian_id' => 'int',
		'banksoal_id' => 'int',
		'poin' => 'int'
	];

	protected $fillable = [
		'ujian_id',
		'banksoal_id',
		'poin'
	];

	public function ujian()
	{
		return $this->belongsTo(\App\Models\Ujian::class);
	}

	public function banksoal()
	{
		return $this->belongsTo(\App\Models\Banksoal::class);
	}

	public function jawaban_items()
	{
		return $this->hasMany(\App\Models\JawabanItem::class);
	}
}
