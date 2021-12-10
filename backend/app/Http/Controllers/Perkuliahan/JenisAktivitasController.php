<?php

namespace App\Http\Controllers\Perkuliahan;

use App\Http\Controllers\Controller;

class JenisAktivitasController extends Controller {      
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  { 
    $data = [];
    return Response()->json([
      'status'=>'00',        
      'message'=>"data jenis aktivitas berhasil diperoleh",
      'data'=>$data,
    ], 200); 
  }     
}