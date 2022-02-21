<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePe2UsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{   
		Schema::defaultStringLength(191);
		Schema::create('pe2_users', function (Blueprint $table) {
			$table->integer('user_id')->primary();
			$table->string('nomor_hp')->nullable();
			$table->string('nomor_hp2')->nullable();
			$table->string('foto')->default('storage/images/users/no_photo.png');                      
			
			$table->timestamps();

			$table->foreign('user_id')
				->references('userid')
				->on('user')
				->onDelete('cascade') 
				->onUpdate('cascade');  
				
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('pe2_users');
	}
}
