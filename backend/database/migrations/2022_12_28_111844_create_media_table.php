<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
  public function up()
  {
    Schema::create('pe3_media', function (Blueprint $table) {
      $table->bigIncrements('id');

      $table->morphs('model');
      $table->uuid('uuid')->nullable()->unique();
      $table->string('collection_name');
      $table->string('name');
      $table->string('file_name');
      $table->string('mime_type')->nullable();
      $table->string('disk');
      $table->string('conversions_disk')->nullable();
      $table->unsignedBigInteger('size');
      $table->json('manipulations');
      $table->json('custom_properties');
      $table->json('generated_conversions');
      $table->json('responsive_images');
      $table->unsignedInteger('order_column')->nullable()->index();

      $table->nullableTimestamps();
    });
    Schema::create('pe3_media_library_models', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->nullableTimestamps();
    });
  }
  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('pe3_media_library_models');
    Schema::drop('pe3_media');
  }
}
