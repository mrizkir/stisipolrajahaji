<?php

namespace App\Http\Controllers\Plugins\H2H\IndoBestArthaKreasi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


use App\Helpers\HelperAkademik;
use App\Models\SPMB\FormulirPendaftaranOnlineModel;
use App\Models\DMaster\ProgramStudiModel;

use Exception;

use Ramsey\Uuid\Uuid;

class TransaksiController extends Controller {  
	public function inquiryTagihan(Request $request)
	{
		$this->validate($request, [
			'kode_billing'=>'required'
		]);
		
		$kode_billing = $request->input('kode_billing');
		
		$tipe_transaksi=substr($kode_billing, 0,2);

		switch ($tipe_transaksi)
		{
			case 10: //bayar biasa
				$data = \DB::table('transaksi AS t')
					->select(\DB::raw('
						t.no_transaksi,
						t.no_faktur,
						t.kjur,
						t.tahun,
						t.idsmt,
						t.idkelas,
						k.nkelas,
						t.no_formulir,
						fp.nama_mhs,
						t.nim,
						t.disc,
						t.commited,
						t.tanggal,
						t.date_added
					'))
					->leftJoin('formulir_pendaftaran AS fp', 'fp.no_formulir', 't.no_formulir')
					->leftJoin('kelas AS k', 'k.idkelas', 't.idkelas')
					->where('t.no_transaksi', $kode_billing)
					->first();
				
				if (is_null($data))        {
					return Response()->json(['Result'=>[
													'status'=>'14',        
													'message'=>"request KODE_BILLING ($kode_billing) tidak sesuai"
												]
											], 200); 
				}
				else if ($data->commited==1)
				{
					return Response()->json(['Result'=>[
												'status'=>'88',        
												'message'=>"Tagihan sudah dibayarkan."
											]
										], 200); 
				}
				else
				{
					$payload['kode_billing'] = $data->no_transaksi;
					$payload['no_formulir'] = $data->no_formulir;
					if ($data->nama_mhs == '' || $data->nim == '' || $data->kjur == 0)
					{
						$mahasiswa = FormulirPendaftaranOnlineModel::find($data->no_formulir);				
						$payload['nama_mhs'] = is_null($mahasiswa) ? '' : $mahasiswa->nama_mhs;
						$payload['keterangan'] = 'MAHASISWA BARU';
					}
					else
					{
						$payload['nama_mhs'] = $data->nama_mhs;
						$payload['keterangan'] = 'MAHASISWA LAMA';
					}
					$payload['kode_billing'] = $data->no_transaksi;
					$payload['no_faktur']=$data->no_faktur;
					$payload['kjur']=$data->kjur;
					$payload['tahun']=$data->tahun;
					$payload['idsmt']=$data->idsmt;						
					$payload['idkelas']=$data->idkelas;	
					$payload['nama_prodi']=ProgramStudiModel::find($payload['kjur'])->value('nama_ps');
					$payload['semester']=HelperAkademik::getSemester($payload['idsmt']);	
					$payload['nama_kelas']=$data->nkelas;
					$payload['totaltagihan']=\DB::table('transaksi_detail')
						->where('no_transaksi', $data->no_transaksi)
						->sum('dibayarkan');

					$payload['commited']=$data->commited;

					return response()->json([
						'Result' => [
							'status'=>00,
							'message'=>'Request Data Berhasil',
							'data'=>$payload,
						]
					], 200); 			

				}			
			break;
			case 11:
				$data = \DB::table('transaksi_cuti AS t')
					->select(\DB::raw('
						t.no_transaksi,
						t.no_faktur,
						t.tahun,
						t.idsmt,
						vdm.no_formulir,
						t.nim,
						vdm.nama_mhs,
						vdm.kjur,
						vdm.nama_ps,
						vdm.idkelas,
						k.nkelas AS nama_kelas,
						t.dibayarkan AS totaltagihan,
						t.commited,
						t.tanggal,
						t.date_added
					'))
				->join('v_datamhs AS vdm', 'vdm.nim', 't.no_formulir')
				->join('kelas AS k', 'k.idkelas', 'vdm.idkelas')
				->where('t.no_transaksi', $kode_billing)
				->first();
			
				if (is_null($data))        {
					return Response()->json(['Result'=>[
													'status'=>'14',        
													'message'=>"request KODE_BILLING ($kode_billing) tidak sesuai"
												]
											], 200); 
				}
				else if ($data->commited==1)
				{
					return Response()->json(['Result'=>[
												'status'=>'88',        
												'message'=>"Tagihan sudah dibayarkan."
											]
										], 200); 
				}
				else
				{
					$payload['kode_billing'] = $data->no_transaksi;
					$payload['no_faktur']=$data->no_faktur;
					$payload['no_formulir'] = $data->no_formulir;
					$payload['nim'] = $data->nim;
					$payload['nama_mhs'] = $data->nama_mhs;					
					$payload['nama_ps']=$data->nama_ps;
					$payload['kjur']=$data->kjur;
					$payload['tahun']=$data->tahun;
					$payload['idsmt']=$data->idsmt;						
					$payload['idkelas']=$data->idkelas;	
					$payload['nama_kelas']=$data->nama_kelas;						
					$payload['semester']=HelperAkademik::getSemester($payload['idsmt']);
					$payload['nama_prodi']=ProgramStudiModel::find($payload['kjur'])->value('nama_ps');
					$payload['keterangan']='CUTI';
					$payload['totaltagihan']=$data->totaltagihan;						
					$payload['commited']=$data->commited;	
					$payload['tanggal']=$data->tanggal;	
					$payload['date_added']=$data->date_added;	
				}
				return response()->json([
					'Result' => [
						'status'=>00,
						'message'=>'Request Data Berhasil',
						'data'=>$payload,
					]
				], 200);
			break;
			default:
				return response()->json([
					'Result' => [
						'status'=>30,
						'message'=>'Proses Login telah berhasil, namun ada error yaitu tipe_transaksi tidak dikenal.',						
					]
				], 200);
		}
	}
	public function payment(Request $request)
	{
		$config = ConfigurationModel::getCache();

		$messages=[
			'kode_billing.required'=>'kode billing diperlukan mohon di isi',
			'kode_billing.exists'=>'kode billing tidak terdaftar didatabase',
			'noref.required'=>'Nomor referensi dibutuhkan',
			'noref.numeric'=>'Nomor referensi harus numeric',
			
			'amount”.required'=>'amount diperlukan mohon di isi',
			'amount”.numeric'=>'Nilai amount harus numeric',
			
			'tanggal_terima.required'=>'tanggal terima dibutuhkan',
			'tanggal_kirim.required'=>'tanggal kirim dibutuhkan',
		];
		$validator = Validator::make($request->all(),[
			'kode_billing'=>'required|exists:transaksi,no_transaksi',
			'noref'=>'required|numeric',
			'amount'=>'required|numeric',
			'tanggal_terima'=>'required',
			'tanggal_kirim'=>'required',
		],$messages);

		if ($validator->fails())
		{
			return Response()->json(['Result'=>[
					'status'=>'11',        
					'message'=>"Format request tidak sesuai",
					'errors'=>$validator->customMessages
				]
			], 200); 
		}
		else
		{
			$kode_billing=$request->input('kode_billing');
			$transaksi=TransaksiModel::select(\DB::raw('
										transaksi.id,
										CONCAT(transaksi.no_transaksi,\' \') AS no_transaksi,
										transaksi.no_faktur,
										formulir_pendaftaran.no_formulir,
										transaksi.nim,        
										formulir_pendaftaran.nama_mhs,    
										\''.addslashes($config['NAMA_PT']).'\' AS universitas,        
										\'\' AS fakultas,   
										program_studi.nama_prodi AS prodi,
										transaksi.kjur,
										transaksi.idsmt,
										transaksi.ta,
										transaksi.idkelas,
										transaksi.total,
										0 AS denda,
										transaksi.status,
										COALESCE(konfirmasi_pembayaran.updated_at, "N.A") AS updated_at_konfirm
									'))
									->join('formulir_pendaftaran','formulir_pendaftaran.user_id','transaksi.user_id')
									->leftJoin('konfirmasi_pembayaran','konfirmasi_pembayaran.transaksi_id','transaksi.id')
									->leftJoin('program_studi','program_studi.id','transaksi.kjur')
									->where('transaksi.no_transaksi',$kode_billing)
									->first();
			
			if ($transaksi->status==1)
			{
				return Response()->json(['Result'=>[
												'status'=>'88',        
												'message'=>"Tagihan sudah dibayarkan tanggal: ".\App\Helpers\Helper::tanggal('d/m/Y H:i:s',$transaksi->updated_at_konfirm),
												'noref'=>$transaksi->no_faktur
											]
										], 200); 
			}
			else if ($transaksi->status==2)
			{
				return Response()->json(['Result'=>[
												'status'=>'88',        
												'message'=>"status kode billing ini dibatalkan"
											]
										], 200); 
			}
			else if ($transaksi->total!=$request->input('amount'))
			{     
				return Response()->json(['Result'=>[
											'status'=>'11',        
											'message'=>'Nilai nominal salah ('.\App\Helpers\Helper::formatUang($request->input('amount')).') karena  tidak sama dengan dengan transaksi '.\App\Helpers\Helper::formatUang($transaksi->total)
										]
									], 200); 
			}
			else
			{
				$result = \DB::transaction(function () use ($request,$transaksi) {
					$no_ref=$request->input('noref');
					$konfirmasi=KonfirmasiPembayaranModel::find($transaksi->id);
					if (is_null($konfirmasi))
					{
						$konfirmasi_insert=KonfirmasiPembayaranModel::create([
							'transaksi_id'=>$transaksi->id,    
							'user_id'=>$this->getUserid(),    
							'no_transaksi'=>$transaksi->no_transaksi,
							'id_channel'=>4,
							'total_bayar'=>$transaksi->total,
							'nomor_rekening_pengirim'=>'HOST TO HOST',
							'nama_rekening_pengirim'=>'BANK RIAU KEPRI SYARIAH',
							'nama_bank_pengirim'=>'BANK RIAU KEPRI SYARIAH',
							'desc'=>'',
							'tanggal_bayar'=>date ('Y-m-d H:m:s'),    
							'bukti_bayar'=>"storage/images/buktibayar/paid.png",  
							'verified'=>true
						]);                   
						$transaksi=$konfirmasi_insert->transaksi;                   
					}
					else
					{
						$transaksi=$konfirmasi->transaksi;                   
					}
					$transaksi->no_faktur=$no_ref;
					$transaksi->status=1;
					$transaksi->save();
					
					//aksi setelah PAID

					$detail = TransaksiDetailModel::select(\DB::raw('
														kombi_id
													'))
													->where('transaksi_id',$transaksi->id)
													->get();
					foreach ($detail as $v)
					{
						switch ($v->kombi_id)
						{
							case 101: //biaya formulir pendaftaran
								$formulir=\App\Models\SPMB\FormulirPendaftaranModel::find($transaksi->user_id);                   
								$no_formulir=$formulir->idsmt.mt_rand();
								$formulir->no_formulir=$no_formulir;
								$formulir->save();
							break;
							case 202:
								if (!(\App\Models\Akademik\DulangModel::where('tahun',$transaksi->ta)
																		->where('idsmt',$transaksi->idsmt)
																		->where('idkelas',$transaksi->idkelas)
																		->where('nim',$transaksi->nim)
																		->exists()))
								{
									\App\Models\Akademik\DulangModel::create([
																				'id'=>Uuid::uuid4()->toString(),
																				'user_id'=>$transaksi->user_id,
																				'nim'=>$transaksi->nim,
																				'tahun'=>$transaksi->ta,
																				'idsmt'=>$transaksi->idsmt,
																				'tasmt'=>$transaksi->ta.$transaksi->idsmt,
																				'idkelas'=>$transaksi->idkelas,
																				'status_sebelumnya'=>'A',
																				'k_status'=>'A',
																			]);
								}
							break;
						}
					}
					
					\App\Models\System\ActivityLog::log($request,[
																	'object' => $transaksi, 
																	'object_id' => $transaksi->id, 
																	'user_id' => $this->getUserid(), 
																	'message' => 'Transaksi berhasil.'
																]);
					$result=[
						'status'=>'00',
						'kode_billing'=>$transaksi->no_transaksi,
						'message'=>'Pembayaran Berhasil',
						'noref'=>$no_ref,
					];

					return $result;
				});
				return response()->json([
											'Result' => $result
										], 200);
			}
		
		}        
	}
}
