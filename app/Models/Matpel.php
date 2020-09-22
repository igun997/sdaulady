<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Nov 2019 18:36:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Matpel
 * 
 * @property int $id
 * @property string $nama
 * @property int $kelas_id
 * @property string $nip
 * 
 * @property \App\Models\Kela $kela
 * @property \App\Models\Guru $guru
 * @property \Illuminate\Database\Eloquent\Collection $banksoals
 * @property \Illuminate\Database\Eloquent\Collection $ujians
 *
 * @package App\Models
 */
class Matpel extends Eloquent
{
	protected $table = 'matpel';
	public $timestamps = false;

	protected $casts = [
		'kelas_id' => 'int'
	];

	protected $fillable = [
		'nama',
		'kelas_id',
		'nip'
	];

	public function kela()
	{
		return $this->belongsTo(\App\Models\Kela::class, 'kelas_id');
	}

	public function guru()
	{
		return $this->belongsTo(\App\Models\Guru::class, 'nip');
	}

	public function banksoals()
	{
		return $this->hasMany(\App\Models\Banksoal::class);
	}

	public function ujians()
	{
		return $this->hasMany(\App\Models\Ujian::class);
	}
}
