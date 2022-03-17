<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Ramsey\Uuid\Uuid;

class CreatePesertaAktivitasTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {   
    Schema::defaultStringLength(191);
    Schema::create('pe3_peserta_aktivitas', function (Blueprint $table) {        
      $table->uuid('id')->primary();                        
      $table->uuid('data_aktivitas_id');
      $table->char('nim', 20);        
      $table->tinyInteger('idsmt');        
      $table->year('tahun');        
      $table->integer('tasmt');        
      $table->string('no_sk_tugas');        
      $table->date('tanggal_sk_tugas');        
      $table->uuid('jenis_aktivitas_id');        
      $table->tinyInteger('jenis_anggota')->default(1);
      $table->string('judul_aktivitas');        
      $table->string('keterangan');        
      $table->string('lokasi');        

      $table->index('jenis_aktivitas_id');     

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
    Schema::dropIfExists('pe3_peserta_aktivitas');
  }
}