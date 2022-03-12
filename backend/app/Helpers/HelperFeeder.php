<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Http;

class HelperFeeder
{
  const LOG_CHANNEL = 'feeder';

  private $FEEDER_HOST;
  private $FEEDER_USERNAME;
  private $FEEDER_PASSWORD;
  private $TOKEN;
  private $RESPONSE;

  public function __construct($token = null)
  {
    $this->FEEDER_HOST = env('FEEDER_HOST', 'xxx');
    $this->FEEDER_USERNAME = env('FEEDER_USERNAME', 'xxx');
    $this->FEEDER_PASSWORD = env('FEEDER_PASSWORD', 'xxx');
    $this->TOKEN = $token;
  }

  /**
   * digunakan untuk mendapatkan feeder host
   */
  public function getFeederHost()
  {
    return $this->FEEDER_HOST;
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
    \Log::channel(self::LOG_CHANNEL)->info("HelperFeeder::koneksi(".$this->getFeederHost().") dengan data $rawBody");
    return $this->HttpPost($this->getFeederHost() .'?=&=', $rawBody);    
  }
  /**
   * digunakan untuk mendapakatn jumlah record nilai mahasiswa atau bisa digunakan juga untuk 
   * mengetahui jumlah record dari KRS
   * keterangan filter bisa mendapatkan ide dari GetRiwayatNilaiMahasiswa
   * {
   * "act":"GetCountRiwayatNilaiMahasiswa",
   * "token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZF9wZW5nZ3VuYSI6ImRjNjE0ZjMxLTU0ODItNDI1Ny04MGVlLWU4NTA1NGY5NWIyOCIsInVzZXJuYW1lIjoiMDcxMDA3IiwicGFzc3dvcmQiOiI3YzYxZWIxZDZkYjE0MWRmZDhiM2E3MWRhYzI2NjQ4MjQ2YmExNjFkIiwibm1fcGVuZ2d1bmEiOiIgVU5JVkVSU0lUQVMgU1VOQU4gR0lSSSAgICAgICAgICAgICAgICAgIiwidGVtcGF0X2xhaGlyIjoiICIsInRnbF9sYWhpciI6IjE4OTktMTItMzFUMTY6NTI6NDguMDAwWiIsImplbmlzX2tlbGFtaW4iOiJMIiwiYWxhbWF0IjoiSmwuIEJyaWdqZW4gS2F0YW1zbyBJSSBXYXJ1IFNpZG9hcmpvIEphd2EgVGltdXIiLCJ5bSI6IiAiLCJza3lwZSI6IiAiLCJub190ZWwiOiIgIiwibm9faHAiOiIwMzEgODUzMjQ3NyIsImFwcHJvdmFsX3BlbmdndW5hIjoiMSIsImFfYWt0aWYiOiIxIiwidGdsX2dhbnRpX3B3ZCI6bnVsbCwiaWRfc2RtX3BlbmdndW5hIjpudWxsLCJpZF9wZF9wZW5nZ3VuYSI6bnVsbCwiaWRfd2lsIjoiOTk5OTk5ICAiLCJsYXN0X3VwZGF0ZSI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsInNvZnRfZGVsZXRlIjoiMCIsImxhc3Rfc3luYyI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsImlkX3VwZGF0ZXIiOiJkYzYxNGYzMS01NDgyLTQyNTctODBlZS1lODUwNTRmOTViMjgiLCJjc2YiOiIxMTM3NjIzOTQ1IiwidG9rZW5fcmVnIjpudWxsLCJqYWJhdGFuIjpudWxsLCJ0Z2xfY3JlYXRlIjoiMTk2OS0xMi0zMVQxNzowMDowMC4wMDBaIiwiaWRfcGVyYW4iOjMsIm5tX3BlcmFuIjoiQWRtaW4gUFQiLCJpZF9zcCI6IjFhOGQzYjcyLTY5YzQtNDFjMy1hZTdkLWVhOWJhZDZhODYwYyIsImlhdCI6MTY0MDIzOTU2MiwiZXhwIjoxNjQwMjQxMzYyfQ._ukqUAp8tCovJYYnpze15-P9NsM42ktxcU-ZsIt7SJw",
   * "filter":""
   * }
  */
  public function getCountRiwayatNilaiMahasiswa($filter = null)
  {
    $rawBody = json_encode([
      'act'=>'GetCountRiwayatNilaiMahasiswa',
      'token'=>$this->TOKEN,
      'filter'=>$filter,      
    ]);

    return $this->HttpPost($this->getFeederHost(), $rawBody);
  }
  /**
   * digunakan untuk mendapatkan krs mahasiswa
   * feeder format query
   * {
   * "act":"GetKRSMahasiswa",
   * "token":"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZF9wZW5nZ3VuYSI6ImRjNjE0ZjMxLTU0ODItNDI1Ny04MGVlLWU4NTA1NGY5NWIyOCIsInVzZXJuYW1lIjoiMDcxMDA3IiwicGFzc3dvcmQiOiI3YzYxZWIxZDZkYjE0MWRmZDhiM2E3MWRhYzI2NjQ4MjQ2YmExNjFkIiwibm1fcGVuZ2d1bmEiOiIgVU5JVkVSU0lUQVMgU1VOQU4gR0lSSSAgICAgICAgICAgICAgICAgIiwidGVtcGF0X2xhaGlyIjoiICIsInRnbF9sYWhpciI6IjE4OTktMTItMzFUMTY6NTI6NDguMDAwWiIsImplbmlzX2tlbGFtaW4iOiJMIiwiYWxhbWF0IjoiSmwuIEJyaWdqZW4gS2F0YW1zbyBJSSBXYXJ1IFNpZG9hcmpvIEphd2EgVGltdXIiLCJ5bSI6IiAiLCJza3lwZSI6IiAiLCJub190ZWwiOiIgIiwibm9faHAiOiIwMzEgODUzMjQ3NyIsImFwcHJvdmFsX3BlbmdndW5hIjoiMSIsImFfYWt0aWYiOiIxIiwidGdsX2dhbnRpX3B3ZCI6bnVsbCwiaWRfc2RtX3BlbmdndW5hIjpudWxsLCJpZF9wZF9wZW5nZ3VuYSI6bnVsbCwiaWRfd2lsIjoiOTk5OTk5ICAiLCJsYXN0X3VwZGF0ZSI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsInNvZnRfZGVsZXRlIjoiMCIsImxhc3Rfc3luYyI6IjIwMjEtMDUtMDZUMTQ6MTk6MDMuNzIwWiIsImlkX3VwZGF0ZXIiOiJkYzYxNGYzMS01NDgyLTQyNTctODBlZS1lODUwNTRmOTViMjgiLCJjc2YiOiIxMTM3NjIzOTQ1IiwidG9rZW5fcmVnIjpudWxsLCJqYWJhdGFuIjpudWxsLCJ0Z2xfY3JlYXRlIjoiMTk2OS0xMi0zMVQxNzowMDowMC4wMDBaIiwiaWRfcGVyYW4iOjMsIm5tX3BlcmFuIjoiQWRtaW4gUFQiLCJpZF9zcCI6IjFhOGQzYjcyLTY5YzQtNDFjMy1hZTdkLWVhOWJhZDZhODYwYyIsImlhdCI6MTYzOTkyMzYyNiwiZXhwIjoxNjM5OTI1NDI2fQ.MJLZxuhhOYO0gu_D2G_vPfxFcx-Zkm6l7rIcSbhcRRs",
   * "filter":"nama_program_studi like 'S1%'",
   * "order":"",
   * "limit":"1",
   * "offset":"0"
   * }
  */
  public function getKRSMahasiswa($order='', $limit = 1, $offset=0, $filter=null)
  {
    $rawBody = json_encode([
      'act'=>'GetKRSMahasiswa',
      'token'=>$this->TOKEN,
      'filter'=>$filter,
      'order'=>$order,
      'limit'=>$limit,
      'offset'=>$offset,
    ]);

    return $this->HttpPost($this->getFeederHost(), $rawBody);
  }
  
}