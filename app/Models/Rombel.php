<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Nov 2019 18:36:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Rombel
 *
 * @property int $id
 * @property string $nama
 *
 * @property \Illuminate\Database\Eloquent\Collection $kelas
 *
 * @package App\Models
 */
class Rombel extends Eloquent
{
	protected $table = 'rombel';
	public $timestamps = false;

	protected $fillable = [
		'nama'
	];

	public function kelas()
	{
		return $this->hasMany(\App\Models\Kela::class);
	}
}
