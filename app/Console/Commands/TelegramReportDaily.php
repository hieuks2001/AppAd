<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\LogTrafficTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Constants\TransactionTypeConstants;
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
      $transactions_withdraw = LogTrafficTransaction::whereBetween('updated_at',[$start,$end])->where('type',TransactionTypeConstants::WITHDRAW);
      $withdraw_approved = clone $transactions_withdraw;
      $withdraw_not_approved = clone $transactions_withdraw;
      $transactions_withdrawApproved = $withdraw_approved->where('status',1)->selectRaw('sum(amount) as sum_amount, count(*) as total')->first();
      $transactions_withdrawNotApproved = $withdraw_not_approved->where('status',0)->selectRaw('count(*) as total')->first();
      $text_withdraw = "Báo cáo từ memtraffic.com \n"
      . "Tổng số yêu cầu đã duyệt: $transactions_withdrawApproved->total\n"
      . "Tổng số tiền đã duyệt: $transactions_withdrawApproved->sum_amount\n";
      if ($transactions_withdrawNotApproved->total > 0) {
        $text_withdraw = "Báo cáo từ memtraffic.com \n"
        . "Số yêu cầu <b>chưa duyệt</b>: $transactions_withdrawNotApproved->total\n"
        . "Số yêu cầu <b>đã duyệt</b>: $transactions_withdrawApproved->total\n"
        . "Tổng số tiền đã duyệt: $transactions_withdrawApproved->sum_amount\n";
      }
      $transactions_topup = LogTrafficTransaction::whereBetween('updated_at',[$start,$end])->where('type',TransactionTypeConstants::TOPUP);
      $topup_approved = clone $transactions_topup;
      $topup_not_approved = clone $transactions_topup;
      $transactions_topupApproved = $topup_approved->where('status',1)->selectRaw('sum(amount) as sum_amount, count(*) as total')->first();
      $transactions_topupNotApproved = $topup_not_approved->where('status',0)->selectRaw('count(*) as total')->first();
      $text_topup = "Báo cáo từ memtraffic.com \n"
      . "Tổng số yêu cầu đã duyệt: $transactions_topupApproved->total\n"
      . "Tổng số tiền đã duyệt: $transactions_topupApproved->sum_amount\n";
      if ($transactions_topupNotApproved->total > 0) {
        $text_topup = "Báo cáo từ memtraffic.com \n"
        . "Số yêu cầu <b>chưa duyệt</b>: $transactions_topupNotApproved->total\n"
        . "Số yêu cầu <b>đã duyệt</b>: $transactions_topupApproved->total\n"
        . "Tổng số tiền đã duyệt: $transactions_topupApproved->sum_amount\n";
      }
      Telegram::sendMessage([
        'chat_id' => env('TELEGRAM_ADMIN'),
        'parse_mode' => 'HTML',
        'text' => $text_withdraw,
      ]);
      Telegram::sendMessage([
        'chat_id' => env('TELEGRAM_ADMIN_DEPOSIT'),
        'parse_mode' => 'HTML',
        'text' => $text_topup,
      ]);
      return 0;
    }
}
