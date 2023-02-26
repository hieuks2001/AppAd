<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Constants\MissionStatusConstants;
use App\Constants\PagePriorityConstants;
use App\Constants\PageStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogTransaction;
use App\Models\Mission;
use App\Models\Page;
use App\Models\PageType;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class UsersResetMissionCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mission:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset user mission count daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
      public function isMissionExpried(Mission $ms)
      {
        // Check if mission haven't completed after 3 hours -> CANCEL
        $now = Carbon::now();
        $lastMissionTime = new Carbon($ms->created_at);
        $time = $now->diff($lastMissionTime);
        if ((int)$time->format('%a') < 1) { // Greater than 1 day
          return false;
        }
        return true;
      }
    
      public function setMissionStatusCancel(Mission $mission)
      {
        DB::transaction(function () use ($mission) {
          $mission->status = MissionStatusConstants::CANCEL;
          $mission->save();
          $page = Page::where('id', $mission->page_id)->first();
          if ($page->traffic_remain < $page->traffic_sum) {
            $page->traffic_remain += 1;
            $page->save();
          }
        });
        return true;
      }
    public function handle()
    {
        User::chunkById(200, function ($users) {
                $users->each->update(['mission_count'=>array()]);
            }, $column='id');
        $mission = Mission::where('status', MissionStatusConstants::DOING)->get();
        foreach( $mission as $ms) {
            $page = Page::where('id', $ms->page_id)->where('status', PageStatusConstants::APPROVED)->first();
            if ($this->isMissionExpried($ms)) {
                $this->setMissionStatusCancel($ms);
            }
        }
    }
}
