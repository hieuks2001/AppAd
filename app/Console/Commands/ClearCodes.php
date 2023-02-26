<?php

namespace App\Console\Commands;

use App\Models\Code;
use App\Models\UserMission;
use Illuminate\Console\Command;

class ClearCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'code:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {
        Code::query()->delete();
        UserMission::chunkById(200, function ($users) {
            $users->each->update(['mission_attempts'=>0,"status"=>1]);
        }, $column='id');
    }
}
