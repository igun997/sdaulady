<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Nov 2019 18:36:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class JawabanItem
 * 
 * @property int $id
 * @property int $jawaban_id
 * @property int $ujian_item_id
 * @property string $jawaban
 * 
 * @property \App\Models\UjianItem $ujian_item
 *
 * @package App\Models
 */
class JawabanItem extends Eloquent
{
	protected $table = 'jawaban_item';
	public $timestamps = false;

	protected $casts = [
		'jawaban_id' => 'int',
		'ujian_item_id' => 'int'
	];

	protected $fillable = [
		'jawaban_id',
		'ujian_item_id',
		'jawaban'
	];

	public function jawaban()
	{
		return $this->belongsTo(\App\Models\Jawaban::class);
	}

	public function ujian_item()
	{
		return $this->belongsTo(\App\Models\UjianItem::class);
	}
}
