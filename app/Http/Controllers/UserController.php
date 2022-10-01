<?php

namespace App\Http\Controllers;

use App\Constants\MissionStatusConstants;
use App\Constants\TransactionStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Constants\UserConstants;
use App\Models\LogTrafficTransaction;
use App\Models\Missions;
use App\Models\Otp;
use App\Models\User;
use App\Models\Page;
use App\Models\PageType;
use App\Models\UserType;
use Carbon\Carbon;
use App\Notifications\TelegramNotification;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Telegram\Bot\Laravel\Facades\Telegram;
use Web3\Web3;
use Web3\Contract;
use Web3\Utils;

class UserController extends Controller
{
  // Helper funcs
  public function getUserIpAddr()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
  }

  public function sendOTP($phoneNumber, $otp)
  {
    $url = "https://safeotp.com/api/message";
    $data = [
      'token' => '6070d3e6-d221-40b0-b254-f7b39bf06611',
      'data' => ['otp' => $otp],
      'recipients' => [
        $phoneNumber
      ]
    ];
    $response = Http::withBody(json_encode($data), 'application/json')
      ->post($url);
    return $response->successful();
  }

  public function login(Request $request)
  {
    if (Auth::check()) {
      return Redirect::to('/');
    }
    if (isset($request->username) && isset($request->password)) {
      if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
        //Check is Admin -> Redirect to admin site
        return Redirect::to('/')->with("isAdmin", Auth::user()->isAdmin);
      } else {
        return Redirect::to('/login')->with('error', 'Sai tên đăng nhập hoặc mật khẩu!');
      }
    } else {
      return view('procedure.login');
    }
  }

  public function logout()
  {
    Auth::logout();
    return Redirect::to('/login');
  }

  public function register(Request $request)
  {
    if (!isset($request->username)) {
      return view('procedure.register');
    }
    if (!isset($request->password) && !isset($request->re_password)) {
      return Redirect::to('/register')->with('error', 'Không đầy đủ thông tin cần thiết!');
    }
    if ($request->password != $request->re_password) {
      return Redirect::to('/register')->with('error', 'Mật khẩu không trùng khớp!');
    }
    $checkUser = User::where('username', $request->username);
    if ($checkUser->count() != 0) {
      return Redirect::to('/register')->with('error', 'Tên tài khoản đã có người sử dụng!');
    }
    $request->validate([
      'username' => 'required|digits:10',
      'password' => 'required'
    ], [
      'username.digits' => 'SĐT không phù hợp'
    ]);
    $input = $request->all();
    $otp = DB::transaction(function () use ($input) {
      $user = new User();
      $user->username = $input['username'];
      $user->phone_number = $input['username'];
      $user->password = bcrypt($input['password']);
      $user->is_admin = 0;
      $user->status = 0; // Set status to inactive / unverfied
      $user->wallet = 0;
      $user->verified = 0;
      $user->save();

      $otp = new Otp();
      $otp->user_id = $user->id;
      $otp->user = 'traffic';
      $otp->otp = Str::random(5);
      $otp->expire = Carbon::now()->addMinutes(5);
      $otp->save();
      return $otp;
    });

    // Send otp sms
    $this->sendOTP($input['username'], $otp->otp);
    return Redirect::to('/login')->with('message', 'Đăng ký thành công! Vui lòng đăng nhập lại và xác minh mã OTP để kích hoạt tài khoản!');
  }

  public function verifyOtp()
  {
    if (!Auth::check()) {
      return Redirect::to('/login');
    }
    $user = Auth::user();
    if ($user->verified) {
      return Redirect::to('/regispage');
    }
    return view('procedure.otp');
  }

  public function verifyOtpToken(Request $request)
  {
    if (!Auth::check()) {
      return Redirect::to('/login');
    }
    $user = Auth::user();
    if ($user->verified) {
      return Redirect::to('/regispage');
    }
    $otp = OTP::where([
      'user_id' => $user->id,
      'otp' => $request->otp,
      'user' => 'traffic'
    ])->get(['created_at', 'id', 'otp', 'expire'])->first();
    if (!$otp) {
      return Redirect::to('/verify')->with(['error' => 'OTP sai, vui lòng kiểm tra lại!', 'getCode' => false]);
    }
    // Check if otp expired
    $check = Carbon::now()->gt($otp->expire);
    if ($check) {
      $otp->delete();
      return Redirect::to('/verify')->with(['error' => 'OTP đã hết hạn, vui vòng nhận lại!', 'getCode' => true]);
    }
    DB::table('user_traffics')->where('id', $user->id)->update(['verified' => 1, 'status' => 1]);
    $otp->delete();
    return Redirect::to('/regispage');
  }

  public function verifyRenewOtp(Request $request)
  {
    if (!Auth::check()) {
      return Redirect::to('/login');
    }
    $user = Auth::user();
    if ($user->verify) {
      return Redirect::to('/regispage');
    }
    $rs = Otp::where([
      'user_id' => $user->id,
      'user' => 'traffic'
    ])->get()->first();
    if ($rs) {
      $check = Carbon::now()->lt($rs->expire);
      if ($check) {
        return Redirect::to('/verify')->with(['error' => 'Vui lòng đợi sau 5\'!']);
      } else {
        $rs->delete();
      }
    }
    $otp = new Otp();
    $otp->otp = Str::random(5);
    $otp->expire = Carbon::now()->addMinutes(5);
    $otp->user = 'traffic';
    $otp->user_id = $user->id;
    $otp->save();
    $this->sendOTP($user->username, $otp->otp);
    return Redirect::to('/verify');
  }

  public function index()
  {
    $user = Auth::user();
    // $ms = DB::table('missions')->where('ms_userUUID', Session::get('user')->id)->where('ms_status', 'already')->first();
    // $missons = DB::table('missions')->where('ms_userUUID', Session::get('user')->id)->get();
    // // if ($ms) {
    // //   return Redirect::to('/tu-khoa')->with('error', "");
    // // } else {
    // //   return view('mission.mission', ['missions' => $missons]);
    // // }
    if ($user) {
      if ($user->is_admin) {
        return Redirect::to('/management/traffic');
      }
      return Redirect::to('/regispage');
    } else {
      return Redirect::to('/login');
    }
  }

  // ================== MISSIONS ==========================
  public function pastekey(Request $request)
  {
    $user = Auth::user();
    $uIP = $this->getUserIpAddr();
    if ($user->status == 0) {
      // check if user is blocked
      return view('mission.mission', [])->withErrors("Tài khoản của bạn đã bị khoá!");
    }
    //rule here
    $ms = Missions::where('user_id', $user->id)->where([
      ["ip", $request->ip()],
      ["user_agent", $request->userAgent()],
      ["status", 0]
    ]);
    $msGet = ($ms)->get(["code", "reward", "page_id"])->first();
    if (!empty($msGet->code) and $msGet->code == $request->key) {
      DB::transaction(function () use ($ms, $user, $msGet) {
        $ms->update(["status" => 1]);
        $u = User::where('id', $user->id)->first();
        $uMsCount = $u->mission_count;
        $pageTypeId = Page::where('id', $msGet->page_id)->get('page_type_id')->first();
        // Update mission count base on Type of page buy traffic.
        if (!array_key_exists($pageTypeId->page_type_id, $uMsCount)) {
          $uMsCount[$pageTypeId->page_type_id] = 1;
        } else {
          $uMsCount[$pageTypeId->page_type_id] += 1;
        }
        // Calculating reward (Admin hold %)
        $page = Page::where('id', $msGet->page_id)->get(["price", "hold_percentage", "traffic_sum"])->first();
        $reward = $page->price / $page->traffic_sum;
        $commission = $reward * $page->hold_percentage / 100;
        $reward -= $commission;
        $u->update([
          'wallet' => $u->wallet + $reward,
          // 'mission_count' => $u->mission_count + 1,
          'mission_count' => $uMsCount,
          'mission_attempts' => 0
        ]);
        // Create log
        $log = new LogTrafficTransaction([
          'amount' => $reward,
          'user_id' => $u->id,
          'type' => TransactionTypeConstants::REWARD,
        ]);
        $log->save();
      });
      return Redirect::to('/tu-khoa');
    } else if (empty($msGet->code) or (!empty($msGet->code) and $msGet->code != $request->key)) {
      // wrong key
      DB::transaction(function () use ($msGet, $ms, $user) {
        $u = User::where('id', $user->id)->first();
        if ($u->mission_attempts < 2) {
          $u->mission_attempts += 1;
        }
        if ($u->mission_attempts == 2) {
          $u->status = 0;
          $ms->update(['status' => MissionStatusConstants::CANCEL]);
          $page = Page::where('id', $msGet->page_id)->first();
          if ($page->traffic_remain < $page->traffic_sum) {
            $page->traffic_remain += 1;
            $page->save();
          }
        }
        $u->save();
      });
      return Redirect::to('/tu-khoa')->withErrors('Sai mã!');
    }
    return Redirect::to('/tu-khoa')->withErrors('Sai mã!');
  }

  public function tukhoa()
  {
    $user = Auth::user();
    $ms = Missions::where('ms_userUUID', $user->id)->where('ms_status', 'already')->first();
    $missons = Missions::where('ms_userUUID', $user->id)->get();

    if ($ms) {
      $page = Page::where('page_name', $ms->ms_name)->first();
      return view('mission.mission', ['mission' => $ms, 'missions' => $missons, 'page' => $page]);
    } else {
      // $all_missions = Missions::where('ms_status', 'already')->get();
      // // Currently get random -> Futures: Get base on prioriry
      // $pages = Page::inRandomOrder()->limit(1)->get();
      // $list = [];
      // foreach ($all_missions as $key => $value) {
      //   array_push($list, $value->ms_name);
      // }
      // foreach ($pages as $key => $value) {
      //   if (!in_array($value->page_name, $list)) {
      //     // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      //     // $randomkey = substr(str_shuffle($permitted_chars), 0, 4) . '88';
      //     $mission = new Missions();
      //     $mission->ms_name = $value->page_name;
      //     $mission->ms_userUUID = $user->user_uuid;
      //     $mission->ms_countdown = 60;
      //     $mission->ms_price = 0.35;
      //     $mission->ms_status = 'already';
      //     $mission->save();
      //     return Redirect::to('/tu-khoa');
      //   } else {
      //     // return Redirect::to('/')->with('error', 'Nhận nhiệm vụ không thành công!');
      //   }
      // }
      return view('mission.mission', ['missions' => $missons])->withErrors("Bạn chưa nhận nhiệm vụ!");
    }
  }

  public function cancelmission()
  {
    $user = Auth::user();
    $ms = Missions::where('ms_userUUID', $user->id)->where('ms_status', 'already')->update(['ms_status' => 'cancel']);
    return Redirect::to('/tu-khoa');
  }

  // ================== END MISSIONS ==========================


  // ====================== PAGES ============================
  public function addpage(Request $request)
  {
    // Nếu người dùng có chọn file để upload
    if ($request->file('image')) {
      $filename = time() . '.' . request()->image->getClientOriginalExtension();

      request()->image->move(public_path('images'), $filename);
      $db =    DB::table('pages')->insert(['page_name' => $request->pagename, 'page_image' => $filename]);
      if ($db) {
        return redirect()->back()->with('message', 'Thêm thành công!');
      } else {
        return redirect()->back()->with('error', 'Thêm thất bại!');
      }
    }
  }
  public function getMission()
  {
    $user = Auth::user();
    $all_missions = Missions::where('ms_status', 'already')->get();
    // Currently get random -> Futures: Get base on prioriry
    $pages = Page::inRandomOrder()->limit(1)->get();
    $list = [];
    foreach ($all_missions as $key => $value) {
      array_push($list, $value->ms_name);
    }
    foreach ($pages as $key => $value) {
      if (!in_array($value->page_name, $list)) {
        // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $randomkey = substr(str_shuffle($permitted_chars), 0, 4) . '88';
        $mission = new Missions();
        $mission->ms_name = $value->page_name;
        $mission->ms_userUUID = $user->id;
        $mission->ms_countdown = 60;
        $mission->ms_price = 0.35;
        $mission->ms_status = 'already';
        $mission->save();
        return Redirect::to('/tu-khoa');
      } else {
        return Redirect::to('/tu-khoa')->with('error', 'Nhận nhiệm vụ không thành công!');
      }
    }
  }
  // ====================== END PAGES ============================

  public function depositView()
  {
    return view("usdt.deposit");
  }
  public function deposit(Request $request)
  {
    $user = Auth::user();
    $amount = $request->amount;
    $wallet = User::where('id', $user->id)->first();
    // DB::transaction(function () use ($wallet, $amount, $user) {
    // Create log
    $old_log = LogTrafficTransaction::where([
      'user_id' => $user->id,
      'status' => TransactionStatusConstants::PENDING,
      'type' => TransactionTypeConstants::TOPUP
    ])->get();
    if (count($old_log) > 0) {
      return view("usdt.deposit")->withErrors("Bạn đang có yêu cầu nạp chưa được duyệt, vui lòng đợi và thử lại sau!");
    }
    $log = new LogTrafficTransaction();
    $log->amount = $amount;
    $log->user_id = $wallet->id;
    $log->type = TransactionTypeConstants::TOPUP;
    $log->status = TransactionStatusConstants::PENDING;
    $log->save();

    $inline_keyboard = json_encode([
      'inline_keyboard' => [
        [
          ['text' => 'Đồng ý', 'callback_data' => json_encode(['type' => TransactionStatusConstants::APPROVED, 'id_request' => $log->id, 'from' => 'traffic'])],
          ['text' => 'Từ chối', 'callback_data' => json_encode(['type' => TransactionStatusConstants::CANCELED, 'id_request' => $log->id, 'from' => 'traffic'])],
        ],
      ]
    ]);

    // $log->notify(new TelegramNotification($log, $user));
    $text = "Thông báo mới từ memtraffic.com \n"
      . "ID người yêu cầu: $user->id\n"
      . "SDT người yêu cầu: $user->phone_number\n"
      . "Loại: <strong>Nạp tiền</strong>\n"
      . "Số tiền yêu cầu: <strong>$log->amount</strong> USDT \n";

    Telegram::sendMessage([
      'chat_id' => env('TELEGRAM_ADMIN_DEPOSIT'),
      'parse_mode' => 'HTML',
      'text' => $text,
      'reply_markup' => $inline_keyboard,
    ]);

    // });
    return Redirect::to("/deposit");
  }

  public function withdrawView()
  {
    return view("usdt.withdraw");
  }

  public function withdraw(Request $request)
  {
    $user = Auth::user();
    $amount = $request->amount;
    $wallet = $user->wallet;

    if ($amount > $wallet) {
      return view("usdt.withdraw")->withErrors("Không đủ số dư trong tài khoản!");
    }

    $old_log = LogTrafficTransaction::where([
      'user_id' => $user->id,
      'status' => TransactionStatusConstants::PENDING,
      'type' => TransactionTypeConstants::WITHDRAW,
    ])->get();
    if (count($old_log) > 0) {
      return view("usdt.withdraw")->withErrors("Bạn đang có yêu cầu rút chưa được duyệt, vui lòng đợi và thử lại sau!");
    }
    // $rs = DB::transaction(function () use ($wallet, $amount, $user) {
    // $rs = $wallet->update(['wallet' => $wallet->wallet - $amount]);
    // Create log
    $log = new LogTrafficTransaction();
    $log->amount = $amount;
    $log->user_id = $user->id;
    $log->type = TransactionTypeConstants::WITHDRAW;
    $log->status = TransactionStatusConstants::PENDING;
    $log->save();

    $inline_keyboard = json_encode([
      'inline_keyboard' => [
        [
          ['text' => 'Đồng ý', 'callback_data' => json_encode(['type' => TransactionStatusConstants::APPROVED, 'id_request' => $log->id, 'from' => 'traffic'])],
          ['text' => 'Từ chối', 'callback_data' => json_encode(['type' => TransactionStatusConstants::CANCELED, 'id_request' => $log->id, 'from' => 'traffic'])],
        ],
      ]
    ]);

    // $log->notify(new TelegramNotification($log, $user));
    $text = "Thông báo mới từ memtraffic.com \n"
      . "ID người yêu cầu: $user->id\n"
      . "SDT người yêu cầu: $user->phone_number\n"
      . "Loại: <strong>Rút tiền</strong>\n"
      . "Số tiền yêu cầu: <strong>$log->amount</strong> USDT \n";

    Telegram::sendMessage([
      'chat_id' => env('TELEGRAM_ADMIN'),
      'parse_mode' => 'HTML',
      'text' => $text,
      'reply_markup' => $inline_keyboard,
    ]);
    // });

    return view("usdt.withdraw");
  }
}
