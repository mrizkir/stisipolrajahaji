<?php

namespace App\Http\Controllers\DMaster;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

use App\Models\DMaster\KategoriKegiatanDosenModel;

class KategoriKegiatanDosenController extends Controller
{      
	const LOG_CHANNEL = 'dmaster-dosen';
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{ 
		$this->hasPermissionTo('DMASTER-DOSEN-KATEGORI-KEGIATAN_BROWSE');

		$sortdesc = $request->filled('sortdesc') ? $request->query('sortdesc', false) : false;
		$orderby = $sortdesc == 'true' ? 'desc' : 'asc';
		$sortby = $request->filled('sortby') ? $request->query('sortby', 'nama_kategori') : 'nama_kategori';

		$data = KategoriKegiatanDosenModel::orderBy($sortby, $orderby);

		if ($request->filled('search')) {
			$search = $request->query('search');
			$data = $data->whereRaw("nama_kategori LIKE '%$search%'");			
		}

		$data = $data->paginate(10);

		return Response()->json([
			'status'=>1,
			'pid'=>'fetch',
			'message'=>"data kategori kegiatan berhasil diperoleh",
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
		$this->hasPermissionTo('DMASTER-DOSEN-KATEGORI-KEGIATAN_BROWSE');

		$kategorikegiatan = KategoriKegiatanDosenModel::find($id);
		if (is_null($kategorikegiatan))
		{
			return Response()->json([
				'status'=>0,
				'pid'=>'update',    
				'message'=>["Data kategori kegiatan dengan id ($id) tidak tersedia di database"]
			], 422); 
		}
		else
		{	
			return Response()->json([
				'status'=>1,
				'pid'=>'fetchdata',
				'result'=>$kategorikegiatan,      
				'message'=>'Data kategori kegiatan berhasil diperoleh.'
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
		$this->hasPermissionTo('DMASTER-DOSEN-KATEGORI-KEGIATAN_STORE');

		$rule=[            
			'kode_kategori'=>'required|numeric|unique:pe3_kategori_kegiatan_dosen,nama_kategori',      
			'nama_kategori'=>'required|string|unique:pe3_kategori_kegiatan_dosen,nama_kategori',      
		];
	
		$this->validate($request, $rule);
						
		$kategorikegiatan=KategoriKegiatanDosenModel::create([
			'idkategori'=>Uuid::uuid4()->toString(),
			'kode_kategori'=>strtoupper($request->input('kode_kategori')),       
			'nama_kategori'=>strtoupper($request->input('nama_kategori')),       
		]);                 

		return Response()->json([
			'status'=>1,
			'pid'=>'store',
			'kategorikegiatan'=>$kategorikegiatan,    
			'message'=>"data kategori kegiatan berhasil disimpan",    
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
		$this->hasPermissionTo('DMASTER-DOSEN-KATEGORI-KEGIATAN_UPDATE');

		$kategorikegiatan = KategoriKegiatanDosenModel::find($id);
		if (is_null($kategorikegiatan))
		{
			return Response()->json([
				'status'=>0,
				'pid'=>'update',    
				'message'=>["Data kategori kegiatan dengan id ($id) tidak tersedia di database"]
			], 422); 
		}
		else
		{					
			$this->validate($request, [
				'kode_kategori'=>[
					'required',    
					'numeric',
					Rule::unique('pe3_kategori_kegiatan_dosen')->ignore($kategorikegiatan->kode_kategori, 'kode_kategori')           
				],						
				'nama_kategori'=>[
					'required',    
					'string',
					Rule::unique('pe3_kategori_kegiatan_dosen')->ignore($kategorikegiatan->nama_kategori, 'nama_kategori')           
				],						
			]); 
	
			$kategorikegiatan->kode_kategori = strtoupper($request->input('kode_kategori'));
			$kategorikegiatan->nama_kategori = strtoupper($request->input('nama_kategori'));
			$kategorikegiatan->save();

			return Response()->json([
				'status'=>1,
				'pid'=>'update',
				'kategorikegiatan'=>$kategorikegiatan,      
				'message'=>'Data kategori kegiatan berhasil diubah.'
			], 200); 
		}
	}   
	public function destroy(Request $request,$id)
	{
		$this->hasPermissionTo('DMASTER-DOSEN-KATEGORI-KEGIATAN_DESTROY');

		$kategorikegiatan = KategoriKegiatanDosenModel::find($id); 
		
		if (is_null($kategorikegiatan))
		{
			\Log::channel(self::LOG_CHANNEL)->error("Kategori kegiatan dengan id ($id) gagal dihapus oleh {$this->getUsername()} karena var kategorikegiatan=null");

			return Response()->json([
				'status'=>0,				
				'message'=>["Kategori kegiatan ($id) gagal dihapus"]
			], 422); 
		}		
		else
		{
			$nama_kategori=$kategorikegiatan->nama_kategori;
			$kategorikegiatan->delete();

			\Log::channel(self::LOG_CHANNEL)->info("Kategori kegiatan ($nama_kategori) berhasil di hapus oleh {$this->getUsername()}");
		
			return Response()->json([
				'status'=>1,
				'pid'=>'destroy',    
				'message'=>"Kategori kegiatan dengan id ($id) berhasil dihapus"
			], 200);    
		}
				  
	} 
}