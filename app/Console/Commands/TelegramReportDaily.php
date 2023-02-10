<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\LogMissionTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TelegramReportDaily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:report';

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
      $now = Carbon::now();
      $start = $now->startOfDay()->toDateTimeString();
      $end = $now->endOfDay()->toDateTimeString();
      $transactions = LogMissionTransaction::whereBetween('updated_at',[$start,$end])->where('type','withdraw');
      $approved = clone $transactions;
      $not_approved = clone $transactions;
      $transactionsApproved = $approved->where('status',1)->selectRaw('sum(amount) as sum_amount, count(*) as total')->get();
      $transactionsNotApproved = $not_approved->where('status',0)->selectRaw('count(*) as total')->get();
      $text = "Báo cáo từ nhiemvu.app \n"
      . "Tổng số yêu cầu đã duyệt: $transactionsApproved->total\n"
      . "Tổng số tiền đã duyệt: $transactionsApproved->sum_amount\n";
      if ($transactionsNotApproved->total > 0) {
        $text = "Báo cáo từ nhiemvu.app \n"
        . "Số yêu cầu <b>chưa duyệt</b>: $transactionsNotApproved->total\n"
        . "Số yêu cầu <b>đã duyệt</b>: $transactionsApproved->total\n"
        . "Tổng số tiền đã duyệt: $transactionsApproved->sum_amount\n";
      }
      Telegram::sendMessage([
        'chat_id' => env('TELEGRAM_ADMIN'),
        'parse_mode' => 'HTML',
        'text' => $text,
      ]);
      return 0;
    }
}
