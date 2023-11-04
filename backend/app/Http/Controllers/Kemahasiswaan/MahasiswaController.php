<?php

namespace App\Http\Controllers\Kemahasiswaan;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class MahasiswaController extends Controller {      
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function status(Request $request, $id)
  { 
    $mahasiswa = \DB::table('v_datamhs')
    ->select(\DB::raw('
      nim,
      nama_mhs,
      k_status AS status,
      photo_profile
    '))
    ->where('nim', $id)
    ->first();    

    return Response()->json([
      'status'=>'00',
      'message'=>"data mahasiswa berhasil diperoleh",
      'result'=>$mahasiswa,
    ], 200); 
  }     
}