<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Nov 2019 18:36:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Kela
 * 
 * @property int $id
 * @property string $nama
 * @property int $rombel_id
 * @property int $kelas_id
 * 
 * @property \App\Models\Rombel $rombel
 * @property \App\Models\Kela $kela
 * @property \Illuminate\Database\Eloquent\Collection $kelas
 * @property \Illuminate\Database\Eloquent\Collection $matpels
 * @property \Illuminate\Database\Eloquent\Collection $siswas
 *
 * @package App\Models
 */
class Kela extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'rombel_id' => 'int',
		'kelas_id' => 'int'
	];

	protected $fillable = [
		'nama',
		'rombel_id',
		'kelas_id'
	];

	public function rombel()
	{
		return $this->belongsTo(\App\Models\Rombel::class);
	}

	public function kela()
	{
		return $this->belongsTo(\App\Models\Kela::class, 'kelas_id');
	}

	public function kelas()
	{
		return $this->hasMany(\App\Models\Kela::class, 'kelas_id');
	}

	public function matpels()
	{
		return $this->hasMany(\App\Models\Matpel::class, 'kelas_id');
	}

	public function siswas()
	{
		return $this->hasMany(\App\Models\Siswa::class, 'kelas_id');
	}
}
