<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Storage;

use App\Helpers\HelperFeeder;
use Exception;

class FeederJob extends Job
{
  const LOG_CHANNEL = 'feeder';

  /**
   * Feeder instance
   * 
  */
  protected $feeder;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->feeder = new HelperFeeder();
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    try {
      $response = $this->feeder->koneksi();   
      
      if (is_null($response))
      {
        throw new Exception("Method HelperPage::koneksi() gagal karena mengembalikan nilai NULL");
      }
      if (!isset($response['error_code']))
      {
        throw new Exception("Koneksi GAGAL ke server feeder {$this->feeder->getFeederHost()} cek username dan password di .env");
      }      
      if ($response['error_code'] == 0) {
        Storage::disk('local')->put('feeder/koneksi.json', json_encode($response));
      } else {
        Storage::disk('local')->put('feeder/koneksi.json', json_encode($response));
        throw new Exception("Koneksi GAGAL ke server feeder {$this->feeder->getFeederHost()} cek username dan password di .env");
      }      
      $token = $this->feeder->getKoneksi();

      $daftar_job = \DB::table('pe3_feeder_job')
      ->where('status', 0)
      ->get();

      foreach($daftar_job as $v)
      {
        $pid = $v->pid;
        \Log::channel(self::LOG_CHANNEL)->info("FEEDER JOB ID: {$v->id} pid: $pid ({$v->pname}) {$v->isi_data}");   
        
        $isi_data = json_decode($v->isi_data, true);             
        switch($pid)
        {
          case 203: //InsertKelasKuliah
            
          break;
        }
      }
    }
    catch(Exception $e) 
    {
      Storage::disk('local')->put('feeder/koneksi.json', json_encode($response));      
      \Log::channel(self::LOG_CHANNEL)->error("Jobs: Feeder::handle() " . $e->getMessage());
      return Response()->json([$e->getMessage()], 422); 
    } 
  }
}
