<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

//job feeder
use App\Jobs\Feeder;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    //
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    //job feeder
    $schedule->job(new Feeder, 'feeder')
      ->everyMinute()
      // ->hourly()
      ->sendOutputTo(storage_path() . '/logs/laravel.log')      
      ->withoutOverlapping();
    
    //job queue feeder
    $schedule->command('queue:work --stop-when-empty --queue=feeder')
    ->everyMinute()
    ->withoutOverlapping();    
    
  }
}
