<?php

namespace App\Http\Controllers\Plugins\H2H\BRK;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Helpers\Helper;
use App\Helpers\HelperAkademik;
use App\Models\SPMB\FormulirPendaftaranOnlineModel;
use App\Models\DMaster\ProgramStudiModel;
use App\Models\Akademik\DulangModel;

use App\Helpers\HelperKeuangan;

use Exception;

class BRKTransaksiController extends Controller {  
	public function inquiryTagihan(Request $request)
	{
		$response = trim(str_replace("\n", "", $request->getContent()));		
		
		try 
		{
			if (!Helper::isJson($response)) {
				throw new Exception (10);
			}

			$data = json_decode($response);			
			$kode_billing = $data->kode_billing;
			$token = $data->token;

			$kode_billing = $request->input('kode_billing');		
			$tipe_transaksi=substr($kode_billing, 0, 2);

			$payload = [];
			switch($tipe_transaksi)
			{
				case 10://bayar biasa
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
					
					if (is_null($data))        
					{
						throw new Exception(14);
					}
					
					if ($data->commited == 1)
					{
						throw new Exception(88);
					}
					
					if ($data->nama_mhs == '' || $data->nim == '' || $data->kjur == 0)
					{
						$mahasiswa = FormulirPendaftaranOnlineModel::find($data->no_formulir);				
						$nama_mhs = is_null($mahasiswa) ? '' : $mahasiswa->nama_mhs;	
						$nim = '-';					
					}
					else
					{
						$nama_mhs = $data->nama_mhs;
						$nim = $data->nim;
					}	
					
					$nominal = \DB::table('transaksi_detail')
						->where('no_transaksi', $data->no_transaksi)
						->sum('dibayarkan');
					
					$payload = [
						"kode_billing" => $data->no_transaksi, 
						"no_formulir" => $data->no_formulir, 
						"nim" => $nim,
						"nama_mhs" => $nama_mhs, 
						"universitas" => "STISIPOL RAJA HAJI TANJUNGPINANG", 
						"fakultas" => "-",
						"prodi" => ProgramStudiModel::find($data->kjur)->value('nama_ps'), 
						"jenis_pembayaran" => "FORMULIR PENDAFTARAN / SPP", 
						"idsmt" => $data->idsmt,
						"ta" => $data->tahun,
						"periode" => $data->tahun.$data->idsmt,
						"nominal" => $nominal,
						"denda" => "0",
						"status" => $data->commited, 
						"updated_at_konfirm" => "N.A"
					];
					
				break;
				case 11: //bayar cuti
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
					->join('v_datamhs AS vdm', 'vdm.nim', 't.nim')
					->join('kelas AS k', 'k.idkelas', 'vdm.idkelas')
					->where('t.no_transaksi', $kode_billing)
					->first();

					if (is_null($data))        
					{
						throw new Exception(14);
					}
					
					if ($data->commited == 1)
					{
						throw new Exception(88);
					}

					$payload = [
						"kode_billing" => $data->no_transaksi, 
						"no_formulir" => $data->no_formulir, 
						"nim" => $data->nim,
						"nama_mhs" => $data->nama_mhs, 
						"universitas" => "STISIPOL RAJA HAJI TANJUNGPINANG", 
						"fakultas" => "-",
						"prodi" => ProgramStudiModel::find($data->kjur)->value('nama_ps'), 
						"jenis_pembayaran" => "CUTI", 
						"idsmt" => $data->idsmt,
						"ta" => $data->tahun,
						"periode" => $data->tahun.$data->idsmt,
						"nominal" => $data->totaltagihan,
						"denda" => "0",
						"status" => $data->commited, 
						"updated_at_konfirm" => "N.A"
					];
				break;
			}
			
			return response()->json([
				'Result' => [
					'status' => 'true',
					'message' => 'Request Data Berhasil',
					'data' => $payload,
				],
			], 200);
		}
		catch(Exception $e)
		{
			$payload = [
				"kode_billing" => "-", 
				"no_formulir" => "-", 
				"nim" => "-",
				"nama_mhs" => "-", 
				"universitas" => "-", 
				"fakultas" => "-",
				"prodi" => "-", 
				"jenis_pembayaran" => "-", 
				"idsmt" => "-",
				"ta" => "-",
				"periode" => "-",
				"nominal" => "-",
				"denda" => "-",
				"status" => "-", 
				"updated_at_konfirm" => "-"
			];
			$code = $e->getMessage();
			switch($code)
			{
				case 10:
					$result = [
						'status'=>'10',
						'message'=>'Format JSON tidak valid',
						'data' => $payload,
					];
				break;
				case 14:
					$result = [
						'status'=>'14',
						'message'=>'Tagihan tidak ditemukan',
						'data' => $payload,
					];
				break;				
				case 88:
					$result = [
						'status'=>'14',
						'message'=>'Tagihan sudah pernah dibayarkan',
						'data' => $payload,
					];
				break;		
				default:
					$result = [
						'status'=>'98',
						'message'=>'Token tidak terdaftar atau ' . $e->getMessage(),
						'data' => $payload,
					];
			}

			return response()->json([
				'Result' => $result
			], 200);
		}	
	}
	public function payment(Request $request)
	{
		$response = trim(str_replace("\n", "", $request->getContent()));		

		try 
		{
			if (!Helper::isJson($response)) {
				throw new Exception (10);
			}

			$data_response = json_decode($response);						
			$kode_billing = $data_response->kode_billing;
			$amount = $data_response->amount;
			$token = $data_response->token;

			$tipe_transaksi=substr($kode_billing, 0, 2);
			$payload = [];

			switch($tipe_transaksi)
			{
				case 10://bayar biasa
					$data = \DB::table('transaksi AS t')
					->select(\DB::raw('
						t.no_transaksi,
						t.kjur,
						t.no_formulir,
						fp.nama_mhs,
						t.nim,
						t.tahun,
						t.idsmt,
						t.idkelas,
						rm.k_status,
						rm.perpanjang,
						fp.ta AS tahun_masuk,
						fp.idsmt AS semester_masuk,
						t.commited
					'))
					->leftJoin('formulir_pendaftaran AS fp', 'fp.no_formulir', 't.no_formulir')
					->leftJoin('register_mahasiswa AS rm', 't.no_formulir', 'rm.no_formulir')
					->where('t.no_transaksi', $kode_billing)
					->first();

					if (is_null($data))        
					{
						throw new Exception(14);
					}
					
					if ($data->commited == 1)
					{
						throw new Exception(88);
					}

					$total_tagihan = \DB::table('transaksi_detail')
						->where('no_transaksi', $data->no_transaksi)
						->sum('dibayarkan');
					
					if ($amount < $total_tagihan)
					{
						throw new Exception(13);
					}
					
					$payload = \DB::transaction(function () use ($data_response, $data, $total_tagihan) {
						$no_transaksi = $data->no_transaksi;
						$no_ref = $data_response->noref;						
						$tanggal_terima = $data_response->tanggal_terima;
						$amount = $data_response->amount;
						$tanggal_kirim = $data_response->tanggal_kirim;
						$userid = $this->getUserid();
						
						\DB::table('transaksi')
							->where('no_transaksi', $no_transaksi)
							->update([
								'no_faktur'=>$no_ref,
								'tanggal' => $tanggal_terima,
								'commited'=> 1
							]);
						
						
						if ($data->nama_mhs == '' && ($data->nim == '' || $data->nim == '0') && $data->kjur == 0) //biaya pendaftaran
						{
							$sql = "INSERT INTO transaksi_api (
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								userid,
								total,
								date_added,
								date_modified
							)
							SELECT 
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								$userid,
								$total_tagihan,
								NOW(),
								NOW() 
							FROM transaksi 
								WHERE no_transaksi='$no_transaksi'";

							\DB::statement($sql);

							return 	[
								'status'=>'00',
								'kode_billing'=>$data->no_transaksi,
								'message'=>'Pembayaran Berhasil',
								'noref'=>$no_ref,
							];
						}
						elseif ($data->nim == '' || $data->nim == '0') //pembayaran mahasiswa baru
						{
							$sql = "INSERT INTO transaksi_api (
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								userid,
								total,
								date_added,
								date_modified
							) 
							SELECT 
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								$userid,
								$total_tagihan,
								NOW(),
								NOW() 
							FROM transaksi 
							WHERE no_transaksi='$no_transaksi'";

							\DB::statement($sql);						
							
						}
						else
						{
							$datadulang = \DB::table('dulang')
								->select(\DB::raw('
									iddulang,
									nim,
									tahun,
									idsmt,
									tanggal,
									idkelas,
									status_sebelumnya,
									k_status
								'))
							 	->where('nim', $data->nim)
								->where('idsmt', $data->idsmt)
								->where('tahun', $data->tahun)
								->first();

							if (is_null($datadulang))
							{
								$h_keuangan = new HelperKeuangan();
								$h_keuangan->setDataMHS([
									'no_formulir'=>$data->no_formulir,
									'nim'=>$data->nim,
									'kjur'=>$data->kjur,
									'ta'=>$data->tahun,
									'idsmt'=>$data->idsmt,
									'tahun_masuk'=>$data->tahun_masuk,
									'semester_masuk'=>$data->semester_masuk,
									'idkelas'=>$data->idkelas,
									'k_status'=>$data->k_status,									
								]);
								$datadulang = $h_keuangan->getDataDulang($data->idsmt, $data->tahun);
								
								if (is_null($datadulang))
								{
									$bool = $h_keuangan->getTresholdPembayaran($data->tahun, $data->idsmt);
									if ($bool)
									{
										$tasmt=$data->tahun.$data->idsmt;
										DulangModel::create([
											'nim'=>$data->nim,
											'tahun'=>$data->tahun,
											'idsmt'=>$data->idsmt,
											'tasmt'=>$tasmt,
											'tanggal'=>\Carbon\Carbon::now(),
											'idkelas'=>$data->idkelas,
											'k_status'=>'A',
											'status_sebelumnya'=>'A',
										]);
										
										\DB::table('register_mahasiswa')
											->where('nim', $data->nim)
											->update([
												'k_status'=>'A'
											]);
									}
								}
								$sql = "INSERT INTO transaksi_api (
									no_transaksi,
									no_faktur,
									kjur,
									tahun,
									idsmt,
									idkelas,
									no_formulir,
									nim,
									commited,
									tanggal,
									userid,
									total,
									date_added,
									date_modified
								) 
								SELECT 
									no_transaksi,
									no_faktur,
									kjur,
									tahun,
									idsmt,
									idkelas,
									no_formulir,
									nim,
									commited,
									tanggal,
									$userid,
									$total_tagihan,
									NOW(),
									NOW() 
								FROM transaksi 
									WHERE no_transaksi='$no_transaksi'";
								
								\DB::statement($sql);
							}

							$payload = [
								"kode_billing" => $data->no_transaksi, 
								"no_formulir" => $data->no_formulir, 
								"nim" => $data->nim,
								"nama_mhs" => $data->nama_mhs, 
								"universitas" => "STISIPOL RAJA HAJI TANJUNGPINANG", 
								"fakultas" => "-",
								"prodi" => ProgramStudiModel::find($data->kjur)->value('nama_ps'), 
								"jenis_pembayaran" => "SPP", 
								"idsmt" => $data->idsmt,
								"ta" => $data->tahun,
								"periode" => $data->tahun.$data->idsmt,
								"nominal" => $total_tagihan,
								"denda" => "0",
								"status" => $data->commited, 
								"updated_at_konfirm" => "N.A"
							];							
							return $payload;
						}
					});
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
							vdm.k_status,
							vdm.tahun_masuk,
							vdm.semester_masuk,
							k.nkelas AS nama_kelas,
							t.dibayarkan AS totaltagihan,
							t.commited,
							t.tanggal,
							t.date_added
						'))
					->join('v_datamhs AS vdm', 'vdm.nim', 't.nim')
					->join('kelas AS k', 'k.idkelas', 'vdm.idkelas')
					->where('t.no_transaksi', $kode_billing)
					->first();

					if (is_null($data))        
					{
						throw new Exception(14);
					}
					
					if ($data->commited == 1)
					{
						throw new Exception(88);
					}

					$total_tagihan = \DB::table('transaksi_detail')
						->where('no_transaksi', $data->no_transaksi)
						->sum('dibayarkan');

					if ($amount < $total_tagihan)
					{
						throw new Exception(13);
					}

					$result = \DB::transaction(function () use ($request, $data) {
						$no_transaksi = $data->no_transaksi;
						$no_ref = $request->input('no_ref');
						$userid = $this->getUserid();
						$total_tagihan = \DB::table('transaksi_detail')
							->where('no_transaksi', $no_transaksi)
							->sum('dibayarkan');

						\DB::table('transaksi')
							->where('no_transaksi', $no_transaksi)
							->update([
								'no_faktur'=>$no_ref,
								'commited'=> 1
							]);

							$datadulang = \DB::table('dulang')
							->select(\DB::raw('
								iddulang,
								nim,
								tahun,
								idsmt,
								tanggal,
								idkelas,
								status_sebelumnya,
								k_status
							'))
							->where('nim', $data->nim)
							->where('idsmt', $data->idsmt)
							->where('tahun', $data->tahun)
							->first();


						if (is_null($datadulang))
						{
							$h_keuangan = new HelperKeuangan();
							$h_keuangan->setDataMHS([
								'no_formulir'=>$data->no_formulir,
								'nim'=>$data->nim,
								'kjur'=>$data->kjur,
								'ta'=>$data->tahun,
								'idsmt'=>$data->idsmt,
								'tahun_masuk'=>$data->tahun_masuk,
								'semester_masuk'=>$data->semester_masuk,
								'idkelas'=>$data->idkelas,
								'k_status'=>$data->k_status,									
							]);
							$datadulang = $h_keuangan->getDataDulang($data->idsmt, $data->tahun);
							
							if (is_null($datadulang))
							{
								$bool = $h_keuangan->getTresholdPembayaran($data->tahun, $data->idsmt);
								if ($bool)
								{
									$tasmt=$data->tahun.$data->idsmt;
									DulangModel::create([
										'nim'=>$data->nim,
										'tahun'=>$data->tahun,
										'idsmt'=>$data->idsmt,
										'tasmt'=>$tasmt,
										'tanggal'=>\Carbon\Carbon::now(),
										'idkelas'=>$data->idkelas,
										'k_status'=>'C',
										'status_sebelumnya'=>$data->k_status,
									]);
									
									\DB::table('register_mahasiswa')
										->where('nim', $data->nim)
										->update([
											'k_status'=>'C'
										]);
								}
							}
							$sql = "INSERT INTO transaksi_api (
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								userid,
								total,
								date_added,
								date_modified
							) 
							SELECT 
								no_transaksi,
								no_faktur,
								kjur,
								tahun,
								idsmt,
								idkelas,
								no_formulir,
								nim,
								commited,
								tanggal,
								$userid,
								$total_tagihan,
								NOW(),
								NOW() 
							FROM transaksi 
								WHERE no_transaksi='$no_transaksi'";
							
							\DB::statement($sql);
						}
						$payload = [
							"kode_billing" => $data->no_transaksi, 
							"no_formulir" => $data->no_formulir, 
							"nim" => $data->nim,
							"nama_mhs" => $data->nama_mhs, 
							"universitas" => "STISIPOL RAJA HAJI TANJUNGPINANG", 
							"fakultas" => "-",
							"prodi" => ProgramStudiModel::find($data->kjur)->value('nama_ps'), 
							"jenis_pembayaran" => "CUTI", 
							"idsmt" => $data->idsmt,
							"ta" => $data->tahun,
							"periode" => $data->tahun.$data->idsmt,
							"nominal" => $total_tagihan,
							"denda" => "0",
							"status" => $data->commited, 
							"updated_at_konfirm" => "N.A"
						];							
						return $payload;
					});					
				break;
			}
			return response()->json([
				'Result' => [
					'status' => '00',
					'message' => 'Pembayaran Data Berhasil',
					'data' => $payload,
				],
			], 200);
		}
		catch(Exception $e)
		{
			$payload = [
				"kode_billing" => "-", 
				"no_formulir" => "-", 
				"nim" => "-",
				"nama_mhs" => "-", 
				"universitas" => "-", 
				"fakultas" => "-",
				"prodi" => "-", 
				"jenis_pembayaran" => "-", 
				"idsmt" => "-",
				"ta" => "-",
				"periode" => "-",
				"nominal" => "-",
				"denda" => "-",
				"status" => "-", 
				"updated_at_konfirm" => "-"
			];
			$code = $e->getMessage();
			switch($code)
			{
				case 10:
					$result = [
						'status'=>'10',
						'message'=>'Format JSON tidak valid',
						'data' => $payload,
					];
				break;
				case 13:
					$result = [
						'status'=>'13',
						'message'=>'Nominal transaksi tidak sesuai',
						'data' => $payload,
					];
				break;				
				case 14:
					$result = [
						'status'=>'14',
						'message'=>'Tagihan tidak ditemukan',
						'data' => $payload,
					];
				break;				
				case 88:
					$result = [
						'status'=>'14',
						'message'=>'Tagihan sudah pernah dibayarkan',
						'data' => $payload,
					];
				break;		
				default:
					$result = [
						'status'=>'98',
						'message'=>'Token tidak terdaftar atau ' . $e->getMessage(),
						'data' => $payload,
					];
			}

			return response()->json([
				'Result' => $result
			], 200);
		}		
	}
}
