<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

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
    public function handle()
    {
        User::where('mission_count', '>', 0)
            ->chunkById(200, function ($users) {
                $users->each->update(['mission_count'=>0]);
            }, $column='id');
    }
}
