<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;

use Exception;

class HelperFeeder
{
  const LOG_CHANNEL = 'feeder';
  const FILE_KONEKSI = 'koneksi.json';

  private $FEEDER_WEB_URL;
  private $FEEDER_API_URL;
  private $FEEDER_USERNAME;
  private $FEEDER_PASSWORD;
  private $TOKEN;
  private $RESPONSE;

  public function __construct($token = null)
  {
    $this->FEEDER_WEB_URL = env('FEEDER_WEB_URL', 'xxx');
    $this->FEEDER_API_URL = env('FEEDER_API_URL', 'xxx');
    $this->FEEDER_USERNAME = env('FEEDER_USERNAME', 'xxx');
    $this->FEEDER_PASSWORD = env('FEEDER_PASSWORD', 'xxx');
    $this->TOKEN = $token;
  }
  /**
   * setter token 
  */
  public function setToken($token)
  {
    $this->TOKEN = $token;
  }
  /**
   * digunakan untuk mendapatkan feeder web url
   */
  public function getFeederWeb()
  {
    return $this->FEEDER_WEB_URL;
  }
  /**
   * digunakan untuk mendapatkan feeder api url
   */
  public function getFeederAPI()
  {
    return $this->FEEDER_API_URL;
  }
  /**
   * digunakan untuk mendapatkan feeder username
   */
  public function getFeederUsername()
  {
    return $this->FEEDER_USERNAME;
  }
  /**
   * digunakan untuk mendapatkan feeder password
   */
  public function getFeederPassword()
  {
    return $this->FEEDER_PASSWORD;
  }
  /**
   * digunakan untuk melakukan http request
   */
  private function HttpPost($url, $rawBody)
  { 
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 5);

    $headers = array(
      "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($curl, CURLOPT_POSTFIELDS, $rawBody);

    $response = curl_exec($curl);
    curl_close($curl);
    $this->RESPONSE = json_decode($response, true);

    return $this->RESPONSE;
  }
  /**
   * @return Http response
   */
  public function koneksi()
  {
    $rawBody = json_encode([
      'act'=>'GetToken',
      'username'=>$this->getFeederUsername(),
      'password'=>$this->getFeederPassword(),
    ]);    
    return $this->HttpPost($this->getFeederAPI() .'?=&=', $rawBody);    
  }
  /**
   *  digunakan untuk mendapatkan token dari file koneksi.json
   * @return string koneksi
  */
  public function getKoneksi() {
    if (!Storage::disk('local')->has('feeder/' . self::FILE_KONEKSI)) 
    {
      \Log::channel(self::LOG_CHANNEL)->error("HelperFeeder::koneksi(".$this->getFeederAPI().")");
      return null;
    } 
    $content = Storage::disk('local')->get('feeder/' . self::FILE_KONEKSI);
    if ($content == null)
    {
      \Log::channel(self::LOG_CHANNEL)->error("HelperFeeder::koneksi(".$this->getFeederAPI().")");
      return null;
    } 
    else
    {
      $koneksi = json_decode($content, true);
      
      if (!isset($koneksi['error_code']))
      {
        \Log::channel(self::LOG_CHANNEL)->error("HelperFeeder::koneksi(".$this->getFeederAPI().")");
        return null;
      } 
      else
      {
        switch($koneksi['error_code'])
        {
          case 0:
            $this->TOKEN = $koneksi['data']['token'];
            return $koneksi['data']['token'];
          break;
          default:
            \Log::channel(self::LOG_CHANNEL)->error("HelperFeeder::koneksi(".$this->getFeederAPI().")");
            return null;
        }
      }      
    }
  }        
  /**
   * digunakan untuk mendapatkan list data
   */
  public function list($act, $limit = null, $offset = null, $order = null, $filter = null)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'limit'=>$limit,
      'offset'=>$offset,
      'order'=>$order,
      'filter'=>$filter,
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);
    if (isset($result['error_code']))
    {
      if ($result['error_code'] == 0)
      {
        return $result['data'];
      }
      else
      {
        throw new Exception ("Proses $act gagal dilakukan dengan pesan: " .$result['error_code'] .' '. $result['error_desc']);
      }      
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan dengan pesan: cek token barangkali udah expire atau koneksi gagal ke server feeder");
    }
  }
  /**
   * digunakan untuk mendapakan detail record
   */
  public function detail($act, $filter)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'filter'=>$filter
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);
    if (isset($result['error_code']))
    {
      if ($result['error_code'] == 0)
      {
        return isset($result['data'][0]) ? $result['data'][0] : $result['data'];
      }
      else
      {
        throw new Exception ("Proses $act gagal dilakukan dengan pesan: " . $result['error_code'] .' '. $result['error_desc']);
      }     
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan dengan pesan: cek token atau koneksi");
    }
  }
  /**
   * digunakan untuk menginputkan resource baru
   */
  public function store($act, $record, $job_id = null)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'record'=>$record,      
    ]);       
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);

    if (isset($result['error_code']))
    {
      return $result;         
    }
    else if (is_null($result))
    {
      if (!is_null($job_id))
      {
        \DB::table('pe3_feeder_job')
        ->where('id', $job_id)
        ->update([
          'status' => 2,
          'desc' => "Proses $act gagal dilakukan, tanpa pesan karena result=null",
        ]);
      }
      throw new Exception ("Proses $act gagal dilakukan, tanpa pesan karena result=null");
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan, dengan pesan $result");
    }
  }
  /**
   * digunakan untuk merubah resource
   */
  public function update($act, $key, $record, $job_id = null)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'key'=>$key,
      'record'=>$record,      
    ]);   

    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);

    if (isset($result['error_code']))
    {
      return $result;         
    }
    else if (is_null($result))
    {
      if (!is_null($job_id))
      {
        \DB::table('pe3_feeder_job')
        ->where('id', $job_id)
        ->update([
          'status' => 2,
          'desc' => "Proses $act gagal dilakukan, tanpa pesan karena result=null",
        ]);
      }
      throw new Exception ("Proses $act gagal dilakukan, tanpa pesan karena result=null");
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan, dengan pesan $result");
    }
  }
  /**
   * digunakan untuk menghapus resource
   */
  public function destroy($act, $key, $job_id = null)
  {
    $rawBody = json_encode([
      'act'=>$act,
      'token'=>$this->TOKEN,
      'key'=>$key,      
    ]);
    $result = $this->HttpPost($this->getFeederAPI(), $rawBody);
    if (isset($result['error_code']))
    {
      if ($result['error_code'] == '0')
      {
        return $result;
      }
      else
      {
        $desc = "Proses $act gagal dilakukan dengan pesan: " . $result['error_code'] .' '. $result['error_desc'];
        if (!is_null($job_id))
        {
          \DB::table('pe3_feeder_job')
          ->where('id', $job_id)
          ->update([
            'status' => 2,
            'desc' => $desc,
          ]);
        }
        throw new Exception ($desc . " job id: $job_id");
      }
    }
    else
    {
      throw new Exception ("Proses $act gagal dilakukan, dengan pesan $result");;
    }    
  }  
}