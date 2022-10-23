<?php

namespace App\Http\Controllers;

use App\Constants\MissionStatusConstants;
use App\Constants\PageStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogTransaction;
use App\Models\Mission;
use App\Models\Missions;
use App\Models\Page;
use App\Models\PageType;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevController extends Controller
{
  public function GetRandomWeightedElement(array $weightedValues)
  {
    $rand = mt_rand(1, (int) array_sum($weightedValues));

    foreach ($weightedValues as $key => $value) {
      $rand -= $value;
      if ($rand <= 0) {
        return $key;
      }
    }
  }
  public function UpdateUserType()
  {
    $user = User::where("username", "User A")->first();
    // $type = PageType::whereBetween('mission_need', [$user->mission_count, PageType::max('mission_need')])
    //   ->orderBy('mission_need', 'asc')->first();
    $types = PageType::orderBy('name', 'asc')->pluck('id');
    // $uType = $user->userType;
    $uType = UserType::where("id", $user->user_type_id)->first();
    $uMission = $user->mission_count;
    $condition = $uType->mission_need;
    $pageWeight = $uType->page_weight;
    $result = array();
    foreach ($types as $key => $typeId) {
      if (array_key_exists($typeId, $uMission) && array_key_exists($typeId, $condition)) {
        $result[$typeId] = $pageWeight[$typeId];
        if ($uMission[$typeId] < $condition[$typeId]) {
          return $result;
        }
      }
    }
    if (empty($result)) {
      // No page type found => pick page Loai '1'
      $result[$types[0]] = $uType->page_weight[$types[0]];
      return $result;
    }
    // Meet all mission requirements => return all page weight
    return $pageWeight;
  }

  public function getMission()
  {
    // Update UserType
    $pageType = $this->UpdateUserType();
    $excludePriority = [];
    $user = User::where("username", "User A")->first();
    $mission = Mission::where('user_id', $user->id)
      ->where('status', MissionStatusConstants::DOING)
      ->orderBy('created_at', 'desc')->first();
    if ($mission) { // There is mission existed!
      $page = Page::where('id', $mission->page_id)
        ->where('status', PageStatusConstants::APPROVED)->first();
      return $page->keyword;
    }

    // NO MISSION CURRENTLY DOING
    $pickedPage = null;

    while (count($pageType) > 0 and !$pickedPage) {
      // Get random page type id base on weights
      $pageTypeId = $this->GetRandomWeightedElement($pageType);
      $pageQuery = Page::query();
      // Get all id of page_type have mission_need <= current user page_type
      // $pageTypeIdArr = PageType::where('mission_need', '<=', $pageType->mission_need)->pluck('id');
      // Add conditions
      $pageQuery->where('traffic_remain', '>', 0)
        ->where('status', PageStatusConstants::APPROVED)
        // ->whereIn('page_type_id', $pageTypeIdArr);
        ->where('page_type_id', $pageTypeId)
        ->whereNotIn('priority', $excludePriority);

      $pages = clone ($pageQuery)->get();
      if ($pages->isEmpty()) { // If there is no pages available
        // return view('mission.mission', [])->withErrors('Không còn nhiệm vụ, vui lòng quay lại sau!');
        unset($pageType[$pageTypeId]); // remove this page type id
        continue;
      }

      // If there are pages available
      // pick random page from pages
      $pages = $pages->shuffle();
      $pickedPage = $pages[0];

      if (!$pickedPage) {
        array_push($excludePriority, $pages[0]->priority);
      }
    }

    if (!$pickedPage) {
      // No page available -> comback later
      return "null";
    }
    // Begin database transaction
    DB::transaction(function () use ($pickedPage, $user) {
      // Refresh data
      $pickedPage = $pickedPage->refresh();

      $newMission = new Mission();
      $newMission->page_id = $pickedPage->id;
      $newMission->user_id = $user->id;
      // Reward = (price - 10% ) / traffic_sum
      $newMission->reward = ($pickedPage->price - ($pickedPage->price * $pickedPage->hold_percentage / 100)) / $pickedPage->traffic_sum;
      $newMission->status = MissionStatusConstants::DOING;
      $newMission->ip = "localhost";
      $newMission->user_agent = "";
      $newMission->origin_url = "";
      $newMission->save();
      $pickedPage->traffic_remain -= 1;
      $pickedPage->save();
    });
    return $pickedPage->keyword;
  }

  public function completeMission()
  {
    $user = User::where("username", "User A")->first();
    //rule here
    $ms = Missions::where('user_id', $user->id)->where([
      // ["ip", $uIP],
      ["status", MissionStatusConstants::DOING]
    ])->first();
    if (!$ms) {
      return $user->mission_count;
    }
    $uMsCount = $user->mission_count;
    $pageTypeId = Page::where('id', $ms->page_id)->get('page_type_id')->first();
    // Update mission count base on Type of page buy traffic.
    if (!array_key_exists($pageTypeId->page_type_id, $uMsCount)) {
      $uMsCount[$pageTypeId->page_type_id] = 1;
    } else {
      $uMsCount[$pageTypeId->page_type_id] += 1;
    }
    $user->mission_count = $uMsCount;
    $user->save();

    $ms->status = 1;
    $ms->save();
    return $user->mission_count;
  }

  public function createUserTest()
  {
    $type =  UserType::where('is_default', 1)->get('id')->first();
    $user = new User();
    // $user->username = "Test";
    // $user->password = bcrypt("12341234");
    // $user->is_admin = 0;
    // $user->status = 0; // Set status to inactive / unverfied
    // $user->wallet = 0;
    // $user->verified = 1;
    // $user->user_type_id = $type->id;
    // $user->commission = 0;
    // // $user->reference = $input['reference'];
    // $user->save();
    return $user->id;
  }

  public function createTestMissison()
  {
    Page::truncate();
    $type1 = PageType::where("name", "1")->first();
    $type2 = PageType::where("name", "2")->first();
    $type3 = PageType::where("name", "3")->first();
    $user = DB::table("user_traffics")->where("username", "Test")->first();

    // Create page
    $page = new Page();
    $page->user_id = $user->id;
    $page->keyword = "Page Loai 1 a";
    $page->image = "";
    $page->url = "";
    $page->traffic_per_day = 100;
    $page->traffic_sum = 100;
    $page->onsite = 100;
    $page->status = 1;
    $page->traffic_remain = 100;
    $page->page_type_id = $type1->id;
    $page->price_per_traffic = 0;
    $page->price = 0;
    $page->save();


    $page2 = new Page();
    $page2->user_id = $user->id;
    $page2->keyword = "Page Loai 1 b";
    $page2->image = "";
    $page2->url = "";
    $page2->traffic_per_day = 100;
    $page2->traffic_sum = 100;
    $page2->onsite = 100;
    $page2->status = 1;
    $page2->traffic_remain = 100;
    $page2->page_type_id = $type1->id;
    $page2->price_per_traffic = 0;
    $page2->price = 0;
    $page2->save();

    $page3 = new Page();
    $page3->user_id = $user->id;
    $page3->keyword = "Page Loai 2 a";
    $page3->image = "";
    $page3->url = "";
    $page3->traffic_per_day = 100;
    $page3->traffic_sum = 100;
    $page3->onsite = 100;
    $page3->status = 1;
    $page3->traffic_remain = 100;
    $page3->page_type_id = $type2->id;
    $page3->price_per_traffic = 0;
    $page3->price = 0;
    $page3->save();

    $page4 = new Page();
    $page4->user_id = $user->id;
    $page4->keyword = "Page Loai 2 b";
    $page4->image = "";
    $page4->url = "";
    $page4->traffic_per_day = 100;
    $page4->traffic_sum = 100;
    $page4->onsite = 100;
    $page4->status = 1;
    $page4->traffic_remain = 100;
    $page4->page_type_id = $type2->id;
    $page4->price_per_traffic = 0;
    $page4->price = 0;
    $page4->save();

    $pageX = new Page();
    $pageX->user_id = $user->id;
    $pageX->keyword = "Page Loai 3 a";
    $pageX->image = "";
    $pageX->url = "";
    $pageX->traffic_per_day = 100;
    $pageX->traffic_sum = 100;
    $pageX->onsite = 100;
    $pageX->status = 1;
    $pageX->traffic_remain = 100;
    $pageX->page_type_id = $type3->id;
    $pageX->price_per_traffic = 0;
    $pageX->price = 0;
    $pageX->save();

    $pageY = new Page();
    $pageY->user_id = $user->id;
    $pageY->keyword = "Page Loai 3 b";
    $pageY->image = "";
    $pageY->url = "";
    $pageY->traffic_per_day = 100;
    $pageY->traffic_sum = 100;
    $pageY->onsite = 100;
    $pageY->status = 1;
    $pageY->traffic_remain = 100;
    $pageY->page_type_id = $type3->id;
    $pageY->price_per_traffic = 0;
    $pageY->price = 0;
    $pageY->save();
  }

  public function createLog($user, $reward, $createdAt)
  {
    $lv1 = User::where('id', $user->reference)->first();
    if ($lv1) {
      $lv1Commission = $reward * 30 / 100; // (Get 30%)
      $oldReward = $reward;
      $reward -= $lv1Commission;
      //
      $logLV1 = new LogTransaction([
        'amount' => $lv1Commission,
        'user_id' => $lv1->id,
        'from_user_id' => $user->id,
        'type' => TransactionTypeConstants::COMMISSION,
        'status' => 0, // pending -> Update later on weekend?
        'created_at' => $createdAt
      ]);
      $logLV1->save();
      if ($lv1->reference) {
        $lv2 = User::where('id', $lv1->reference)->first();
        if ($lv2) {
          $lv2Commission = $oldReward * 1 / 100; // (Get 1%)
          $reward -= $lv2Commission;
          //
          $logLV2 = new LogTransaction([
            'amount' => $lv2Commission,
            'user_id' => $lv2->id,
            'from_user_id' => $user->id,
            'type' => TransactionTypeConstants::COMMISSION,
            'status' => 0, // pending -> update later on weekend?
            'created_at' => $createdAt,
          ]);
          $logLV2->save();
        }
      }
    }
    // Create log
    $log = new LogTransaction([
      'amount' => $reward,
      'user_id' => $user->id,
      'type' => TransactionTypeConstants::REWARD,
      'status' => 1, // auto Accept
      'created_at' => $createdAt
    ]);
    $log->save();
  }

  public function genLog(Request $request)
  {
    $users = User::all();
    $now = Carbon::now();
    $start = $now->startOfWeek()->format("Y-m-d");
    $end = $now->endOfWeek(Carbon::SATURDAY)->format("Y-m-d");
    $dates = CarbonPeriod::create($start, $end)->toArray();
    // $dates = array_map(function ($date) {
    //   return $date->format("Y-m-d");
    // }, $dates);
    // $user = Auth::user();
    foreach ($users as $user) {
      foreach ($dates as $date) {
        $reward = 30;
        // Get up to 1 level user reference
        $lv1 = User::where('id', $user->reference)->first();
        if ($lv1) {
          $lv1Commission = $reward * 30 / 100; // (Get 30%)
          $oldReward = $reward;
          $reward -= $lv1Commission;
          //
          $logLV1 = new LogTransaction([
            'amount' => $lv1Commission,
            'user_id' => $lv1->id,
            'from_user_id' => $user->id,
            'type' => TransactionTypeConstants::COMMISSION,
            'status' => 0, // pending -> Update later on weekend?
            'created_at' => $date
          ]);
          $logLV1->save();
          if ($lv1->reference) {
            $lv2 = User::where('id', $lv1->reference)->first();
            if ($lv2) {
              $lv2Commission = $oldReward * 1 / 100; // (Get 1%)
              $reward -= $lv2Commission;
              //
              $logLV2 = new LogTransaction([
                'amount' => $lv2Commission,
                'user_id' => $lv2->id,
                'from_user_id' => $user->id,
                'type' => TransactionTypeConstants::COMMISSION,
                'status' => 0, // pending -> update later on weekend?
                'created_at' => $date,
              ]);
              $logLV2->save();
            }
          }
        }
        // Create log
        $log = new LogTransaction([
          'amount' => $reward,
          'user_id' => $user->id,
          'type' => TransactionTypeConstants::REWARD,
          'status' => 1, // auto Accept
          'created_at' => $date
        ]);
        $log->save();
      }
    }
  }

  public function createUser($username, $reference, $createdAt)
  {
    $type =  UserType::where('is_default', 1)->get('id')->first();
    $user = new User();
    $user->username = $username;
    $user->password = bcrypt("12341234");
    $user->is_admin = 0;
    $user->status = 1; // Set status to inactive / unverfied
    $user->wallet = 0;
    $user->verified = 1;
    $user->user_type_id = $type->id;
    $user->commission = 0;
    $user->reference = $reference;
    $user->created_at = $createdAt;
    $user->save();
  }

  public function createUserMonth()
  {
    User::truncate();
    $now = Carbon::now()->subMonth();
    $start = $now->startOfMonth()->format("Y-m-d");
    $end = $now->endOfMonth()->format("Y-m-d");

    $dates = CarbonPeriod::create($start, $end)->toArray();
    $count = 1;

    $type =  UserType::where('is_default', 1)->get('id')->first();

    $userRoot = new User();
    $userRoot->username = "root";
    $userRoot->password = bcrypt("12341234");
    $userRoot->is_admin = 0;
    $userRoot->status = 1; // Set status to inactive / unverfied
    $userRoot->wallet = 0;
    $userRoot->verified = 1;
    $userRoot->user_type_id = $type->id;
    $userRoot->commission = 0;
    $userRoot->created_at = $start;
    $userRoot->save();

    $userA = new User();
    $userA->username = "User A";
    $userA->password = bcrypt("12341234");
    $userA->is_admin = 0;
    $userA->status = 1; // Set status to inactive / unverfied
    $userA->wallet = 0;
    $userA->verified = 1;
    $userA->user_type_id = $type->id;
    $userA->commission = 0;
    $userA->reference = $userRoot->id;
    $userA->created_at = $start;
    $userA->save();
    foreach ($dates as $createdAt) {
      $this->createUser("Ref A - " . $count, $userA->id, $createdAt);
      $count++;
    }
    dd($count);
  }

  public function createUserWeek()
  {
    User::truncate();
    $now = Carbon::now();
    $start = $now->startOfWeek()->format("Y-m-d");
    $end = $now->endOfWeek()->format("Y-m-d");

    $dates = CarbonPeriod::create($start, $end)->toArray();
    $count = 1;

    $type =  UserType::where('is_default', 1)->get('id')->first();

    $userRoot = new User();
    $userRoot->username = "root";
    $userRoot->password = bcrypt("12341234");
    $userRoot->is_admin = 0;
    $userRoot->status = 1; // Set status to inactive / unverfied
    $userRoot->wallet = 0;
    $userRoot->verified = 1;
    $userRoot->user_type_id = $type->id;
    $userRoot->commission = 0;
    $userRoot->created_at = $start;
    $userRoot->save();

    $userA = new User();
    $userA->username = "User A";
    $userA->password = bcrypt("12341234");
    $userA->is_admin = 0;
    $userA->status = 1; // Set status to inactive / unverfied
    $userA->wallet = 0;
    $userA->verified = 1;
    $userA->user_type_id = $type->id;
    $userA->commission = 0;
    $userA->reference = $userRoot->id;
    $userA->created_at = $start;
    $userA->save();
    foreach ($dates as $createdAt) {
      $this->createUser("Ref A - " . $count, $userA->id, $createdAt);
      $count++;
    }
    dd($count);
  }

  public function createLogMonth()
  {
    LogTransaction::truncate();
    $a = User::where("username", "User A")->first();

    $refUsers = User::where("reference", $a->id)->orderBy("created_at", "desc")->limit(24)->get();
    // $refUsers = User::where("reference", $a->id)->get();

    $now = Carbon::now()->subMonth();
    $start = $now->startOfMonth()->format("Y-m-d");
    $end = $now->endOfMonth()->format("Y-m-d");

    $dates = CarbonPeriod::create($start, $end)->toArray();
    $dates = array_map(function ($date) {
      return $date->format("Y-m-d");
    }, $dates);

    foreach ($refUsers as $refUser) {
      $newDates = array_slice($dates, array_search($refUser->created_at->format("Y-m-d"), $dates));
      foreach ($newDates as $createdAt) {
        $this->createLog($refUser, 30, $createdAt);
      }
    }
  }

  public function createLogWeek()
  {
    LogTransaction::truncate();
    $a = User::where("username", "User A")->first();

    $refUsers = User::where("reference", $a->id)->get();

    $now = Carbon::now();
    $start = $now->startOfWeek()->format("Y-m-d");
    $end = $now->endOfWeek()->format("Y-m-d");


    $dates = CarbonPeriod::create($start, $end)->toArray();

    foreach ($refUsers as $refUser) {
      $newDates = array_slice($dates, array_search($refUser->created_at->format("Y-m-d"), $dates));
      foreach ($newDates as $createdAt) {
        $this->createLog($refUser, 30, $createdAt);
      }
    }
  }

  public function clearMission(Request $request)
  {
    LogTransaction::truncate();
  }

  public function testMission(Request $request)
  {
    $user = User::where("id", "d880ab18-1adc-4a9a-a302-8e79a6695cbc")->first();
    $now = Carbon::now();
    $start = $now->startOfWeek()->format("Y-m-d");
    $end = $now->endOfWeek(Carbon::SATURDAY)->format("Y-m-d");
    $dates = CarbonPeriod::create($start, $end)->toArray();
    $dates = array_map(function ($date) {
      return $date->format("Y-m-d");
    }, $dates);

    $refUsers = User::where("reference", $user->id)
      ->whereBetween("created_at", [
        $start,
        $end,
      ])
      ->get();
    $acceptedUserCount = 0;
    foreach ($refUsers as $refUser) {
      // Query all missions are done by reference user
      $logs = LogTransaction::where([
        "user_id" => $refUser->id,
        "type" => TransactionTypeConstants::REWARD,
        "status" => 1,
      ])->whereBetween("created_at", [
        $start,
        $end,
      ])->groupBy("date")->orderBy("date")
        ->get(array(
          DB::raw("Date(created_at) as date"),
          DB::raw("SUM(amount) as amount"),
        ));
      dd($logs);
      // Check if user is active everyday
      $newDates = array_slice($dates, array_search($user->created_at->format("Y-m-d"), $dates));
      $count = 0;
      foreach ($logs as $date) {
        if ($date->amount > 20) { // Reward 20 more usdt per day
          if (in_array($date->date, $newDates)) {
            $count++;
          }
        }
      }
      if ($count == count($newDates)) {
        $acceptedUserCount++;
      }
    }
    dd($acceptedUserCount);
  }

  // New test ===============================================================================================================================

  public function missionTodayNewUser(Request $request)
  {

    $request->validate([
      "day" => "required|numeric|min:1|max:31",
      "username" => "required"
    ]);

    $input = $request->all();
    $u = User::where("username", $input["username"])->first();

    if (!$u){
      return ["error" => "User " . $input["username"] ." not found!"];
    }

    $create_at = Carbon::createFromFormat('d', $input["day"]);

    $type =  UserType::where('is_default', 1)->get('id')->first();

    $userA = new User();
    $userA->username = "Ref" . $input["username"] . Carbon::now()->format("Ymds");
    $userA->password = bcrypt("12341234");
    $userA->is_admin = 0;
    $userA->status = 1; // Set status to inactive / unverfied
    $userA->wallet = 0;
    $userA->verified = 1;
    $userA->user_type_id = $type->id;
    $userA->commission = 0;
    $userA->reference = $u->id;
    $userA->created_at = $create_at;
    $userA->updated_at = $create_at;
    $userA->save();

    return ["username" => $userA->username, "password" => "12341234", "created_at" => $create_at->format("d-m-Y")];
  }

  public function missionTodayOldUserDoMission(Request $request)
  {
    $request->validate([
      "miss_number" => "required|numeric",
      "day" => "required|numeric|min:1|max:31",
      "username" => "required"
    ]);

    $input = $request->all();
    $u = User::where("username", $input["username"])->first();
    if (!$u){
      return ["error" => "User " . $input["username"] ." not found!"];
    }
    $create_at = Carbon::createFromFormat('d', $input["day"]);
    $refs = User::where("reference", $u->id)->whereDate("created_at", "<=", $create_at)->inRandomOrder()->get();

    $refs = $refs->slice($input["miss_number"]);

    $count = 0;

    $pageType = PageType::where("name", "1")->first();

    foreach ($refs as $user) {
      // Create mission
      $page = Page::where(["status" => 1, "page_type_id" => $pageType->id])->inRandomOrder()->limit(1)->first();

      $reward = ($page->price - ($page->price * $page->hold_percentage / 100)) / $page->traffic_sum;

      $lv1 = User::where('id', $user->reference)->first();
      if ($lv1) {
        $lv1Commission = $reward * 30 / 100; // (Get 30%)
        $oldReward = $reward;
        $reward -= $lv1Commission;
        // Create log lv1
        $logLV1 = new LogTransaction([
          'amount' => $lv1Commission,
          'user_id' => $lv1->id,
          'from_user_id' => $user->id,
          'type' => TransactionTypeConstants::COMMISSION,
          'status' => 0, // pending -> Update later on weekend?
        ]);
        $logLV1->created_at = $create_at;
        $logLV1->updated_at = $create_at;
        $logLV1->save();
        // If lv1 have reference

        if ($lv1->reference) {
          $lv2 = User::where('id', $lv1->reference)->first();
          if ($lv2) {
            // Create log lv2
            $lv2Commission = $oldReward * 1 / 100; // (Get 1%)
            $reward -= $lv2Commission;
            $logLV2 = new LogTransaction([
              'amount' => $lv2Commission,
              'user_id' => $lv2->id,
              'from_user_id' => $user->id,
              'type' => TransactionTypeConstants::COMMISSION,
              'status' => 0, // pending -> update later on weekend?
            ]);
            $logLV2->created_at = $create_at;
            $logLV2->updated_at = $create_at;
            $logLV2->save();
          }
        }
      }

      $newMission = new Mission();
      $newMission->page_id = $page->id;
      $newMission->user_id = $user->id;
      $newMission->reward = $reward;
      $newMission->status = MissionStatusConstants::DOING;
      $newMission->ip = "1.1.1.1";
      $newMission->user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36";
      $newMission->origin_url = "";
      $newMission->status = 1; // DONE
      $newMission->created_at = $create_at;
      $newMission->updated_at = $create_at;
      $newMission->save();

      $log = new LogTransaction([
        'amount' => $reward,
        'user_id' => $user->id,
        'type' => TransactionTypeConstants::REWARD,
        'status' => 1, // auto Accept
      ]);
      $log->created_at = $create_at;
      $log->updated_at = $create_at;
      $log->save();

      $user->update([
        'wallet' => $user->wallet + $reward,
      ]);
      $count++;
    }
    return ["Missions Created: " => $count];
  }

  public function checkUserUpdateWeek(Request $rq)
  {
    $rq->validate([
      "day" => "numeric|min:1|max:31",
    ]);

    $input = $rq->all();
    $now = Carbon::createFromFormat("d", $input["day"]);
    $start = $now->startOfWeek()->format("Y-m-d");
    $end = $now->endOfWeek(Carbon::SUNDAY)->format("Y-m-d");
    $end = $now->addWeek()->startOfWeek()->format("Y-m-d");
    $minimumReward = Setting::where("name", "minimum_reward")->first();
    $delayDay = Setting::where("name", "delay_day_week")->first();
    $result = array();

    User::chunkById(200, function ($users) use ($start, $end, $minimumReward, $delayDay, &$result) {
      // Loop each user in 200 users
      foreach ($users as $user) {
        if (checkUserReference($user->id, $start, $end, 6, (float)$minimumReward->value, (int)$delayDay->value)) {
          $result[$user->username] = "OK";
          // $this->info("User $user->username dat du dieu kien an tron theo tuan (week) - Tien hanh an tron");
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
          $result[$user->username] = "Khong";
          $this->giveCommission($user->id);
        }
      }
    }, $column = "id");
    return $result;
  }

  public function checkUserUpdateMonth($month)
  {
    // $now = Carbon::now()->subMonth(); // Previous month
    $now = Carbon::createFromFormat("m", $month);
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

    $result = array();
    User::chunkById(200, function ($users) use ($dates, $minimumReward, $delayDay, &$result) {
      // Loop each user in 200 users
      foreach ($users as $user) {
        $count = 0;
        for ($i = 1; $i <= count($dates); $i++) {
          $week = $dates[$i - 1];
          if (checkUserReference($user->id, current($week), end($week), 6 * $i, (float)$minimumReward->value, (int)$delayDay->value)) {
            $count++;
          }
        }
        if ($count >= 4) {
          $result[$user->username] = "OK";
          $this->removeReference($user->id);
        } else {
          // $this->error("User $user->username khong du dieu kien tach line theo thang.");
          $result[$user->username] = "Khong";
        }
      }
    }, $column = "id");

    return $result;
  }

  function removeReference($userId)
  {
    $u = User::where("id", $userId)->first();
    if (!$u){
      return;
    }

    $u->reference = null;
    $u->save();
    // $this->info("$u->username da tach line xong.");
    return;
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
