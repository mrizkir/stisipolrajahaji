<?php

namespace App\Helpers;
use App\Models\System\ConfigurationModel;

class HelperAuth 
{
  /**
	 * daftar role
	 */
	private static $daftar_role=[
		'sa'=>'superadmin',
		'm'=>'manajemen',
    'pmb'=>'pmb',
    'k'=>'keuangan',
    'on'=>'operator nilai',
    'd'=>'dosen',
    'dw'=>'dosoenwali',
    'm'=>'mahasiswa',
    'mb'=>'mahasiswabaru',
    'al'=>'alumni',
    'ot'=>'orangtuawali',      
	];  
  //digunakan untuk mendapat nama role
  public static function getRealRoleName($role = null) {    
    if ($role == null)
    {
      return HelperAuth::$daftar_role;
    }
    else
    {
      return HelperAuth::$daftar_role[$role];
    }
  }
}