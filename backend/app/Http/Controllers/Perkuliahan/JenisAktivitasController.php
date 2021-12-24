<?php

namespace App\Http\Controllers\Perkuliahan;

use App\Http\Controllers\Controller;
use App\Models\Akademik\JenisAktivitasModel;

class JenisAktivitasController extends Controller {      
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  { 
    $data = JenisAktivitasModel::all();
    return Response()->json([
      'status'=>'00',        
      'message'=>"data jenis aktivitas berhasil diperoleh",
      'jenisaktivitas'=>$data,
    ], 200); 
  }     
}