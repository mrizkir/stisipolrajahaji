<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\HelperAuth;
use App\Models\System\ConfigurationModel;
use App\Models\DMaster\TAModel;
use App\Models\DMaster\ProgramStudiModel;
use App\Models\DMaster\StatusMahasiswaModel;


class UIController extends Controller {
  /**
   * digunakan untuk mendapatkan setting variabel ui frontend
   */
  public function frontend ()
  {
    $config = ConfigurationModel::getCache();        
    // $captcha_site_key = $config['CAPTCHA_SITE_KEY'];
    // $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
    // $semester_pendaftaran = $config['default_semester_PENDAFTARAN'];
    // $identitas['nama_pt']=$config['NAMA_PT'];
    // $identitas['nama_pt_alias']=$config['NAMA_PT_ALIAS'];
    // $identitas['bentuk_pt']=$config['BENTUK_PT'];
    return Response()->json([
                  'status'=>1,
                  'pid'=>'fetchdata',
                  // 'captcha_site_key'=>$captcha_site_key,
                  // 'tahun_pendaftaran'=>$tahun_pendaftaran,
                  // 'semester_pendaftaran'=>$semester_pendaftaran,
                  // 'identitas'=>$identitas,
                  'daftar_role'=>HelperAuth::getRealRoleName(),
                  'message'=>'Fetch data ui untuk front berhasil diperoleh'
                ], 200);
  }
  /**
   * digunakan untuk mendapatkan setting variabel ui admin
   */
  public function admin ()
  {
    $config = ConfigurationModel::getCache();    
    $daftar_semester=[
      0=>[
        'value'=>1,
        'text'=>'GANJIL'
      ],
      1=>[
        'value'=>2,
        'text'=>'GENAP'
      ],
      2=>[
        'value'=>3,
        'text'=>'PENDEK'
      ]
    ];
    $roles=$this->getRoleNames();
    
    if (count($roles) > 0)
    {
      if ($this->hasRole('superadmin'))
      {
        $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
        ->orderBy('tahun','asc')
        ->get();

        $daftar_prodi=$this->getDaftarProdi();
        
        $prodi_id=$config['default_kjur'];

        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];
      }
      elseif($this->hasRole('pmb'))
      {
        $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
                ->orderBy('tahun','asc')
                ->get();

        $userid=$this->getUserid();

        $kjur=$this->guard()->user()->kjur;        
        $daftar_prodi=$this->getDaftarProdi($kjur); 
        $prodi_id=$kjur > 0 ? $daftar_prodi[0]->id : $config['default_kjur'];        
        
        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];
      }
      elseif ($this->hasRole('puslahta'))
      {
        $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
                ->orderBy('tahun','asc')
                ->get();

        $userid=$this->getUserid();
        
        $kjur=$this->guard()->user()->kjur;        
        $daftar_prodi=$this->getDaftarProdi($kjur); 
        $prodi_id=$kjur > 0 ? $daftar_prodi[0]->id : $config['default_kjur'];        

        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];
      }
      elseif ($this->hasRole('keuangan'))
      {
        $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
                ->orderBy('tahun','asc')
                ->get();

        $userid=$this->getUserid();
        
        $kjur=$this->guard()->user()->kjur;        
        $daftar_prodi=$this->getDaftarProdi($kjur); 
        $prodi_id=$kjur > 0 ? $daftar_prodi[0]->id : $config['default_kjur'];        
        
        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];
      } 
      elseif ($this->hasRole('mahasiswa'))
      {
        $formulir = \DB::table('formulir_pendaftaran AS A')
        ->select(\DB::raw('
          A.*
        '))
        ->join('v_datamhs AS B', 'A.no_formulir', 'B.no_formulir')
        ->where('B.nim', $this->getUsername())
        ->first();        

        $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
          ->where('tahun','>=',$formulir->ta)
          ->orderBy('tahun','asc')
          ->get();
        
        $kjur=$formulir->kjur1;        
        $daftar_prodi=$this->getDaftarProdi($kjur); 
        $prodi_id=$kjur > 0 ? $daftar_prodi[0]->id : $config['default_kjur'];        

        $tahun_pendaftaran = $formulir->ta;
        $tahun_akademik = $config['default_ta'];
      }
      elseif ($this->hasRole('mahasiswabaru'))
      {
        $formulir=\App\Models\SPMB\FormulirPendaftaranModel::find($this->getUserid());
        $daftar_ta=TAModel::where('tahun','=',$formulir->ta)
                  ->select(\DB::raw('tahun AS value,tahun_akademik AS text'))
                  ->get();

        $kjur=$formulir->kjur1;        
        $daftar_prodi=$this->getDaftarProdi($kjur); 
        $prodi_id=$kjur > 0 ? $daftar_prodi[0]->id : $config['default_kjur'];        

        $tahun_pendaftaran = $formulir->ta;
        $tahun_akademik = $formulir->ta;
      }
      elseif ($this->hasRole('manajemen'))
      {
        $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
                ->orderBy('tahun','asc')
                ->get();

        $kjur=$this->guard()->user()->kjur;        
        $daftar_prodi=$this->getDaftarProdi($kjur); 
        $prodi_id=$kjur > 0 ? $daftar_prodi[0]->id : $config['default_kjur'];        

        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];
        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];
      }
      elseif ($this->hasRole('programstudi'))
      {
        $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
                ->orderBy('tahun','asc')
                ->get();

        $userid=$this->getUserid();

        $kjur=$this->guard()->user()->kjur;        
        $daftar_prodi=$this->getDaftarProdi($kjur); 
        $prodi_id=$kjur > 0 ? $daftar_prodi[0]->id : $config['default_kjur'];        

        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];
      }
      elseif ($this->hasRole(['dosen', 'dosenwali']))
      {
        $daftar_ta=TAModel::select(\DB::raw('tahun AS value,tahun_akademik AS text'))
                ->orderBy('tahun','asc')
                ->get();

        $kjur=$this->guard()->user()->kjur;        
        $daftar_prodi=$this->getDaftarProdi($kjur); 
        $prodi_id=$kjur > 0 ? $daftar_prodi[0]->id : $config['default_kjur'];        

        $tahun_pendaftaran = $config['default_tahun_pendaftaran'];
        $tahun_akademik = $config['default_ta'];
      }

      $daftar_kelas=\App\Models\DMaster\KelasModel::select(\DB::raw('idkelas AS id,nkelas AS text'))
      ->get();
      $idkelas='A';

      $daftar_status_mhs=StatusMahasiswaModel::select(\DB::raw('k_status AS id, n_status AS text'))
      ->get();
        
      $k_status = 'A';
      $theme = 'default';
      return Response()->json([
        'status'=>1,
        'pid'=>'fetchdata',
        'roles'=>$roles,
        'daftar_ta'=>$daftar_ta,
        'tahun_pendaftaran'=>$tahun_pendaftaran,
        'tahun_akademik'=>$tahun_akademik,
        'daftar_semester'=>$daftar_semester,
        'semester_akademik' => $config['default_semester'],
        'daftar_prodi'=>$daftar_prodi,
        'prodi_id'=>$prodi_id,
        'daftar_kelas'=>$daftar_kelas,
        'idkelas'=>$idkelas,
        'daftar_status_mhs'=>$daftar_status_mhs,
        'k_status'=>$k_status,
        'theme'=>$theme,
        'message'=>'Fetch data ui untuk admin berhasil diperoleh',
      ], 200)->setEncodingOptions(JSON_NUMERIC_CHECK);
    }
    else
    {
      return Response()->json([
        'status'=>0,
        'message'=>'Fetch data ui gagal karena roles kosong.'
      ], 422);
    }
  }
  private function getDaftarProdi($kjur = 0)
  {
    $daftar_prodi=ProgramStudiModel::select(\DB::raw('
      program_studi.*,
      jenjang_studi.njenjang AS nama_jenjang
    '))
    ->where('kjur', '!=', 0)
    ->join('jenjang_studi', 'jenjang_studi.kjenjang', 'program_studi.kjenjang');

    if ($kjur > 0)
    {
      $daftar_prodi = $daftar_prodi->where('kjur', $kjur);      
    }
    else
    {
      $daftar_prodi = $daftar_prodi->where('kjur', '!=', 0);      
    }
    $daftar_prodi = $daftar_prodi->get();

    return $daftar_prodi;
  }
}
