<?php

namespace App\Console\Commands;

use App\Constants\TransactionStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogMissionTransaction;
use App\Models\LogTrafficTransaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Http;

class HandleUpdateTelegram extends Command
{
  public function momoSend($data, $callback)
  {
    $response = Http::asForm()->post('https://api-momo.online/share.php', [
      'token' => '9zqwhGSdisUGouE75w3O8rd2L1dj4OPgGyfOqSieUao',
      'id_momo' => '0906568374',
      'phone' => $data->phone,
      'money' => $data->money * 23000,
      'comment' => $data->comment,
    ]);
    if ($response["status"]) {
      if (is_callable($callback)) {
        call_user_func($callback, $response);
      }
      return $response;
    } else {
      //refresh token if error
      $token = Http::asForm()->post('https://api-momo.online/share.php', [
        'token' => 'npEXDCZaHRaN0YvbldX700vLACEnedPaayntqVbDHoI',
        'id_momo' => '0906568374',
      ]);
      $this->momoSend($data, $callback);
    }
  }
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'telegram:get-update';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Update topup/withdraw accept request from Telegram';

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
    $activities = Telegram::getUpdates();
    // dd($activities);
    if (count($activities) <= 0) {
      return true;
    }

    foreach ($activities as $k => $v) {
      if (isset($v['callback_query'])) {
        $record = json_decode($v->callback_query);
        // dd($record);
        $data = json_decode($record->data);
        $old_txt = $record->message->text;

        if ($data->from == 'traffic') {
          $mRequest = LogTrafficTransaction::find($data->id_request);
        } else {
          $mRequest = LogMissionTransaction::find($data->id_request);
        }

        if (!$mRequest) {
          echo "Không tìm thấy yêu cầu ! id " . $data->id_request . " từ " . $data->from;
          continue;
        }

        if ($mRequest->status == TransactionStatusConstants::APPROVED || $mRequest->status == TransactionStatusConstants::CANCELED) {
          echo "Yêu cầu đã được duyệt rồi! id " . $data->id_request . " từ " . $data->from;
          continue;
        }

        $user_table = $data->from == 'traffic' ? "user_traffics" : "user_missions";
        $targetUser = DB::table($user_table)->where("id", $mRequest->user_id);

        if ($data->type == TransactionStatusConstants::APPROVED) {
          if ($mRequest->type == TransactionTypeConstants::TOPUP) {
            $targetUser->increment("wallet", $mRequest->amount);
          } else if ($mRequest->type == TransactionTypeConstants::WITHDRAW) {
            if (($targetUser)->first()->wallet < $mRequest->amount) {
              echo "Người dùng không đủ USDT cho yêu cầu này! id " . $data->id_request . " từ " . $data->from;
              continue;
            }
            // momo
            $body = new \stdClass();
            $body->phone = $targetUser->first()->phone_number;
            $body->money = $mRequest->amount;
            $from_site = $data->from == 'traffic' ? 'memtraffic.com' : 'nhiemvu.app';
            $body->comment = "Rút tiền từ " . $from_site;
            $rsMomo = $this->momoSend($body, function ($result) use ($targetUser, $mRequest) {
              if (!$result["error"]) { //thanh cong
                $targetUser->decrement("wallet", $mRequest->amount);
                return true;
              } else {
                //momo error
                echo "Lỗi chuyển tiền" . $result["message"];
                return false;
              }
            });
            if (!$rsMomo) {
              continue;
            };
          };
          $mRequest->status = TransactionStatusConstants::APPROVED;
          $mRequest->save();

          $mappingTxt = $mRequest->type == TransactionTypeConstants::TOPUP
            ? ["txt" => "Nạp tiền thành công", "amount" => $mRequest->amount]
            : ["txt" => "Rút tiền thành công", "amount" => $mRequest->amount];

          try {
            Telegram::editMessageText([
              'parse_mode' => 'HTML',
              'chat_id' => $mRequest->type == TransactionTypeConstants::TOPUP ? env('TELEGRAM_ADMIN_DEPOSIT') : env('TELEGRAM_ADMIN'),
              'text' => $old_txt . "\n<b>Đã Duyệt</b>\n",
              'message_id' => $record->message->message_id
            ]);
            echo 'Edit message ok';
          } catch (\Throwable $th) {
            // throw $th;
          }
        } else if ($data->type == TransactionStatusConstants::CANCELED) {
          $mRequest->status = TransactionStatusConstants::CANCELED;
          $mRequest->save();
          try {
            Telegram::editMessageText([
              'parse_mode' => 'HTML',
              'chat_id' => $mRequest->type == TransactionTypeConstants::TOPUP ? env('TELEGRAM_ADMIN_DEPOSIT') : env('TELEGRAM_ADMIN'),
              'text' => $old_txt . "\n<b>Đã Huỷ</b>\n",
              'message_id' => $record->message->message_id
            ]);
            echo 'Edit message ok';
          } catch (\Throwable $th) {
            // throw $th;
          }
        }
      }
    }
    return true;
  }
}
