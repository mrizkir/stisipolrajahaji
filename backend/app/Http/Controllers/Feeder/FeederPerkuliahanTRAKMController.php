<?php

namespace App\Http\Controllers\Feeder;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Exception;

use App\Helpers\HelperNilai;
use App\Helpers\HelperKeuangan;

class FeederPerkuliahanTRAKMController extends Controller
{
  /**
   * index
   */
  public function index(Request $request)
  {    
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
      $data = \DB::table('v_datamhs AS A')
      ->select(\DB::raw('
        A.nim,
        A.nama_mhs,
        B.k_status,
        A.tahun_masuk,
        B.idkelas,
        0 AS sks,
        0 AS ips,
        0 AS ipk,
        0 AS spp
      '))
      ->join('dulang AS B', 'B.nim', 'A.nim')      
      ->where('B.idsmt', $semester_akademik)
      ->where('B.tahun', $tahun_akademik)
      ->where('A.kjur', $prodi_id)
      ->orderBy('A.tahun_masuk', 'ASC')
      ->orderBy('A.nama_mhs', 'ASC');      

      $data = $data->paginate(10);
      
      $data->transform(function ($item, $key) use ($tahun_akademik, $semester_akademik) {      
        $helper = new HelperNilai();
        $helper->setDataMHS([
          'nim' => $item->nim
        ]);

        
        $helper->getKHS($tahun_akademik, $semester_akademik);
        $item->ips = $helper->getIPS();
        $item->sks = $helper->getTotalSKS();
        $dataipk = $helper->getIPKSampaiTASemester($tahun_akademik, $semester_akademik, 'ipksks');	                                
        $item->ipk = $dataipk['ipk'];

        $helper = new HelperKeuangan();
        $helper->setDataMHS([
          'nim' => $item->nim,
          'tahun_masuk' => $item->tahun_masuk,
          'idsmt' => $semester_akademik,
          'idkelas' => $item->idkelas,
        ]);

        $spp = $helper->getTotalBiayaMhsPeriodePembayaran('lama');
        $item->spp = $helper->formatUang($spp);
      
        return $item;
      });

      $response = [
        'status'=>1,
			  'pid'=>'fetch',
        'message' => "Data aktivitas kuliah mahasiswa ({$tahun_akademik}{$tahun_akademik}) berhasil diperoleh",
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