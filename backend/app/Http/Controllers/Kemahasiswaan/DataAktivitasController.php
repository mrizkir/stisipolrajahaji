<?php

namespace App\Http\Controllers\Kemahasiswaan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

use App\Models\Kemahasiswaan\DataAktivitasModel;

class DataAktivitasController extends Controller
{      
	const LOG_CHANNEL = 'perkuliahan-aktivitas-mahasiswa';
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{ 
		$this->hasPermissionTo('KEMAHASISWAAN-AKTIVITAS_BROWSE');

		$sortdesc = $request->filled('sortdesc') ? $request->query('sortdesc', false) : false;
		$orderby = $sortdesc == 'true' ? 'desc' : 'asc';
		$sortby = $request->filled('sortby') ? $request->query('sortby', 'judul_aktivitas') : 'judul_aktivitas';

		$data = DataAktivitasModel::select(\DB::raw('
			pe3_data_aktivitas.*,
			pe3_jenis_aktivitas.nama_aktivitas
		'))
		->join('pe3_jenis_aktivitas', 'pe3_jenis_aktivitas.idjenis', 'pe3_data_aktivitas.jenis_aktivitas_id')
		->orderBy($sortby, $orderby);

		if ($request->filled('search')) {
			$search = $request->query('search');
			$data = $data->whereRaw("judul_aktivitas LIKE '%$search%'")
			->orWhereRaw("no_sk_tugas LIKE '%$search%'")			
			->orWhereRaw("nama_aktivitas LIKE '%$search%'");			
		}

		$data = $data->paginate(10);

		return Response()->json([
			'status'=>1,
			'pid'=>'fetch',
			'message'=>"data aktivitas berhasil diperoleh",
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
		$this->hasPermissionTo('KEMAHASISWAAN-AKTIVITAS_BROWSE');

		$dataaktivitas = DataAktivitasModel::select(\DB::raw('
			pe3_data_aktivitas.*,
			pe3_jenis_aktivitas.nama_aktivitas
		'))
		->join('pe3_jenis_aktivitas', 'pe3_jenis_aktivitas.idjenis', 'pe3_data_aktivitas.jenis_aktivitas_id')
		->where('pe3_data_aktivitas.id', $id)
		->first();

		if (is_null($dataaktivitas))
		{
			return Response()->json([
				'status'=>0,
				'pid'=>'update',    
				'message'=>["Data data aktivitas dengan id ($id) tidak tersedia di database"]
			], 422); 
		}
		else
		{	
			return Response()->json([
				'status'=>1,
				'pid'=>'fetchdata',
				'result'=>$dataaktivitas,      
				'message'=>"Data aktivitas dengan id ($id) berhasil diperoleh."
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
		$this->hasPermissionTo('KEMAHASISWAAN-AKTIVITAS_STORE');

		$rule=[            
			'prodi_id'=>'required|exists:program_studi,kjur',      
			'idsmt'=>'required|in:1,2',      
			'tahun'=>'required|numeric',			
			'no_sk_tugas'=>'required|unique:pe3_data_aktivitas,no_sk_tugas',      
			'tanggal_sk_tugas'=>'required|date',      
			'jenis_aktivitas_id'=>'required|exists:pe3_jenis_aktivitas,idjenis',      
			'jenis_anggota'=>'required|in:1,2',      
			'judul_aktivitas'=>'required',
		];
	
		$this->validate($request, $rule);
						
		$dataaktivitas=DataAktivitasModel::create([
      'id'=>Uuid::uuid4()->toString(),                        
      'prodi_id'=>$request->input('prodi_id'),        
      'idsmt'=>$request->input('idsmt'),        
      'tahun'=>$request->input('tahun'),        
      'tasmt'=>$request->input('tahun') . $request->input('idsmt'),
      'no_sk_tugas'=>strtoupper($request->input('no_sk_tugas')),
      'tanggal_sk_tugas'=>$request->input('tanggal_sk_tugas'),        
      'jenis_aktivitas_id'=>$request->input('jenis_aktivitas_id'),        
      'jenis_anggota'=>$request->input('jenis_anggota'),
      'judul_aktivitas'=>strtoupper($request->input('judul_aktivitas')),
      'keterangan'=>$request->input('keterangan'),        
      'lokasi'=>$request->input('lokasi'),   			
		]);                 

		return Response()->json([
			'status'=>1,
			'pid'=>'store',
			'result'=>$dataaktivitas,    
			'message'=>"data aktivitas berhasil disimpan",    
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
		$this->hasPermissionTo('KEMAHASISWAAN-AKTIVITAS_UPDATE');

		$dataaktivitas = DataAktivitasModel::find($id);
		if (is_null($dataaktivitas))
		{
			return Response()->json([
				'status'=>0,
				'pid'=>'update',    
				'message'=>["Data data aktivitas dengan id ($id) tidak tersedia di database"]
			], 422); 
		}
		else
		{	
			$rule=[
				'no_sk_tugas'=>'required|unique:pe3_data_aktivitas,no_sk_tugas,'.$dataaktivitas->id,      
				'tanggal_sk_tugas'=>'required|date',      
				'jenis_aktivitas_id'=>'required|exists:pe3_jenis_aktivitas,idjenis',      
				'jenis_anggota'=>'required|in:1,2',      
				'judul_aktivitas'=>'required',
			];
		
			$this->validate($request, $rule);

			$dataaktivitas->no_sk_tugas = strtoupper($request->input('no_sk_tugas'));
			$dataaktivitas->tanggal_sk_tugas=$request->input('tanggal_sk_tugas');
      $dataaktivitas->jenis_aktivitas_id=$request->input('jenis_aktivitas_id');
      $dataaktivitas->jenis_anggota=$request->input('jenis_anggota');
      $dataaktivitas->judul_aktivitas=strtoupper($request->input('judul_aktivitas'));
      $dataaktivitas->keterangan=$request->input('keterangan');
      $dataaktivitas->lokasi=$request->input('lokasi');

			$dataaktivitas->save();

			return Response()->json([
				'status'=>1,
				'pid'=>'update',
				'dataaktivitas'=>$dataaktivitas,      
				'message'=>'Data aktivitas berhasil diubah.'
			], 200); 
		}
	}   
	public function destroy(Request $request,$id)
	{
		$this->hasPermissionTo('KEMAHASISWAAN-AKTIVITAS_DESTROY');

		$dataaktivitas = DataAktivitasModel::find($id); 
		
		if (is_null($dataaktivitas))
		{
			\Log::channel(self::LOG_CHANNEL)->error("Jenis aktivigas dengan id ($id) gagal dihapus oleh {$this->getUsername()} karena var dataaktivitas=null");

			return Response()->json([
				'status'=>0,				
				'message'=>["Data Aktivitas dengan ($id) gagal dihapus"]
			], 422); 
		}		
		else
		{
			$judul_aktivitas=$dataaktivitas->judul_aktivitas;
			$dataaktivitas->delete();

			\Log::channel(self::LOG_CHANNEL)->info("Data Aktivitas berhasil di hapus oleh {$this->getUsername()}");
		
			return Response()->json([
				'status'=>1,
				'pid'=>'destroy',    
				'message'=>"Data aktivitas dengan id ($id) berhasil dihapus"
			], 200);    
		}
				  
	} 
}