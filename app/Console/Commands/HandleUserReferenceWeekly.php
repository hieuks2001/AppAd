<?php

namespace App\Console\Commands;

use App\Constants\TransactionTypeConstants;
use App\Models\LogTransaction;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HandleUserReferenceWeekly extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'user:update-week';

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
  {
    // Seperate line weekly
    // Run weekly on sunday -> Check from Mon to Sat

    // Create dates array contain all day in week (Y-m-d)
    $now = Carbon::now();
    $start = $now->startOfWeek()->format("Y-m-d");
    $end = $now->endOfWeek(Carbon::SATURDAY)->format("Y-m-d");
    $minimumReward = Setting::where("name", "minimum_reward")->first();
    $delayDay = Setting::where("name", "delay_day")->first();
    echo ("From $start to $end");
    User::chunkById(200, function ($users) use ($start, $end, $minimumReward, $delayDay) {
      // Loop each user in 200 users
      foreach ($users as $user) {
        echo "\n====================================================================\n";
        $this->line("Checking $user->username");
        if (checkUserReference($user->id, $start, $end, 6, (float)$minimumReward->value, (int)$delayDay->value)) {
          $this->info("User $user->username dat du dieu kien an tron theo tuan (week) - Tien hanh an tron");
          $lv1Referrer = User::where("id", $user->reference)->first();
          if (!$lv1Referrer) {
            continue;
          }
          // Return User's 30% commission
          $this->returnCommission($user->id, $lv1Referrer->id);

          if ($lv1Referrer->reference) {
            $lv2Referrer = User::where("id", $lv1Referrer->reference)->first();
            if ($lv2Referrer) {
              $this->giveCommission($user->id);
            }
          }
        } else {
          $this->error("User $user->username khong du dieu kien an tron theo tuan (week)");
          $this->giveCommission($user->id);
        }
      }
    }, $column = "id");
    return 0;
  }

  function returnCommission($userId, $referId)
  {
    // Return the user's referral commission
    $pendingCommision = LogTransaction::where([
      "user_id" => $referId,
      "from_user_id" => $userId,
      "status" => 0,
      "type" => TransactionTypeConstants::COMMISSION,
    ])->get(["amount", "id", "status"]);
    // Begin transaction for money!!!
    foreach ($pendingCommision as $value) {
      DB::transaction(function () use ($value, $userId) {
        DB::table("user_missions")->where("id", $userId)->increment("wallet", $value->amount);
        $value->status = -1; // Canceled - Mean give back to user
        $value->save();
      });
    }
  }

  function giveCommission($userId)
  {
    // Give commission to referrer
    $pendingCommision = LogTransaction::where([
      "from_user_id" => $userId,
      "status" => 0,
      "type" => TransactionTypeConstants::COMMISSION,
    ])->get(["amount", "id", "status", "user_id"]);

    if ($pendingCommision->count() == 0) {
      return;
    }
    // Begin transaction for money!!!
    foreach ($pendingCommision as $value) {
      DB::transaction(function () use ($value) {
        // echo ("User_id >>>> " . $value->user_id . " >>>> " . $value->amount . "\n");
        DB::table("user_missions")->where("id", $value->user_id)->increment("wallet", $value->amount); // ->user_id mean referrer
        $value->status = 1; // Accepted - Mean give to referrer
        $value->save();
      });
    }
  }
}
