<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;

class KRSModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'krs';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'idkrs';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'idkrs',
		'tgl_krs',
		'no_krs',
		'nim',
		'idsmt',
		'tahun',
		'tasmt',
		'sah',
		'is_merdeka',
		'tgl_disahkan',
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