<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

use App\Models\Kemahasiswaan\JenisAktivitasModel;

class JenisAktivitasController extends Controller
{      
	const LOG_CHANNEL = 'perkuliahan-aktivitas-mahasiswa';
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{ 
		$this->hasPermissionTo('KEMAHASISWAAN-JENIS-AKTIVITAS_BROWSE');

		$sortdesc = $request->filled('sortdesc') ? $request->query('sortdesc', false) : false;
		$orderby = $sortdesc == 'true' ? 'desc' : 'asc';
		$sortby = $request->filled('sortby') ? $request->query('sortby', 'nama_aktivitas') : 'nama_aktivitas';

		$data = JenisAktivitasModel::orderBy($sortby, $orderby);

		if ($request->filled('search')) {
			$search = $request->query('search');
			$data = $data->whereRaw("nama_aktivitas LIKE '%$search%'");			
		}

		$data = $data->paginate(10);

		return Response()->json([
			'status'=>1,
			'pid'=>'fetch',
			'message'=>"data jenis aktivitas berhasil diperoleh",
			'result'=>$data,
		], 200); 
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id)
	{			
		$this->hasPermissionTo('KEMAHASISWAAN-JENIS-AKTIVITAS_BROWSE');

		$jenisaktivitas = JenisAktivitasModel::find($id);
		if (is_null($jenisaktivitas))
		{
			return Response()->json([
				'status'=>0,
				'pid'=>'update',    
				'message'=>["Data jenis aktivitas dengan id ($id) tidak tersedia di database"]
			], 422); 
		}
		else
		{	
			return Response()->json([
				'status'=>1,
				'pid'=>'fetchdata',
				'result'=>$jenisaktivitas,      
				'message'=>'Data jenis aktivitas berhasil diperoleh.'
			], 200); 
		}
	}   
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$this->hasPermissionTo('KEMAHASISWAAN-JENIS-AKTIVITAS_STORE');

		$rule=[            
			'nama_aktivitas'=>'required|string|unique:pe3_jenis_aktivitas,nama_aktivitas',      
		];
	
		$this->validate($request, $rule);
						
		$jenisaktivitas=JenisAktivitasModel::create([
			'idjenis'=>Uuid::uuid4()->toString(),
			'nama_aktivitas'=>strtoupper($request->input('nama_aktivitas')),       
		]);                 

		return Response()->json([
			'status'=>1,
			'pid'=>'store',
			'jenisaktivitas'=>$jenisaktivitas,    
			'message'=>"data jenis aktivitas berhasil disimpan",    
		], 200); 
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{			
		$this->hasPermissionTo('KEMAHASISWAAN-JENIS-AKTIVITAS_UPDATE');

		$jenisaktivitas = JenisAktivitasModel::find($id);
		if (is_null($jenisaktivitas))
		{
			return Response()->json([
				'status'=>0,
				'pid'=>'update',    
				'message'=>["Data jenis aktivitas dengan id ($id) tidak tersedia di database"]
			], 422); 
		}
		else
		{					
			$this->validate($request, [
				'nama_aktivitas'=>[
					'required',    
					'string',
					Rule::unique('pe3_jenis_aktivitas')->ignore($jenisaktivitas->nama_aktivitas, 'nama_aktivitas')           
				],						
			]); 
	
			$jenisaktivitas->nama_aktivitas = strtoupper($request->input('nama_aktivitas'));
			$jenisaktivitas->save();

			return Response()->json([
				'status'=>1,
				'pid'=>'update',
				'jenisaktivitas'=>$jenisaktivitas,      
				'message'=>'Data jenis aktivitas berhasil diubah.'
			], 200); 
		}
	}   
	public function destroy(Request $request,$id)
	{
		$this->hasPermissionTo('KEMAHASISWAAN-JENIS-AKTIVITAS_DESTROY');

		$jenisaktivitas = JenisAktivitasModel::find($id); 
		
		if (is_null($jenisaktivitas))
		{
			\Log::channel(self::LOG_CHANNEL)->error("Jenis aktivigas dengan id ($id) gagal dihapus oleh {$this->getUsername()} karena var jenisaktivitas=null");

			return Response()->json([
				'status'=>0,				
				'message'=>["Jenis Aktivitas ($id) gagal dihapus"]
			], 422); 
		}		
		else
		{
			$nama_aktivitas=$jenisaktivitas->nama_aktivitas;
			$jenisaktivitas->delete();

			\Log::channel(self::LOG_CHANNEL)->info("Jenis Aktivitas ($nama_aktivitas) berhasil di hapus oleh {$this->getUsername()}");
		
			return Response()->json([
				'status'=>1,
				'pid'=>'destroy',    
				'message'=>"Jenis aktivitas dengan id ($id) berhasil dihapus"
			], 200);    
		}
				  
	} 
}