<?php

namespace App\Http\Controllers\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\HelperAkademik;
use App\Helpers\HelperDMaster;

use App\Models\Akademik\KelasPerkuliahanModel;

class JadwalKuliahController extends Controller {      
  /**
   * digunakan untuk mendapatkan daftar jadwal kuliah
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $this->validate($request, [
      'ta' => 'required',
      'idsmt' => 'required',
      'idprodi' => 'required'
    ]);

    $idsmt = $request->input('idsmt');
    $ta = $request->input('ta');
    $idprodi = $request->input('idprodi');

    $daftar_jadwal = \DB::table('kelas_mhs AS km')
    ->select(\DB::raw("
      km.idkelas_mhs,
      km.idkelas,
      '' AS nama_kelas,
      km.nama_kelas,
      km.hari,
      km.jam_masuk,
      km.jam_keluar,
      vpp.kmatkul,
      '' AS kode_matkul,
      vpp.nmatkul,
      vpp.nama_dosen,
      vpp.nidn,
      rk.namaruang,
      rk.kapasitas,
      0 AS jumlah_peserta_kelas
    "))
    ->join('v_pengampu_penyelenggaraan AS vpp', 'km.idpengampu_penyelenggaraan', 'vpp.idpengampu_penyelenggaraan')
    ->leftJoin('ruangkelas AS rk', 'rk.idruangkelas', 'km.idruangkelas')
    ->where('idsmt', $idsmt)
    ->where('tahun', $ta)
    ->where('kjur', $idprodi)
    ->orderBy('idkelas', 'ASC')
    ->orderBy('hari', 'ASC')
    ->orderBy('nmatkul', 'ASC')    
    ->get();
  
    $daftar_jadwal->transform(function ($item, $key) {
      $kmatkul = $item->kmatkul;
      $item->kode_matkul = HelperAkademik::getKMatkul($kmatkul); 
      $item->namakelas = HelperDMaster::getNamaKelasByID($item->idkelas).'-'.chr($item->nama_kelas + 64) . ' ['.$item->nidn.']';
      $jumlah_peserta_kelas = \DB::table('kelas_mhs_detail')
      ->where('idkelas_mhs', $item->idkelas_mhs)
      ->count();
      $item->jumlah_peserta_kelas = $jumlah_peserta_kelas;

      return $item;
    });
    
    return Response()->json([
      'status'=>'00',
      'message'=>"data jadwal berhasil diperoleh",
      'record'=>$daftar_jadwal
    ], 200); 
  }  

  /**
   * digunakan untuk mendapatkan peserta dari id jadwal kuliah
   * @param id kelas mhs
   * @return \Illuminate\Http\Response
   */
  public function peserta(Request $request, $id)
  {
    $kelas_perkuliahan = KelasPerkuliahanModel::find($id);
    if (is_null($kelas_perkuliahan))
    {
      return Response()->json([
        'status'=>'11',
        'message'=>"data jadwal gagal diperoleh",
        'record'=>null
      ], 200); 
    }
    else
    {      
      $peserta = \DB::table('kelas_mhs_detail AS kmd')
      ->select(\DB::raw('
        kmd.idkrsmatkul,
        vdm.nim,
        vdm.nirm,
        vdm.nama_mhs,
        vdm.jk,
        vdm.tahun_masuk,
        k.sah
      '))      
      ->join('krsmatkul AS km', 'kmd.idkrsmatkul', 'km.idkrsmatkul')
      ->join('krs AS k', 'km.idkrs', 'k.idkrs')
      ->join('v_datamhs AS vdm', 'k.nim', 'vdm.nim')
      ->where('kmd.idkelas_mhs', $id)
      ->where('km.batal', 0)
      ->get();

      return Response()->json([
        'status'=>'00',
        'message'=>"data peserta berhasil diperoleh",
        'record'=>$peserta
      ], 200); 
    }
  }
}