<?php

namespace App\Http\Controllers\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KHSController extends Controller
{      
  public function index(Request $request)
  {
    $this->hasPermissionTo('AKADEMIK-NILAI-KHS_BROWSE');

    $rule=[      
			'perPage'=>'required|numeric',      
			'currentPage'=>'required|numeric',      
			'sortBy'=>'required',
			'sortDesc'=>'required',
			'nama_prodi'=>'required',
			'prodi_id'=>'required|exists:program_studi,kjur',
			'semester_akademik'=>'required|in:1,2,3',
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
    
    $data = \DB::table('krs AS k')
    ->select(\DB::raw('
      k.idkrs,
      k.tgl_krs,
      vdm.no_formulir,
      k.nim,
      vdm.nirm,
      vdm.nama_mhs,
      vdm.jk,
      vdm.kjur,
      vdm.tahun_masuk,
      vdm.semester_masuk,
      vdm.idkelas,
      k.sah,
      k.tgl_disahkan,
      0 AS boolpembayaran
    '))
    ->join('v_datamhs AS vdm', 'k.nim', 'vdm.nim');
    
    
    if ($request->has('search')) {
			$search = $request->input('search');
			$data = $data->whereRaw("vdm.nim LIKE '%$search%'")
			->orWhereRaw("vdm.nirm LIKE '%$search%'")			
			->orWhereRaw("vdm.nama_mhs LIKE '%$search%'");			
		}
    else
    {
      $data->where('k.tahun', $tahun_akademik)
        ->where('k.idsmt', $semester_akademik)
        ->where('vdm.kjur', $prodi_id);    
    }

    if($request->has('tahun_masuk'))
    {
      $data->where('vdm.tahun_masuk', $request->input('tahun_masuk'));
    }

    if($request->has('iddosen_wali'))
    {
      $data->where('vdm.iddosen_wali', $request->input('iddosen_wali'));
    }
    
    if ($this->hasRole('mahasiswa'))
    {
      $data->where('vdm.nim', $this->getUsername());
    }
    $data->orderBy($sortBy, $sortDesc);   

    $data = $data->paginate($perPage);

    return Response()->json([
			'status'=>1,
			'pid'=>'fetchdata',			
			'result'=>$data,			
			'message'=>'Fetch data khs mahasiswa berhasil diperoleh'
		], 200);
  }
}