<?php

namespace App\Models;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;
class MediaModel extends BaseMedia
{
  protected $table = 'pe3_media';
}