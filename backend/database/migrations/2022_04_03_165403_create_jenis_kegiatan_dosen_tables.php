<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Ramsey\Uuid\Uuid;

class CreateJenisKegiatanDosenTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {   
    Schema::defaultStringLength(191);
    Schema::create('pe3_jenis_kegiatan_dosen', function (Blueprint $table) {        
      $table->uuid('idjenis')->primary();                        
      $table->string('kode_kegiatan', 25)->unique();        
      $table->string('nama_kegiatan');        
      $table->timestamps();            
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pe3_jenis_kegiatan_dosen');
  }
}