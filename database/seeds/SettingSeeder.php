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
        ["name" => "minimum_reward", "value" => "20"]
      );
      $minimumReward->save();

      $delayDay = new Setting(
        ["name" => "delay_day", "value" => "1"]
      );
      $delayDay->save();
    }
}
