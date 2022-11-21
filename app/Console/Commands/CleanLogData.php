<?php

namespace App\Console\Commands;

use App\Models\LogMissionTransaction;
use App\Models\LogTrafficTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanLogData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

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
      $now = Carbon::now()->subMonth(3);
      $data = LogTrafficTransaction::whereDate('created_at', '<', $now )->cursor();

      foreach ($data as $item) {
        $item->delete();
      }

      $data = LogMissionTransaction::whereDate('created_at', '<', $now )->cursor();
      foreach ($data as $item) {
        $item->delete();
      }
      return 0;
    }
}
