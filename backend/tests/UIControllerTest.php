<?php

use App\Models\User;

class UIControllerTest extends TestCase
{
	/**
	 * test for ui admin
	 *
	 * @return void
	 */
	public function testAdminDaftarProdi()
	{
    $daftar_user = User::select(\DB::raw('
      *
    '))
    // ->where('userid', 1)
    ->get();

    foreach($daftar_user as $user)
    {
      print ("\nUserid : ".$user->userid);
      print ("\nUsername : ".$user->username);
      print ("\nPage : ".$user->page);
      print ("\n===================");

      $response = $this->actingAs($user)->call('get', '/v2/system/setting/uiadmin');      
      $this->assertEquals(200, $response->status());

      $daftar_prodi = $response->getData()->daftar_prodi;
      $this->assertFalse(is_null($daftar_prodi));
      
      foreach($daftar_prodi as $v)
      {
        //cek nama jenjang tidak boleh null atau kosong
        $this->assertFalse(is_null($v->nama_jenjang) || empty($v->nama_jenjang));
      }

    }		    
	}
}
