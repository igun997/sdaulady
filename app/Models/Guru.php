<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 24 Nov 2019 18:36:51 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Guru
 *
 * @property string $nip
 * @property string $nama
 * @property string $alamat
 * @property string $no_hp
 * @property string $email
 * @property string $password
 * @property \Carbon\Carbon $dibuat
 *
 * @property \Illuminate\Database\Eloquent\Collection $matpels
 *
 * @package App\Models
 */
class Guru extends Eloquent
{
	protected $table = 'guru';
	protected $primaryKey = 'nip';
	public $incrementing = false;
	public $timestamps = false;

	protected $dates = [
		'dibuat'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'nip',
		'nama',
		'alamat',
		'no_hp',
		'email',
		'password',
		'dibuat'
	];

	public function matpels()
	{
		return $this->hasMany(\App\Models\Matpel::class, 'nip');
	}
}
