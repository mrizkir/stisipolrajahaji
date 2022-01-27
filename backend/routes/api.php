<?php
$router->get('/', function () use ($router) {
	return 'PortalEKampus API';
});

$router->group(['prefix'=>'v2'], function () use ($router)
{
	//auth login
	$router->post('/auth/login',['uses'=>'AuthController@login','as'=>'auth.login']);

	//untuk uifront
	$router->get('/system/setting/uifront',['uses'=>'System\UIController@frontend','as'=>'uifront.frontend']);
});

$router->group(['prefix'=>'v2', 'middleware'=>'auth:api'], function () use ($router)
{
	//authentication
	$router->post('/auth/logout',['uses'=>'AuthController@logout','as'=>'auth.logout']);
	$router->get('/auth/refresh',['uses'=>'AuthController@refresh','as'=>'auth.refresh']);
	$router->get('/auth/me',['uses'=>'AuthController@me','as'=>'auth.me']);

	//ui admin
	$router->get('/system/setting/uiadmin',['uses'=>'System\UIController@admin','as'=>'ui.admin']);

	//kemahasiswaan	
	$router->post('/kemahasiswaan/daftarmhs',['middleware'=>['role:superadmin|manajemen|operator nilai|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@index','as'=>'daftarmhs.index']);
	$router->get('/kemahasiswaan/daftarmhs/all',['middleware'=>['role:superadmin|manajemen|operator nilai|keuangan'],'uses'=>'Kemahasiswaan\DaftarMahasiswaController@all','as'=>'daftarmhs.all']);
	
	//akademik - perkuliahan - aktivitas mahasiswa - jenis aktivitas
	$router->get('/akademik/perkuliahan/jenisaktivitas',['middleware'=>['role:superadmin|manajemen'],'uses'=>'Perkuliahan\JenisAktivitasController@index','as'=>'perkuliahan-jenisaktivitas.index']);				
	$router->post('/akademik/perkuliahan/jenisaktivitas/store',['middleware'=>['role:superadmin|manajemen'],'uses'=>'Perkuliahan\JenisAktivitasController@store','as'=>'perkuliahan-jenisaktivitas.store']);
	$router->get('/akademik/perkuliahan/jenisaktivitas/{id}',['middleware'=>['role:superadmin|manajemen'],'uses'=>'Perkuliahan\JenisAktivitasController@show','as'=>'perkuliahan-jenisaktivitas.show']);
	$router->put('/akademik/perkuliahan/jenisaktivitas/{id}',['middleware'=>['role:superadmin|manajemen'],'uses'=>'Perkuliahan\JenisAktivitasController@update','as'=>'perkuliahan-jenisaktivitas.update']);
	$router->delete('/akademik/perkuliahan/jenisaktivitas/{id}',['middleware'=>['role:superadmin|manajemen'],'uses'=>'Perkuliahan\JenisAktivitasController@destroy','as'=>'perkuliahan-jenisaktivitas.destroy']);

	//setting - users
	$router->get('/system/users',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@index','as'=>'users.index']);
	$router->post('/system/users/store',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@store','as'=>'users.store']);
	$router->put('/system/users/updatepassword/{id}',['uses'=>'System\UsersController@updatepassword','as'=>'users.updatepassword']);
	$router->post('/system/users/uploadfoto/{id}',['uses'=>'System\UsersController@uploadfoto','as'=>'users.uploadfoto']);
	$router->post('/system/users/resetfoto/{id}',['uses'=>'System\UsersController@resetfoto','as'=>'users.resetfoto']);
	$router->post('/system/users/syncallpermissions',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@syncallpermissions','as'=>'users.syncallpermissions']);
	$router->post('/system/users/storeuserpermissions',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@storeuserpermissions','as'=>'users.storeuserpermissions']);
	$router->post('/system/users/revokeuserpermissions',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@revokeuserpermissions','as'=>'users.revokeuserpermissions']);
	$router->put('/system/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@update','as'=>'users.update']);
	$router->get('/system/users/{id}',['uses'=>'System\UsersController@show','as'=>'users.show']);
	$router->delete('/system/users/{id}',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@destroy','as'=>'users.destroy']);
	//lokasi method userpermission ada di file UserController
	$router->get('/system/users/{id}/permission',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@userpermissions','as'=>'users.permission']);
	$router->get('/system/users/{id}/mypermission',['uses'=>'System\UsersController@mypermission','as'=>'users.mypermission']);
	$router->get('/system/users/{id}/prodi',['middleware'=>['role:superadmin'],'uses'=>'System\UsersController@usersprodi','as'=>'users.prodi']);
	$router->get('/system/users/{id}/roles',['uses'=>'System\UsersController@roles','as'=>'users.roles']);

	//setting - users keuangan
	$router->get('/system/userskeuangan',['middleware'=>['role:superadmin|keuangan'],'uses'=>'System\UsersKeuanganController@index','as'=>'userskeuangan.index']);
	$router->post('/system/userskeuangan/store',['middleware'=>['role:superadmin|keuangan'],'uses'=>'System\UsersKeuanganController@store','as'=>'userskeuangan.store']);
	$router->put('/system/userskeuangan/{id}',['middleware'=>['role:superadmin|keuangan'],'uses'=>'System\UsersKeuanganController@update','as'=>'userskeuangan.update']);
	$router->delete('/system/userskeuangan/{id}',['middleware'=>['role:superadmin|keuangan'],'uses'=>'System\UsersKeuanganController@destroy','as'=>'userskeuangan.destroy']);

	//setting - users pmb
	$router->get('/system/userspmb',['middleware'=>['role:superadmin|pmb'],'uses'=>'System\UsersPMBController@index','as'=>'userspmb.index']);
	$router->post('/system/userspmb/store',['middleware'=>['role:superadmin|pmb'],'uses'=>'System\UsersPMBController@store','as'=>'userspmb.store']);
	$router->put('/system/userspmb/{id}',['middleware'=>['role:superadmin|pmb'],'uses'=>'System\UsersPMBController@update','as'=>'userspmb.update']);
	$router->delete('/system/userspmb/{id}',['middleware'=>['role:superadmin|pmb'],'uses'=>'System\UsersPMBController@destroy','as'=>'userspmb.destroy']);

	//setting - users akademik
	$router->get('/system/usersakademik',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersAkademikController@index','as'=>'usersakademik.index']);
	$router->post('/system/usersakademik/store',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersAkademikController@store','as'=>'usersakademik.store']);
	$router->put('/system/usersakademik/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersAkademikController@update','as'=>'usersakademik.update']);
	$router->delete('/system/usersakademik/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersAkademikController@destroy','as'=>'usersakademik.destroy']);

	//setting - users program studi
	$router->get('/system/usersprodi',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@index','as'=>'usersprodi.index']);
	$router->post('/system/usersprodi/store',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@store','as'=>'usersprodi.store']);
	$router->put('/system/usersprodi/{id}',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@update','as'=>'usersprodi.update']);
	$router->get('/system/usersprodi/{id}',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@show','as'=>'usersprodi.show']);
	$router->delete('/system/usersprodi/{id}',['middleware'=>['role:superadmin|programstudi'],'uses'=>'System\UsersProdiController@destroy','as'=>'usersprodi.destroy']);

	//setting - users puslahta
	$router->get('/system/userspuslahta',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@index','as'=>'userspuslahta.index']);
	$router->post('/system/userspuslahta/store',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@store','as'=>'userspuslahta.store']);
	$router->put('/system/userspuslahta/{id}',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@update','as'=>'userspuslahta.update']);
	$router->get('/system/userspuslahta/{id}',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@show','as'=>'userspuslahta.show']);
	$router->delete('/system/userspuslahta/{id}',['middleware'=>['role:superadmin|puslahta'],'uses'=>'System\UsersPuslahtaController@destroy','as'=>'userspuslahta.destroy']);

	//setting - users dosen
	$router->get('/system/usersdosen',['uses'=>'System\UsersDosenController@index','as'=>'usersdosen.index']);
	$router->post('/system/usersdosen/store',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersDosenController@store','as'=>'usersdosen.store']);
	$router->get('/system/usersdosen/biodatadiri/{id}',['middleware'=>['role:superadmin|akademik|programstudi|dosen'],'uses'=>'System\UsersDosenController@biodatadiri','as'=>'usersdosen.biodatadiri']);
	$router->put('/system/usersdosen/biodatadiri/{id}',['middleware'=>['role:superadmin|akademik|programstudi|dosen'],'uses'=>'System\UsersDosenController@updatebiodatadiri','as'=>'usersdosen.updatebiodatadiri']);
	$router->put('/system/usersdosen/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersDosenController@update','as'=>'usersdosen.update']);
	$router->delete('/system/usersdosen/{id}',['middleware'=>['role:superadmin|akademik'],'uses'=>'System\UsersDosenController@destroy','as'=>'usersdosen.destroy']);

});

//payment - [bank riau kepri]
$router->group(['prefix'=>'v2/h2h/iak', 'middleware'=>'auth:api'], function () use ($router)
{
	//inquiry tagihan
	$router->post('/inquiry-tagihan',['uses'=>'Plugins\H2H\IndoBestArthaKreasi\TransaksiController@inquiryTagihan','as'=>'iak.transaksi.inquiry-tagihan']);
	//payment
	$router->post('/payment',['uses'=>'Plugins\H2H\IndoBestArthaKreasi\TransaksiController@payment','as'=>'iak.transaksi.payment']);
});

//android - [gss]
$router->group(['prefix'=>'android'], function () use ($router)
{
	//khs mahasiswa
	$router->get('/khs',['uses'=>'Plugins\Android\AndroidKHSController@index','as'=>'android.khs.index']);
});