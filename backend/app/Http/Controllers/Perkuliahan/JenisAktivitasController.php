<?php

namespace App\Http\Controllers\Perkuliahan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

use App\Models\Akademik\JenisAktivitasModel;

class JenisAktivitasController extends Controller
{      
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
			'pid'=>'fetch',
			'message'=>"data jenis aktivitas berhasil diperoleh",
			'jenisaktivitas'=>$data,
		], 200); 
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$rule=[            
			'nama_aktivitas'=>'required|string|unique:pe3_jenis_aktivitas,nama_aktivitas',      
		];
	
		$this->validate($request, $rule);
						
		$jenisaktivitas=JenisAktivitasModel::create([
			'idjenis'=>Uuid::uuid4()->toString(),
			'nama_aktivitas'=>strtoupper($request->input('nama_aktivitas')),               
		]);                 

		return Response()->json([
			'status'=>'00',        
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
			$jenisaktivitas = JenisAktivitasModel::find($id);
			if (is_null($jenisaktivitas))
			{
				return Response()->json([
					'status'=>404,
					'pid'=>'update',    
					'message'=>["Data jenis aktivitas dengan ($id) tidak tersedia di database"]
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
					'status'=>'00',
					'pid'=>'update',
					'jenisaktivitas'=>$jenisaktivitas,      
					'message'=>'Data jenis aktivitas '.$jenisaktivitas->namaruang.' berhasil diubah.'
				], 200); 
			}
		}    
}