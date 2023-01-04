<?php

namespace App\Models\Kepegawaian;

use Illuminate\Database\Eloquent\Model;

class DosenModel extends Model {    
	/**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'dosen';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'iddosen';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [        
		'iddosen',
    'nidn',
    'nipy',
    'nama_dosen',
    'gelar_depan',
    'gelar_belakang',
    'idjabatan',
    'alamat_dosen',
    'telp_hp',
    'email',
    'website',
    'username',
    'userpassword',
    'theme',
    'status'
	];
	/**
	 * enable auto_increment.
	 *
	 * @var string
	 */
	public $incrementing = true;
	/**
	 * activated timestamps.
	 *
	 * @var string
	 */
	public $timestamps = false;
}