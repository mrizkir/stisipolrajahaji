<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
      $table->char('nirm', 20);
      $table->tinyInteger('jenis_anggota')->default(1);
      
      $table->timestamps();
      
      
      $table->index('nim');     
      $table->index('nirm');     

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