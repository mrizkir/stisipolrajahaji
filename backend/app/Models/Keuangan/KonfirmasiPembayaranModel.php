<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class KonfirmasiPembayaranModel extends Model {    
   /**
   * nama tabel model ini.
   *
   * @var string
   */
  protected $table = 'konfirmasi_pembayaran';
  /**
   * primary key tabel ini.
   *
   * @var string
   */
  protected $primaryKey = 'no_transaksi';
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [        
    'no_transaksi',               
    'user_id',                   
    'id_channel',        
    'total_bayar',
    'nomor_rekening_pengirim',
    'nama_rekening_pengirim',
    'nama_bank_pengirim',
    'keterangan',
    'tanggal_bayar',          
    'verified',        
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

  public function user()
  {
    return $this->belongsTo('App\Models\User','user_id','id');
  }
  public function formulir()
  {
    return $this->belongsTo('App\Models\SPMB\FormulirPendaftaranModel','user_id','user_id');
  }
  public function transaksi()
  {
    return $this->belongsTo('App\Models\Keuangan\TransaksiModel', 'no_transaksi', 'no_transaksi');
  }
}