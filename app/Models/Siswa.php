<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Nov 2019 18:36:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Siswa
 *
 * @property string $nis
 * @property string $nama
 * @property string $alamat
 * @property string $no_hp
 * @property int $jk
 * @property string $foto
 * @property string $email
 * @property string $password
 * @property int $kelas_id
 * @property \Carbon\Carbon $dibuat
 *
 * @property \App\Models\Kela $kela
 *
 * @package App\Models
 */
class Siswa extends Eloquent
{
	protected $table = 'siswa';
	protected $primaryKey = 'nis';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'jk' => 'int',
		'kelas_id' => 'int'
	];

	protected $dates = [
		'dibuat'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'nis',
		'nama',
		'alamat',
		'no_hp',
		'jk',
		'foto',
		'email',
		'password',
		'kelas_id',
		'dibuat'
	];

	public function kela()
	{
		return $this->belongsTo(\App\Models\Kela::class, 'kelas_id');
	}
}
