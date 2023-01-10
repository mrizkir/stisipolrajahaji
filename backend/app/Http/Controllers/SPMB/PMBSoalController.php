<?php

namespace App\Http\Controllers\SPMB;

use App\Http\Controllers\Controller;

class PMBSoalController extends Controller {      
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  { 
    $soal = [];

    return Response()->json([
      'status'=>'00',
      'message'=>"data pmb berhasil diperoleh",
      'soal'=>$soal
    ], 200); 
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function all()
  { 
    $soal = \DB::table('soal')
    ->get();

    
    foreach($soal as $k=>$v) 
    {
      echo $v->nama_soal . "<br>";
      $jawaban = \DB::table('jawaban')
      ->where('idsoal', $v->idsoal)
      ->get();

      foreach($jawaban as $jawab) 
      {
        echo "<ul>";
        echo "<li>". $jawab->jawaban . "(" . $jawab->status . ")" . "</li>";
        echo "</ul>";
      }

    }
    
    // return Response()->json([
    //   'status'=>'00',
    //   'message'=>"data pmb berhasil diperoleh",
    //   'soal'=>$soal
    // ], 200); 
  }
}