<?php
/**
 * class ini digunakan untuk kegiatan dosen (perkuliahan)
 */
namespace App\Models\DMaster;

use Illuminate\Database\Eloquent\Model;

class JenisKegiatanDosenModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'pe3_jenis_kegiaatan_dosen';
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
		'kode_kegiatan',		
		'nama_aktivitas',		
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