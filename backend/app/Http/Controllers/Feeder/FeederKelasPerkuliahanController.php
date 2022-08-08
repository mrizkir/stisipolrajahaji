<?php

namespace App\Http\Controllers\Feeder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Exception;

use App\Helpers\HelperFeeder;

class FeederKelasPerkuliahanController extends Controller
{      
	const LOG_CHANNEL = 'feeder';

  public function teskoneksi()
  {
    $feeder = new HelperFeeder();    
    try {
      $response = $feeder->koneksi();   

      if (!isset($response['error_code']))
      {
        throw new Exception("Koneksi GAGAL ke server feeder {$feeder->getFeederAPI()} cek username dan password di .env");
      }      
      if ($response['error_code'] == 0) {
        $response['error_desc'] = "Koneksi berhasil ke server feeder {$feeder->getFeederAPI()}";
      }
      \Log::channel(self::LOG_CHANNEL)->info("FeederController::teskoneksi() Berhasil");
      return Response()->json($response, 200); 
    }
    catch(Exception $e) 
    {
      \Log::channel(self::LOG_CHANNEL)->error("FeederController::teskoneksi() gagal karena " . $e->getMessage());
      return Response()->json([$e->getMessage()], 422); 
    }    
  }
  /**
   * mahasiswa - GetKRSMahasiswa()
   */
  public function getkrsmahasiswa(Request $request) {
    
    $rule=[
      'token'=>'required',			
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

    $token = $request->input('token');
    $perPage = $request->input('perPage');
    $currentPage = $request->input('currentPage');
    $sortBy = $request->input('sortBy');
    $sortDesc = $request->input('sortDesc');
    $prodi_id = $request->input('prodi_id');
    $nama_prodi = $request->input('nama_prodi');
    $semester_akademik = $request->input('semester_akademik');
    $tahun_akademik = $request->input('tahun_akademik');

    try {
      $feeder = new HelperFeeder($token);
      $id_periode = $tahun_akademik.$semester_akademik;      
      $response = $feeder->getKRSMahasiswa('nama_mahasiswa ASC', $perPage, $currentPage, "id_periode='$id_periode' AND nama_program_studi LIKE '%$nama_prodi%'");      
      if (!isset($response['error_code']))
      {
        throw new Exception("Koneksi GAGAL ke server feeder {$feeder->getFeederAPI()} cek username dan password di .env");
      } 
      if ($response['error_code'] != 0) {
        throw new Exception($response['error_desc']);
      }
      return Response()->json($response, 200); 
    }
    catch(Exception $e) 
    {
      return Response()->json([$e->getMessage()], 422); 
    }        
  } 
}