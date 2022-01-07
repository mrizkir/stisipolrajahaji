<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Ramsey\Uuid\Uuid;

class CreateJenisAktivitasTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {   
    Schema::defaultStringLength(191);
    Schema::create('pe3_jenis_aktivitas', function (Blueprint $table) {        
      $table->uuid('idjenis')->primary();                        
      $table->string('nama_aktivitas');        
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
    Schema::dropIfExists('pe3_jenis_aktivitas');
  }
}