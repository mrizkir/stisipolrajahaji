<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategoriKegiatanDosenTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {   
    Schema::defaultStringLength(191);
    Schema::create('pe3_kategori_kegiatan_dosen', function (Blueprint $table) {        
      $table->uuid('idkategori')->primary();                        
      $table->string('kode_kategori', 25)->unique();        
      $table->string('nama_kategori');        
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
    Schema::dropIfExists('pe3_kategori_kegiatan_dosen');
  }
}