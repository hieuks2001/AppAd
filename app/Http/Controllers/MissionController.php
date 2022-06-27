<?php

namespace App\Http\Controllers;

use App\Constants\MissionStatusConstants;
use App\Constants\PagePriorityConstants;
use App\Constants\PageStatusConstants;
use App\Models\Mission;
use App\Models\Missions;
use App\Models\Page;
use App\Models\PageType;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MissionController extends Controller
{
    public function test(Request $request)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomkey = substr(str_shuffle($permitted_chars), 0, 10);
        $mission = Mission::where('ms_status', 'already')->where('ms_name', $request->name)->update(['ms_code' => $randomkey]);
        return Redirect::to('/test');
    }

    // Helper function
    public function UpdateUserType()
    {
        $user = Auth::user();
        $type = PageType::whereBetween('mission_need', [$user->mission_count, PageType::max('mission_need')])
            ->orderBy('mission_need', 'asc')->first();
        if (!$type) {
            $type = PageType::orderBy('mission_need', 'desc')->limit(1)->first();
        }
        if ($type->id != $user->page_type_id) {
            User::where('id', $user->id)->update(['page_type_id' => $type->id]);
        }
        return $type;
    }

    public function getMission()
    {
        $user = Auth::user();
        $mission = Mission::where('user_id', $user->id)
            ->where('status', MissionStatusConstants::DOING)
            ->orderBy('created_at', 'desc')->first();

        if ($mission) {
            $page = Page::where('id', $mission->page_id)
                ->where('status', PageStatusConstants::APPROVED)->first();
            return view('mission.mission', ['mission' => $mission, 'page' => $page]);
        } else {
            return view('mission.mission', [])->withErrors("Bạn chưa nhận nhiệm vụ!");
        }
    }

    public function postMission(Request $request)
    {
        // Update UserType
        $pageType = $this->UpdateUserType();
        $user = Auth::user();

        $mission = Mission::where('user_id', $user->id)
            ->where('status', MissionStatusConstants::DOING)
            ->orderBy('created_at', 'desc')->first();
        if ($mission) { // There is mission existed!
            $page = Page::where('id', $mission->page_id)
                ->where('status', PageStatusConstants::APPROVED)->first();
            return view('mission.mission', ['mission' => $mission, 'page' => $page]);
        }

        // NO MISSION CURRENTLY DOING 

        $pageQuery = Page::query();
        // Get all id of page_type have mission_need <= current user page_type
        $pageTypeIdArr = PageType::where('mission_need', '<=', $pageType->mission_need)->pluck('id');
        // Add conditions
        $pageQuery->where('traffic_remain', '>', 0)
            ->where('status', PageStatusConstants::APPROVED)
            ->whereIn('page_type_id', $pageTypeIdArr);

        // Query pages by Priority (HIGH -> MEDIUM -> LOW)
        $pages = (clone $pageQuery)->where('priority', PagePriorityConstants::HIGH)->get();
        if ($pages->isEmpty()) { // If no HIGH priority page found
            $pages = (clone $pageQuery)->where('priority', PagePriorityConstants::MEDIUM)->get();
        }
        if ($pages->isEmpty()) { // If no MEDIUM priority page found
            $pages = (clone $pageQuery)->where('priority', PagePriorityConstants::LOW)->get();
        }
        if ($pages->isEmpty()) { // If there is no pages available
            return view('mission.mission', [])->withErrors('Không còn nhiệm vụ, vui lòng quay lại sau!');
        }

        // If there are pages available
        // pick random page from pages
        $pages = $pages->shuffle();
        $now = Carbon::now();
        $pickedPage = null;

        foreach ($pages as $page) {
            $mission = Mission::where('page_id', $page->id)
                ->where('status', MissionStatusConstants::COMPLETED)
                ->where('ip', $request->ip())
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
        }

        if (!$pickedPage) {
            // No page available -> comback later
            return view('mission.mission', [])->withErrors('Không còn nhiệm vụ, vui lòng quay lại sau!');
        }

        // Begin database transaction
        DB::transaction(function () use ($pickedPage, $user, $request) {
            // Refresh data
            $pickedPage = $pickedPage->refresh();

            $newMission = new Mission();
            $newMission->page_id = $pickedPage->id;
            $newMission->user_id = $user->id;
            // Reward = (price - 10% ) / traffic_sum
            $newMission->reward = ($pickedPage->price - ($pickedPage->price * $pickedPage->hold_percentage / 100)) / $pickedPage->traffic_sum;
            $newMission->status = MissionStatusConstants::DOING;
            $newMission->ip = $request->ip();
            $newMission->user_agent = $request->userAgent();
            $newMission->save();

            $pickedPage->traffic_remain -= 1;
            $pickedPage->save();
        });

        return Redirect::to('/tu-khoa');
    }

    public function cancelMission()
    {
        // Cancel current mission
        $user = Auth::user();
        $mission = Mission::where('user_id', $user->id)
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
        return Redirect::to('/tu-khoa');
    }
}
