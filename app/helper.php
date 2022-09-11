<?php

use App\Constants\TransactionTypeConstants;
use App\Models\LogTransaction;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

if (!function_exists('checkUserReference')) {

  function checkUserReference($userId, $fromDate, $toDate, $userCount, $minimumReward = 20)
  {
    if ($fromDate instanceof \Carbon\Carbon) {
      $fromDate = $fromDate->format("Y-m-d");
    }

    if ($toDate instanceof \Carbon\Carbon) {
      $toDate = $toDate->foramt("Y-m-d");
    }

    $dates = CarbonPeriod::create($fromDate, $toDate)->toArray();
    $dates = array_map(function ($date) {
      return $date->format("Y-m-d");
    }, $dates);
    $user = User::where("id", $userId)->first();
    if (!$user) {
      return false;
    }

    if (!isset($user->reference) or !$user->reference) { // Already seperate line from commision system or No referrer
      echo "Already seperate line from commision system or No referer\n";
      return false;
    }
    // Get User list
    // $refUsers = User::where("reference", $user->id)->get(["id"]); // return list id
    $refUsers = User::where("reference", $user->id)
      ->whereBetween("created_at", [
        $fromDate,
        $toDate,
      ])
      ->get();
    if ($refUsers->count() < $userCount) {
      return false;
    }
    $acceptedUserCount = 0;
    foreach ($refUsers as $refUser) {
      // Query all missions are done by reference user
      $logs = LogTransaction::where([
        "user_id" => $refUser->id,
        "type" => TransactionTypeConstants::REWARD,
        "status" => 1,
      ])->whereBetween("created_at", [
        $fromDate,
        $toDate,
      ])->groupBy("date")->orderBy("date")
        ->get(array(
          DB::raw("Date(created_at) as date"),
          DB::raw("SUM(amount) as amount"),
        ));
      // Check if user is active everyday
      $count = 0;
      $newDates = array_slice($dates, array_search($refUser->created_at->format("Y-m-d"), $dates));
      foreach ($logs as $date) {
        if ($date->amount >= $minimumReward) { // Reward more than $minimumReward per day
          if (in_array($date->date, $newDates)) {
            $count++;
          }
        }
      }
      // Check if all day in week are rewarded more than 20 usdt
      // Skip 1 day
      if ($count >= count($newDates) - 1) {
        $acceptedUserCount++;
      }

      if ($acceptedUserCount >= $userCount) {
        return true;
      }
    }
    return false;
  }
}
