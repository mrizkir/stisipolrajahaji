<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

//feeder job class
use App\Jobs\FeederJob;

class Kernel extends ConsoleKernel
{
	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		
		//job feeder
    $schedule->job(new FeederJob, 'feeder')
      ->everyMinute()
      ->withoutOverlapping();
    
    //job queue feeder
    $schedule->command('queue:work --stop-when-empty --queue=feeder')
    ->everyMinute()
    ->withoutOverlapping();    
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__.'/Commands');

		require base_path('routes/console.php');
	}
	/**
   * Get the timezone that should be used by default for scheduled events.
   *
   * @return \DateTimeZone|string|null
   */
  protected function scheduleTimezone()
  {
    return 'Asia/Jakarta';
  }
}
