<?php

namespace App\Http\Controllers\Feeder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;

use App\Helpers\HelperAkademik;
use App\Helpers\HelperDMaster;

class FeederKelasPerkuliahanController extends Controller
{ 
  /**
   * index = daftar kelas
   */
  public function index(Request $request) {
    
    $rule=[      
			'perPage'=>'required|numeric',      
			'currentPage'=>'required|numeric',      
			'sortBy'=>'required',
			'sortDesc'=>'required',
			'nama_prodi'=>'required',
			'prodi_id'=>'required|exists:program_studi,kjur',
			'semester_akademik'=>'required|in:1,2',
			'tahun_akademik'=>'required|numeric',
		];
    
		$this->validate($request, $rule);

    $perPage = $request->input('perPage');
    $currentPage = $request->input('currentPage');
    $sortBy = $request->input('sortBy');
    $sortDesc = $request->input('sortDesc');
    $prodi_id = $request->input('prodi_id');
    $nama_prodi = $request->input('nama_prodi');
    $semester_akademik = $request->input('semester_akademik');
    $tahun_akademik = $request->input('tahun_akademik');

    try {
      $data = \DB::table('kelas_mhs AS A')
      ->select(\DB::raw('
        A.idkelas_mhs,
        A.idkelas,
        A.nama_kelas,
        A.hari,
        A.jam_masuk,
        A.jam_keluar,
        "" AS kode_matkul,
        "" AS namakelas,
        0 AS jumlah_peserta_kelas,
        B.kmatkul,
        B.nmatkul,
        B.nama_dosen,
        B.nidn,
        C.namaruang,
        C.kapasitas
      '))
      ->join('v_pengampu_penyelenggaraan AS B', 'B.idpengampu_penyelenggaraan', 'A.idpengampu_penyelenggaraan')
      ->leftJoin('ruangkelas AS C', 'C.idruangkelas', 'A.idruangkelas') 
      ->where('B.idsmt', $semester_akademik)
      ->where('B.tahun', $tahun_akademik)
      ->where('kjur', $prodi_id)
      ->orderBy('A.idkelas', 'ASC')
      ->orderBy('A.hari', 'ASC')
      ->orderBy('nmatkul', 'ASC');

      $data = $data->paginate(10);
      
      $data->transform(function ($item, $key) {
        $kmatkul = $item->kmatkul;
        $item->kode_matkul = HelperAkademik::getKMatkul($kmatkul); 
        $item->namakelas = HelperDMaster::getNamaKelasByID($item->idkelas).'-'.chr($item->nama_kelas + 64);
        $jumlah_peserta_kelas = \DB::table('kelas_mhs_detail')
        ->where('idkelas_mhs', $item->idkelas_mhs)
        ->count();
        $item->jumlah_peserta_kelas = $jumlah_peserta_kelas;
  
        return $item;
      });

      $response = [
        'status'=>1,
			  'pid'=>'fetch',
        'message' => "Data kelas perkuliahan ({$tahun_akademik}{$tahun_akademik}) berhasil diperoleh",
        'result' => $data,
      ];
      
      return Response()->json($response, 200); 
    }
    catch(Exception $e) 
    {
      return Response()->json([$e->getMessage()], 422); 
    }        
  } 
}