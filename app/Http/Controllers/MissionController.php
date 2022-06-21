<?php

namespace App\Http\Controllers;

use App\Constants\MissionStatusConstants;
use App\Constants\PagePriorityConstants;
use App\Constants\PageStatusConstants;
use App\Models\Mission;
use App\Models\Page;
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

    public function getMission(){
        $user = Auth::user();
        $mission = Mission::where('user_id', $user->id)
            ->where('status', MissionStatusConstants::DOING)
            ->orderBy('created_at', 'desc')->first();

        if ($mission) {
            $page = Page::where('id', $mission->page_id)
                ->where('status', PageStatusConstants::APPROVED)->first();
            return view('mission.mission', ['mission' => $mission, 'missions' => $mission, 'page' => $page]); 
        } else {
            return view('mission.mission', [])->withErrors("Bạn chưa nhận nhiệm vụ!");
        }
    }

    public function postMission()
    {
        $user = Auth::user();
        $mission = Mission::where('user_id', $user->id)
            ->where('status', MissionStatusConstants::DOING)
            ->orderBy('created_at', 'desc')->first();

        if ($mission) {
            $page = Page::where('id', $mission->page_id)
                ->where('status', PageStatusConstants::APPROVED)->first();
            return view('mission.mission', ['mission' => $mission, 'missions' => $mission, 'page' => $page]); 
        }

        // Query pages by Priority (HIGH -> MEDIUM -> LOW)
        $pages = Page::where('priority', PagePriorityConstants::HIGH)
            ->where('traffic_remain', '>', 0)
            ->where('status', PageStatusConstants::APPROVED)
            ->get();

        if ($pages->isEmpty()) { // If no HIGH priority page found
            $pages = Page::where('priority', PagePriorityConstants::MEDIUM)
                ->where('status', PageStatusConstants::APPROVED)
                ->where('traffic_remain', '>', 0)->get();
        }

        if ($pages->isEmpty()) { // If no MEDIUM priority page found
            $pages = Page::where('priority', PagePriorityConstants::LOW)
                ->where('status', PageStatusConstants::APPROVED)
                ->where('traffic_remain', '>', 0)->get();
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
                ->orderBy('updated_at', 'desc')->first();

            if (!$mission){
                // There is no mission that user doing
                $pickedPage = $page;
                break;
            }

            // Find difference from last done mission of this page from user
            $lastMissionTime = new Carbon($mission->updated_at);
            $time = Carbon::parse($now->diff($lastMissionTime)->format('%H:%I:%S'));

            if($time->gte($page->timeout)){
                $pickedPage = $page;
                break;
            }
        }

        if(!$pickedPage) {
            // No page available -> comback later
            return view('mission.mission', [])->withErrors('Không còn nhiệm vụ, vui lòng quay lại sau!');
        }
        
        $newMission = new Mission();
        $newMission->page_id = $pickedPage->id;
        $newMission->user_id = $user->id;
        // Reward = (price - 10% ) / traffic_sum
        $newMission->reward = ($pickedPage->price - ($pickedPage->price * 10 / 100) ) / $pickedPage->traffic_sum;
        $newMission->status = MissionStatusConstants::DOING;
        $newMission->save();
        
        return Redirect::to('/tu-khoa');
    }
}
