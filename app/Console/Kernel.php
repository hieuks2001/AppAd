<?php

namespace App\Console;

use App\Console\Commands\UsersResetMissionCount;
use App\Console\Commands\HandleUserReferenceMonthly;
use App\Console\Commands\HandleUserReferenceWeekly;
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
        HandleUserReferenceMonthly::class,
        UsersResetMissionCount::class,
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
        $schedule->command('user:update-week')->weeklyOn(7, '00:10')->timezone('Asia/Ho_Chi_Minh');
        $schedule->command('user:update-month')->monthlyOn(1, '00:15')->timezone('Asia/Ho_Chi_Minh');
        $schedule->command('mission:reset')->dailyAt('00:05')->timezone('Asia/Ho_Chi_Minh');
        $schedule->command('telegram:report')->dailyAt('23:55')->timezone('Asia/Ho_Chi_Minh');
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
}
