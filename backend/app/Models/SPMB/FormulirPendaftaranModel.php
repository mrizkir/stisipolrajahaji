<?php

namespace App\Models\SPMB;

use Illuminate\Database\Eloquent\Model;

class FormulirPendaftaranModel  extends Model
{
  /**
	 * nama tabel model ini.
	 *
	 * @var string
	 */
	protected $table = 'formulir_pendaftaran';
	/**
	 * primary key tabel ini.
	 *
	 * @var string
	 */
	protected $primaryKey = 'no_formulir';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [        
		'no_formulir',
    'nama_mhs',
    'tempat_lahir',
    'tanggal_lahir',
    'jk',
    'idagama',
    'nama_ibu_kandung',
    'idwarga',
    'nik',
    'idstatus',
    'alamat_kantor',
    'alamat_rumah',
    'kelurahan',
    'kecamatan',
    'telp_kantor',
    'telp_rumah',
    'telp_hp',
    'idjp',
    'pendidikan_terakhir',
    'nisn',
    'jurusan',
    'kota',
    'provinsi',
    'tahun_pa',
    'jenis_slta',
    'asal_slta',
    'status_slta',
    'nomor_ijazah',
    'kjur1',
    'kjur2',
    'idkelas',
    'daftar_via',
    'ta',
    'idsmt',
    'waktu_mendaftar',
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
	public $timestamps = false;	
}
