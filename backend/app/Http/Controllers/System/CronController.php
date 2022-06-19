<?php

namespace App\Http\Controllers\System;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class CronController extends Controller {      
  public function run()
  {
    Artisan::call('schedule:run');
  }
}