<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeederJobTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {   
    Schema::defaultStringLength(191);
    Schema::create('pe3_feeder_job', function (Blueprint $table) {      
      $table->uuid('id')->primary();                                    
      $table->smallInteger('pid');                                                                                             
      $table->string('pname'); //process name
      $table->string('data_id');//kode data seperti nim, kode mata kuliah, prodi_id, dll
      $table->text('isi_data'); //isi data lebih lengkap menggunakan format json            
      $table->text('desc')->nullable();
      $table->string('prodi_id', 20)->nullable();
      $table->string('kode_program_studi', 6)->nullable();
      $table->string('id_semester', 5)->nullable(); 
      $table->tinyInteger('status'); //0 belum diprocess, 1 sukses, 2 gagal
      $table->timestamps();

      $table->index('pid');
      $table->index('data_id');
      $table->index('prodi_id');
      $table->index('kode_program_studi');
      $table->index('id_semester');      
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('pe3_feeder_job');
  }
}
