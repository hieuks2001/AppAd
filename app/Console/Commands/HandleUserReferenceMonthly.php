<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HandleUserReferenceMonthly extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'user:update-month';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
  }

  public function line($string, $style = null, $verbosity = null)
  {
      $timestamped = date('[Y-m-d H:i:s] ') . ucfirst($style) . ': ' . $string;

      $styled = $style ? "<$style>$timestamped</$style>" : $timestamped;

      $this->output->writeln($styled, $this->parseVerbosity($verbosity));
  }

  /**
   * Execute the console command.
   *
   * @return int
   */
  public function handle()
  { // Seperate line weekly
    // Run weekly on sunday -> Check from Mon to Sat

    // Create dates array contain all day in month (Y-m-d)
    $now = Carbon::now()->subMonth(); // Previous month
    $dates = array();
    for ($i = 1; $i <= 4; $i++) {
      $start = $now->endOfMonth()->subWeeks($i)->format("Y-m-d");
      if ($i == 4) {
        $start = $now->startOfMonth()->format("Y-m-d");
      }
      $end = $now->endOfMonth()->format("Y-m-d");
      $week = CarbonPeriod::create($start, $end)->toArray();
      $week = array_map(function ($date) {
        return $date->format("Y-m-d");
      }, $week);

      array_push($dates, $week);
    }
    $minimumReward = Setting::where("name", "minimum_reward")->first();
    $delayDay = Setting::where("name", "delay_day_month")->first();
    $maxUserPerDay = Setting::where("name", "max_ref_user_per_day_month")->first();
    $this->info("Checking from $start to $end");
    User::chunkById(200, function ($users) use ($dates, $minimumReward, $delayDay, $maxUserPerDay) {
      // Loop each user in 200 users
      foreach ($users as $user) {
        echo "\n====================================================================\n";
        $this->line("Checking $user->username");
        $count = 0;
        for ($i = 1; $i <= count($dates); $i++) {
          $week = $dates[$i - 1];
          if (checkUserReference($user->id, current($week), end($week), 6 * $i, (float)$minimumReward->value, (int)$delayDay->value, (int)$maxUserPerDay->value)) {
            $count++;
          }
        }
        if ($count >= 4) {
          // echo "\nUser $user->username dat du dieu kien tach line theo thang - Tien hanh tach line.\n";
          $this->info("User $user->username dat du dieu kien tach line theo thang - Tien hanh tach line.");
          $this->removeReference($user->id);
        } else {
          $this->error("User $user->username khong du dieu kien tach line theo thang.");
        }
      }
    }, $column = "id");
  }

  public function removeReference($userId)
  {
    $u = User::where("id", $userId)->first();
    if (!$u){
      return;
    }

    $u->reference = null;
    $u->save();
    $this->info("$u->username da tach line xong.");
    return;
  }
}
