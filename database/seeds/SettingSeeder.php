<?php

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Setting::truncate();

    $minimumReward = new Setting(
      ["name" => "minimum_reward", "value" => "6900"] //0.3*23000
    );
    $minimumReward->save();

    $delayDayWeek = new Setting(
      ["name" => "delay_day_week", "value" => "1"]
    );
    $delayDayWeek->save();

    $delayDayMonth = new Setting(
      ["name" => "delay_day_month", "value" => "1"]
    );
    $delayDayMonth->save();

    $commissionRateLv1 = new Setting(
      ["name" => "commission_rate_1", "value" => "30"]
    );
    $commissionRateLv1->save();

    $commissionRateLv2 = new Setting(
      ["name" => "commission_rate_2", "value" => "1"]
    );
    $commissionRateLv2->save();

    $maxRefUserPerDay = new Setting(
      ["name" => "max_ref_user_per_day_week", "value" => "2"]
    );
    $maxRefUserPerDay->save();

    $maxRefUserPerDayMonth = new Setting(
      ["name" => "max_ref_user_per_day_month", "value" => "2"]
    );
    $maxRefUserPerDayMonth->save();

    $refUserRequiredWeek = new Setting(
      ["name" => "ref_user_required_week", "value" => "6"]
    );
    $refUserRequiredWeek->save();

    $refUserRequiredMonth = new Setting(
      ["name" => "ref_user_required_month", "value" => "24"]
    );
    $refUserRequiredMonth->save();
  }
}
