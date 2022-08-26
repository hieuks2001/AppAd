<?php

namespace App\Http\Controllers;

use App\Constants\TransactionStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogMissionTransaction;
use App\Models\LogTrafficTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
  public function index()
  {
    dd(Telegram::getUpdates());
  }

  public function newUpdate(Request $request)
  {
    $callback_query = $request->callback_query ?? null;
    if ($callback_query) {
      try {
        $record = json_decode($callback_query);
        $data = json_decode($record->data);
        $old_txt = $record->message->text;

        if ($data->from == 'traffic') {
          $mRequest = LogTrafficTransaction::find($data->id_request);
        } else {
          $mRequest = LogMissionTransaction::find($data->id_request);
        }

        if (!$mRequest) {
          echo "Không tìm thấy yêu cầu ! id " . $data->id_request . " từ " . $data->from;
          return;
        }

        if ($mRequest->status == TransactionStatusConstants::APPROVED || $mRequest->status == TransactionStatusConstants::CANCELED) {
          echo "Yêu cầu đã được duyệt rồi! id " . $data->id_request . " từ " . $data->from;
          return;
        }

        $user_table = $data->from == 'traffic' ? "user_traffics" : "user_missions";
        $targetUser = DB::table($user_table)->where("id", $mRequest->user_id);

        if ($data->type == TransactionStatusConstants::APPROVED) {
          if ($mRequest->type == TransactionTypeConstants::TOPUP) {
            $targetUser->increment("wallet", $mRequest->amount);
          } else if ($mRequest->type == TransactionTypeConstants::WITHDRAW) {
            if (($targetUser)->first()->wallet < $mRequest->amount) {
              echo "Người dùng không đủ USDT cho yêu cầu này! id " . $data->id_request . " từ " . $data->from;
              return;
            }
            $targetUser->decrement("wallet", $mRequest->amount);
          }

          $mRequest->status = TransactionStatusConstants::APPROVED;
          $mRequest->save();

          $mappingTxt = $mRequest->type == TransactionTypeConstants::TOPUP
            ? ["txt" => "Nạp tiền thành công", "amount" => $mRequest->amount]
            : ["txt" => "Rút tiền thành công", "amount" => $mRequest->amount];

          try {
            Telegram::editMessageText([
              'parse_mode' => 'HTML',
              'chat_id' => env('TELEGRAM_ADMIN'),
              'text' => $old_txt . "\n<b>Đã Duyệt</b>\n",
              'message_id' => $record->message->message_id
            ]);
            echo 'Edit message ok';
          } catch (\Throwable $th) {
            echo $th;
          }
        } else if ($data->type == TransactionStatusConstants::CANCELED) {
          $mRequest->status = TransactionStatusConstants::CANCELED;
          $mRequest->save();
          try {
            Telegram::editMessageText([
              'parse_mode' => 'HTML',
              'chat_id' => env('TELEGRAM_ADMIN'),
              'text' => $old_txt . "\n<b>Đã Huỷ</b>\n",
              'message_id' => $record->message->message_id
            ]);
            echo 'Edit message ok';
          } catch (\Throwable $th) {
            // throw $th;
          }
        }
      } catch (Exception $e) {
        echo $e;
      }
    }
  }
}
