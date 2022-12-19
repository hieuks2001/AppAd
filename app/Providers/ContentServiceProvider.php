<?php

namespace App\Providers;

use App\Constants\MissionStatusConstants;
use App\Constants\OntimeTypeConstants;
use App\Constants\PagePriorityConstants;
use App\Constants\PageStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\Mission;
use App\Models\Notification;
use App\Models\Page;
use Carbon\Carbon;
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
      ->simplePaginate(
        $perPage = 10, $columns = array(
          DB::raw('Date(created_at) as date'), DB::raw('sum(reward) as mission_reward')
        )
      );
      $view->with('statistical', $statistical);
    });
    view()->composer('box.patternBox1', function ($view) {
      $user = Auth::user();
      $money = [];
      $money["income"] = DB::table("log_mission_transactions")->where(["user_id" => $user->id, "type" => TransactionTypeConstants::REWARD])->sum("amount");
      $money["balance"] = $user->wallet;
      $money["commission"] = 0;
      // $money["sum"] = $user->wallet + $money["balance"] + $money["commission"];
      $money["sum"] = $money["income"] + $money["commission"];
      $view->with('money', $money);
    });
    view()->composer('box.patternBox2', function ($view) {
      $user = Auth::user();
      $trafficQuery = Page::where("user_id", $user->id);
      $boughtPage = (clone $trafficQuery)->where("status", PageStatusConstants::APPROVED)->pluck("id");
      $traffic = [];
      $traffic["bought"] = count($boughtPage);
      $traffic["sum"] = (clone $trafficQuery)->count();
      $traffic["totalCharge"] = (clone $trafficQuery)->where("status", PageStatusConstants::APPROVED)->sum("price");
      $traffic["remaining"] = $traffic["totalCharge"]  - Mission::whereIn("page_id", $boughtPage)->where("status", MissionStatusConstants::COMPLETED)->sum("reward");
      $view->with('traffic', $traffic);
    });
    view()->composer('box.patternBox3', function ($view) {
      $user = Auth::user();
      $money = [];
      $money["balance"] = $user->wallet;
      $money["withdrawing"] = DB::table("log_mission_transactions")->where(["user_id" => $user->id, "type" => TransactionTypeConstants::WITHDRAW, "status" => 0])->sum("amount");
      $money["withdrawed"] = DB::table("log_mission_transactions")->where(["user_id" => $user->id, "type" => TransactionTypeConstants::WITHDRAW, "status" => 1])->sum("amount");
      $view->with('money', $money);
    });
    view()->composer('regispage.index', function ($view) {
      $onsite = PageType::all();
      $view->with('onsite', $onsite);
    });
    view()->composer('admin.editTraffic', function ($view) {
      $onsite = PageType::all();
      $priority = new ReflectionClass(PagePriorityConstants::class);
      $view->with('priority', $priority->getConstants());
      $view->with('onsite', $onsite);
    });
    view()->composer('admin.users', function ($view) {
      $types = UserType::get();
      $pageTypes = PageType::all()->sortBy('name');
      $view->with(['user_types' => $types, "page_types" => $pageTypes]);
    });
    view()->composer('mission.mission', function ($view) {
      $missions = Mission::where('user_id', Auth::user()->id)
        ->orderBy('created_at', 'desc')
        ->simplePaginate(10);
      $view->with('missions', $missions);
    });
    view()->composer('notification', function ($view) {
      // $now = Carbon::parse(Carbon::now()->format("Y-m-d"));
      $now = Carbon::now();
      $notification = [];
      $isWeekend = $now->isWeekend();
      $isLastMonth = $now->isLastOfMonth();
      // 4 TH
      // TH1: X ko phải là cuối tuần -> []
      // TH2: X là cuối tuần nhưng ko phải là cuối tháng -> ["week"=>msg]
      // TH3: X là cuối tháng nhưng ko phải là cuối tuần -> ["month"=>msg]
      // TH4: X là cuối tuần và cuối tháng (đặc biệt) -> ["month"=>msg,"week"=>msg]
      if ($isWeekend) {
        # code...
        // Check đã đạt điều kiện tuần ở đây
        $weekNoti = Notification::where(["user_id" => Auth::user()->id])->whereDate("created_at", $now->endOfWeek())->first();
        // true -> ['week'=>msg]
        // false -> [] or ko làm gì cả
        if ($weekNoti){
          $notification['week'] = $weekNoti->content;
        }
      }
      if ($isLastMonth) {
        # code...
        // Check đã đạt điều kiện tháng ở đây
            // true -> ['month'=>msg]
            // false -> [] or ko làm gì cả
        $monthNoti = Notification::where(["user_id" => Auth::user()->id])->whereDate("created_at", $now->endOfWeek())->orderBy("created_at", "desc")->first();
        // true -> ['week'=>msg]
        // false -> [] or ko làm gì cả
        if ($monthNoti){
          $notification['month'] = $monthNoti->content;
        }
      }
      $view->with('notification', $notification);
    });
    view()->composer('usdt.withdraw', function ($view) {
      $data = DB::table('log_mission_transactions')
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
