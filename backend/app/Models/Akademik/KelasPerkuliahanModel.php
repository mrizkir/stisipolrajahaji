<?php

namespace App\Models\Akademik;

use Illuminate\Database\Eloquent\Model;

class KelasPerkuliahanModel extends Model {    
	 /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'kelas_mhs';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'idkelas_mhs';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'idkelas_mhs',
    'idkelas',
    'nama_kelas',
    'hari',
    'jam_masuk',
    'jam_keluar',
    'idpengampu_penyelenggaraan',
    'idruangkelas',
    'persen_quiz',
    'persen_tugas',
    'persen_uts',
    'persen_uas',
    'persen_absen',
    'isi_nilai'
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