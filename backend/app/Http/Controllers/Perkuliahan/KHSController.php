<?php

namespace App\Http\Controllers\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KHSController extends Controller
{      
  public function index(Request $request)
  {
    $this->hasPermissionTo('AKADEMIK-NILAI-KHS_BROWSE');

    $ta = 2021;
    $idsmt = 1;
    $kjur = 1;
    
    $sortdesc = $request->filled('sortdesc') ? $request->query('sortdesc', false) : false;
		$orderby = $sortdesc == 'true' ? 'desc' : 'asc';
		$sortby = $request->filled('sortby') ? $request->query('sortby', 'vdm.nama_mhs') : 'vdm.nama_mhs';

    $str = "SELECT  FROM krs k,v_datamhs vdm WHERE k.nim=vdm.nim AND tahun='$ta' AND idsmt='$idsmt' AND kjur=$kjur $str_tahun_masuk $str_dosen_wali";

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
    
    
    if ($request->filled('search')) {
			$search = $request->query('search');
			$data = $data->whereRaw("vdm.nim LIKE '%$search%'")
			->orWhereRaw("vdm.nama_mhs LIKE '%$search%'");			
		}
    else
    {
      $data->where('k.tahun', $ta)
        ->where('k.idsmt', $idsmt)
        ->where('k.kjur', $kjur);    
    }

    $data->orderBy($sortby, $orderby);

    return Response()->json([
			'status'=>1,
			'pid'=>'fetchdata',			
			'result'=>$data,			
			'message'=>'Fetch data khs mahasiswa berhasil diperoleh'
		], 200);
  }
}