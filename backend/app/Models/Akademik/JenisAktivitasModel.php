<?php
/**
 * class ini digunakan untuk aktivitas mahasiswa (perkuliahan)
 */
namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;

class JenisAktivitasModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'pe3_jenis_aktivitas';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'idjenis';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'idjenis',
		'nama_jenis',		
	];
	/**
	 * enable auto_increment.
	 *
	 * @var string
	 */
	public $incrementing = false;
	/**
	 * activated timestamps.
	 *
	 * @var string
	 */
	public $timestamps = true;
}