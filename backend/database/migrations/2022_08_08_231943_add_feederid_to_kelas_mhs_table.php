<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFeederidToKelasMhsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('kelas_mhs', function (Blueprint $table) {
      $table->string('feederid', 20)->after('isi_nilai')->nullable();
      $table->datetime('lastsync')->after('feederid')->nullable();
      $table->datetime('feedermessage')->after('lastsync')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('calonmahasiswa', function (Blueprint $table) {
      $table->dropColumn('feederid');      
      $table->dropColumn('lastsync');      
      $table->dropColumn('feedermessage');      
    });
  }
}
