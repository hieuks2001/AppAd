<?php

namespace App\Providers;

use App\Constants\MissionStatusConstants;
use App\Constants\OntimeTypeConstants;
use App\Constants\PagePriorityConstants;
use App\Constants\PageStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\Mission;
use App\Models\Page;
use App\Models\PageType;
use App\Models\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class ContentServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    //
    view()->composer('dashboard.index', function ($view) {
      $user = Auth::user();
      $statistical = Mission::where(['user_id' => $user->id, 'status' => MissionStatusConstants::COMPLETED])
        ->groupBy('date')
        ->orderBy('date', 'DESC')
        ->limit(7)
        ->get(
          array(
            DB::raw('Date(created_at) as date'), DB::raw('sum(reward) as mission_reward')
          )
        );
      $view->with('statistical', $statistical);
    });
    view()->composer('box.patternBox1', function ($view) {
      $user = Auth::user();
      $money = [];
      $money["income"] = DB::table("log_traffic_transactions")->where(["user_id" => $user->id, "type" => TransactionTypeConstants::REWARD])->sum("amount");
      $money["balance"] = $user->wallet;
      $money["commission"] = 0;
      $money["sum"] = $user->wallet + $money["balance"] + $money["commission"];
      $view->with('money', $money);
    });
    view()->composer('box.patternBox2', function ($view) {
      $user = Auth::user();
      $trafficQuery = Page::where("user_id", $user->id);
      $boughtPage = (clone $trafficQuery)->where("status", PageStatusConstants::APPROVED)->pluck("id");
      $traffic = [];
      $traffic["bought"] = count($boughtPage);
      $traffic["balance"] = $user->wallet;
      $traffic["sum"] = (clone $trafficQuery)->count();
      $traffic["totalCharge"] = (clone $trafficQuery)->where("status", PageStatusConstants::APPROVED)->sum("price");
      $traffic["remaining"] = $traffic["totalCharge"]  - Mission::whereIn("page_id", $boughtPage)->where("status", MissionStatusConstants::COMPLETED)->sum("reward");
      $view->with('traffic', $traffic);
    });
    view()->composer('box.patternBox3', function ($view) {
      $user = Auth::user();
      $money = [];
      $money["balance"] = $user->wallet;
      $money["withdrawing"] = DB::table("log_traffic_transactions")->where(["user_id" => $user->id, "type" => TransactionTypeConstants::WITHDRAW, "status" => 0])->sum("amount");
      $money["withdrawed"] = DB::table("log_traffic_transactions")->where(["user_id" => $user->id, "type" => TransactionTypeConstants::WITHDRAW, "status" => 1])->sum("amount");
      $view->with('money', $money);
    });
    view()->composer('box.patternBox4', function ($view) {
      $user = Auth::user();
      $money = [];
      $money["balance"] = $user->wallet;
      $money["withdrawing"] = DB::table("log_traffic_transactions")->where(["user_id" => $user->id, "type" => TransactionTypeConstants::TOPUP, "status" => 0])->sum("amount");
      $money["withdrawed"] = DB::table("log_traffic_transactions")->where(["user_id" => $user->id, "type" => TransactionTypeConstants::TOPUP, "status" => 1])->sum("amount");
      $view->with('money', $money);
    });
    view()->composer('regispage.index', function ($view) {
      $onsite = PageType::all()->sortBy('name');
      $view->with('onsite', $onsite);
    });
    view()->composer('admin.editTraffic', function ($view) {
      $onsite = PageType::all()->sortBy('name');
      $priority = new ReflectionClass(PagePriorityConstants::class);
      $view->with(['priority' => $priority->getConstants(), 'onsite' => $onsite]);
    });
    view()->composer('admin.users', function ($view) {
      $types = UserType::get();
      $pageTypes = PageType::all()->sortBy('name');
      $view->with(['user_types' => $types, "page_types" => $pageTypes]);
    });
    view()->composer('mission.mission', function ($view) {
      $missions = Mission::where('user_id', Auth::user()->id)
        ->orderBy('updated_at', 'desc')
        ->limit(10)
        ->get();
      $view->with('missions', $missions);
    });
    view()->composer('usdt.deposit', function ($view) {
      $data = DB::table('log_traffic_transactions')
        ->where([
          'user_id' => Auth::user()->id,
          'type' => TransactionTypeConstants::TOPUP
        ])
        ->orderBy("updated_at", "DESC")
        ->simplePaginate(10);
      $view->with('data', $data);
    });
    view()->composer('usdt.withdraw', function ($view) {
      $data = DB::table('log_traffic_transactions')
        ->where([
          'user_id' => Auth::user()->id,
          'type' => TransactionTypeConstants::WITHDRAW
        ])
        ->orderBy("updated_at", "DESC")
        ->simplePaginate(10);
      $view->with('data', $data);
    });
  }
}
