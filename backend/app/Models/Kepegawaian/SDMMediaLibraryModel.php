<?php

namespace App\Models\Kepegawaian;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SDMMediaLibraryModel extends Model implements HasMedia
{
  use InteractsWithMedia;

  protected $table = 'pe3_media_library_models';

  protected $fillable = [
    'id',   
  ];

  /**
	 * activated timestamps.
	 *
	 * @var string
	*/
	public $timestamps = true;
  
  public function registerMediaConversions(Media $media = null): void
  {
    $this->addMediaConversion('thumb')
      ->width(350)
      ->height(250);
  }
}