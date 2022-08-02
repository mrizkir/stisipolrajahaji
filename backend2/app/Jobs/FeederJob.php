<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

use App\Helpers\HelperFeeder;

class FeederJob implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        throw new Exception("Method HelperFeeder::koneksi() gagal karena mengembalikan nilai NULL");
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

    }
    catch(Exception $e) 
    {      
      Storage::disk('local')->put('feeder/koneksi.json', json_encode($response));
      \Log::channel(self::LOG_CHANNEL)->error("FeederJob:  " . $e->getMessage());      
    }
  }
}
