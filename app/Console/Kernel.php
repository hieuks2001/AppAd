<?php

namespace App\Console;

use App\Console\Commands\CleanLogData;
use App\Console\Commands\UsersResetMissionCount;
use App\Console\Commands\ClearCodes;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
  /**
   * The Artisan commands provided by your application.
   *
   * @var array
   */
  protected $commands = [
    UsersResetMissionCount::class,
    ClearCodes::class,
    CleanLogData::class,
  ];

  /**
   * Define the application's command schedule.
   *
   * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
   * @return void
   */
  protected function schedule(Schedule $schedule)
  {
    // $schedule->command('inspire')->hourly();
    $schedule->command('code:clear')->dailyAt('00:05');
    $schedule->command('log:clear')->dailyAt('00:10');
  }

  /**
   * Register the commands for the application.
   *
   * @return void
   */
  protected function commands()
  {
    $this->load(__DIR__ . '/Commands');

    require base_path('routes/console.php');
  }
}
