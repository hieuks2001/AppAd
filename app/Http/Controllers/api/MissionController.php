<?php

namespace App\Http\Controllers\api;

use App\Constants\MissionStatusConstants;
use App\Constants\PageStatusConstants;
use App\Models\Missions;
use App\Models\Code;
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
    $ms = $request->ms;
    $userAgent = $request->userAgent();
    $mission = Mission::where('id', $ms)
      ->where('user_agent', $userAgent)
      ->where('status', MissionStatusConstants::DOING)
      ->orderBy('created_at', 'desc')->first();
    if (!$mission) { // There is mission existed!
      return response()->json(["error" => "No mission received"]);
    }
    $page = Page::where('id', $mission->page_id)
      ->where('status', 1)->first();
    return response()->json(["mission" => $mission, "page" => $page]);
  }

  public function postMission(Request $request)
  {
    $originUrl = $request->headers->get('origin');
    $userIP = $this->getUserIpAddr();
    $ms = $request->ms;
    $mission = Mission::where([
      // 'ip' => $userIP,
      'id' => $ms,
      'user_agent' => $request->userAgent(),
      'status' => MissionStatusConstants::DOING,
    ])
      ->orderBy('created_at', 'desc')->first();
    if ($mission) { // There is mission existed!
      $page = Page::where('id', $mission->page_id)
        ->where('status', PageStatusConstants::APPROVED)->get(['keyword', 'onsite', 'image', 'url'])->first();
      return response()->json(["page" => $page,"mission"=>$mission->id]);
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
        ->where('id', $ms)
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
    $msId = DB::transaction(function () use ($pickedPage, $request, $userIP, $originUrl) {
      // Refresh data
      $pickedPage = $pickedPage->refresh();

      $newMission = new Mission();
      $newMission->page_id = $pickedPage->id;
      $newMission->status = MissionStatusConstants::DOING;
      $newMission->ip = $userIP;
      $newMission->user_agent = $request->userAgent();
      $newMission->origin_url = $originUrl;
      $newMission->save();
      
      $pickedPage->traffic_remain -= 1;
      $pickedPage->save();
      return $newMission->id;
    });

    return response()->json(["page" => $pickedPage,"mission"=> $msId]);
  }

  public function cancelMission(Request $request)
  {
    $userIP = $this->getUserIpAddr();
    $ms = $request->ms;
    // Cancel current mission
    $mission = Mission::where([
      // 'ip' => $userIP,
      'id' => $ms,
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

  public function pastekey(Request $request)
  {
    //rule here
    $uIP = $this->getUserIpAddr();
    $msId = $request->ms;
    $ms = Missions::where([
      // ["ip", $uIP],
      ["id", $msId],
      ["user_agent", $request->userAgent()],
      ["status", 0]
    ]);
    $msGet = ($ms)->get(["code", "page_id"])->first();
    if (!$msGet){
      return response()->json(["error" => "No mission"]);
    }
    $code = Code::where([
      ["code",$request->key],
      ["status",0]
    ]);
    if (!is_null($code->first())) {
      $ms->update(["status" => 1,"code"=>$request->key]);
      $code->update(["status" => 1]);
      return response()->json(["success" => "Correct code"]);
    } else {
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
