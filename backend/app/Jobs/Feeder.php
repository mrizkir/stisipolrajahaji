<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Storage;

use App\Helpers\HelperFeeder;
use Exception;

class Feeder extends Job
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

      $path = \Storage::path('feeder/koneksi.json');      
      
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
    }
    catch(Exception $e) 
    {
      // Storage::disk('local')->put('feeder/koneksi.json', json_encode($response));      
      \Log::channel(self::LOG_CHANNEL)->error("Jobs: Feeder::handle() " . $e->getMessage());
      return Response()->json([$e->getMessage()], 422); 
    } 
  }
}
