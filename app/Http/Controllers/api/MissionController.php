<?php

namespace App\Http\Controllers\api;

use App\Constants\MissionStatusConstants;
use App\Constants\PageStatusConstants;
use App\Models\Missions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Page;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class MissionController extends Controller
{
  public function getUserIpAddr()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

  public function getMission(Request $request)
  {
    $userIP = $this->getUserIpAddr();
    $userAgent = $request->userAgent();
    $mission = Mission::where('ip', $userIP)
      ->where('user_agent', $userAgent)
      ->where('status', MissionStatusConstants::DOING)
      ->orderBy('created_at', 'desc')->first();
    if (!$mission) { // There is mission existed!
      return response()->json(["error" => "No mission received"]);
    }
    $page = Page::where('id', $mission->page_id)
      ->where('status', 1)->first();
    if ($mission->ip !== $userIP || $mission->user_agent !== $userAgent) {
      return response()->json(["mission" => $page, "error"=>"Nhiệm vụ bị quá hạn, vui lòng hủy và nhận lại"]);
    }
    return response()->json(["mission" => $page]);
  }

  public function postMission(Request $request)
  {
    $originUrl = $request->headers->get('origin');
    $userIP = $this->getUserIpAddr();
    $mission = Mission::where([
      'ip' => $userIP,
      'user_agent' => $request->userAgent(),
      'status' => MissionStatusConstants::DOING,
    ])
      ->orderBy('created_at', 'desc')->first();
    if ($mission) { // There is mission existed!
      $page = Page::where('id', $mission->page_id)
        ->where('status', PageStatusConstants::APPROVED)->get(['keyword', 'onsite', 'image', 'url'])->first();
      return response()->json(["mission" => $page]);
    }
    // NO MISSION CURRENTLY DOING
    $pickedPage = null;
    $excludePageId = [];

    while (!$pickedPage) {
      $now = Carbon::now();
      $page = Page::where('status', 1)
        ->whereNotIn('id', $excludePageId)
        ->inRandomOrder()->first();
      if (!$page) {
        break;
      }
      $mission = Mission::where('page_id', $page->id)
        ->where('status', MissionStatusConstants::COMPLETED)
        ->where('ip', $userIP)
        ->whereDate('updated_at',  Carbon::today())
        ->orderBy('updated_at', 'desc')->first();
      if (!$mission) {
        // There is no mission that user doing
        $pickedPage = $page;
        break;
      }
      // Find difference from last done mission of this page from user
      $lastMissionTime = new Carbon($mission->updated_at);
      $time = Carbon::parse($now->diff($lastMissionTime)->format('%H:%I:%S'));
      if ($time->gte(Carbon::createFromTimestamp($page->timeout))) {
        $pickedPage = $page;
        break;
      }
      if (!$pickedPage) {
        array_push($excludePageId, $page->id);
        // unset($pageType[$pageTypeId]);
      }
    }

    if (!$pickedPage) {
      // No page available -> comback later
      return response()->json(["error" => "No mission available"]);
    }
    // Begin database transaction
    DB::transaction(function () use ($pickedPage, $request, $userIP, $originUrl) {
      // Refresh data
      $pickedPage = $pickedPage->refresh();

      $newMission = new Mission();
      $newMission->page_id = $pickedPage->id;
      $newMission->status = MissionStatusConstants::DOING;
      $newMission->ip = $userIP;
      $newMission->user_agent = $request->userAgent();
      $newMission->save();
      $newMission->origin_url = $originUrl;

      $pickedPage->traffic_remain -= 1;
      $pickedPage->save();
    });

    return response()->json(["mission" => $pickedPage]);
  }

  public function cancelMission(Request $request)
  {
    $userIP = $this->getUserIpAddr();
    // Cancel current mission
    $mission = Mission::where([
      'ip' => $userIP,
      'user_agent' => $request->userAgent()
    ])
      ->where('status', MissionStatusConstants::DOING)
      ->orderBy('created_at', 'desc')->first();

    if ($mission) {

      DB::transaction(function () use ($mission) {

        $mission->status = MissionStatusConstants::CANCEL;
        $mission->save();

        $page = Page::where('id', $mission->page_id)->first();
        if ($page->traffic_remain < $page->traffic_sum) {
          $page->traffic_remain += 1;
          $page->save();
        }
      });
    }
    return response()->json(["status" => "ok"]);
  }

  public function generateCode(Request $rq)
  {
    try {
      $pageId = $rq->pageId;
      $host = $rq->host;
      $path = $rq->path;
      // $uIP = $rq->ip();
      $uIP = $this->getUserIpAddr();
      $uAgent = $rq->userAgent();
      $mission = Mission::where([
        ["ip", $uIP],
        ["user_agent", $uAgent],
        ["page_id", $pageId],
        ["missions.status", MissionStatusConstants::DOING],
      ]);
      //rule here
      $page = Page::where([
        ["id", $pageId],
        ["status", 1],
      ])->get(["onsite", "url"])->first();
      if (empty($page)) {
        return response()->json(["error" => "Traffic của site chưa sẵn sàng"]);
      }
      if (!str_contains($page->url, $host)) {
        return response()->json(["error" => "Lỗi, nhúng không đúng site"]);
      }

      //check this ip don't have mission
      if ($mission->count() === 0) {
        return response()->json(["error" => "Lỗi"]);
      }

      //first count down
      $time = $mission->get('updated_at')->first();
      if (empty($time->updated_at)) {
        $mission->update(['updated_at' => Carbon::now()]);
        return response()->json(["onsite" => $page->onsite]);
      }

      // f5 - or click anything link
      $code = $mission->get('code')->first();
      //check code is exist
      if (empty($code->code)) {
        //generateCode
        //check rule
        $timeDiff = Carbon::now()->diffInSeconds($time->updated_at);
        if ($page->onsite <= $timeDiff) {
          if ($path !== "/") {
            $uuid = Uuid::uuid4()->toString();
            $mission->update(["missions.code" => $uuid]);
            return response()->json(["code" => $uuid]);
          } else {
            $mission->update(['updated_at' => Carbon::now()]);
            return response()->json(["onsite" => $page->onsite]);
          }
        } else {
          $mission->update(['updated_at' => Carbon::now()]);
          return response()->json(["onsite" => $page->onsite]);
        }
      } else {
        return response()->json($code);
      }
    } catch (Exception $err) {
      return response()->json(["error" => $err->getMessage()], 500);
    }
  }

  public function pastekey(Request $request)
  {
    //rule here
    $uIP = $this->getUserIpAddr();
    $ms = Missions::where([
      ["ip", $uIP],
      ["user_agent", $request->userAgent()],
      ["status", 0]
    ]);
    $msGet = ($ms)->get(["code", "page_id"])->first();
    if (!$msGet){
      return response()->json(["error" => "No mission"]);
    }
    if (!empty($msGet->code) and $msGet->code == $request->key) {
      $ms->update(["status" => 1]);
      return response()->json(["success" => "Correct code"]);
    } else if (empty($msGet->code) or (!empty($msGet->code) and $msGet->code != $request->key)) {
      // wrong key
      DB::transaction(function () use ($msGet, $ms) {
        $page = Page::where('id', $msGet->page_id)->first();
        if ($page->traffic_remain < $page->traffic_sum) {
          $page->traffic_remain += 1;
          $page->save();
        }
      });
      return response()->json(["error" => "Wrong key"]);
    }
    return response()->json(["error" => "Wrong key"]);
  }
}
