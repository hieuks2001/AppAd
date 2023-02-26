<?php

namespace App\Http\Controllers;

use App\Constants\PagePriorityConstants;
use App\Constants\PageStatusConstants;
use App\Constants\OntimeTypeConstants;
use App\Constants\TransactionStatusConstants;
use App\Constants\MissionStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogMissionTransaction;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageType;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserType;
use Brick\Math\Exception\NumberFormatException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use ReflectionClass;
use stdClass;
use Spatie\Browsershot\Browsershot;

class DashboardController extends Controller
{

  public function managementMissions()
  {
    $missions = Page::join("missions", "missions.page_id", "=", "pages.id")
      ->where([
        ['pages.status', "=", PageStatusConstants::APPROVED],
        ['missions.status', "=", MissionStatusConstants::COMPLETED],
      ])
      ->orderBy("missions.updated_at", "DESC")
      ->simplePaginate(
        $perPage = 20,
        $columns = [
          "missions.id", "missions.ip", "missions.origin_url", "missions.updated_at", "missions.reward",
          "pages.url", "pages.price_per_traffic", "pages.hold_percentage"
        ]
      );
    return view('admin.missions')->with('missions', $missions);
  }

  public function searchMission(Request $request)
  {
    $key = $request->data;
    $missions = Page::join("missions", "missions.page_id", "=", "pages.id")
      ->where([
        ['pages.status', "=", PageStatusConstants::APPROVED],
        ['missions.status', "=", MissionStatusConstants::COMPLETED],
      ])
      ->where(function ($query) use ($key) {
        $query->where('url', 'LIKE', "%{$key}%");
        $query->orWhere('origin_url', 'LIKE', "%{$key}%");
      })
      ->orderBy("missions.updated_at", "DESC")
      ->simplePaginate(
        $perPage = 20,
        $columns = [
          "missions.id", "missions.ip", "missions.origin_url", "missions.updated_at", "missions.reward",
          "pages.url", "pages.price_per_traffic", "pages.hold_percentage"
        ]
      );
    return view('admin.missions', compact(['missions']));
  }

  // Management User - UserType

  public function managementUsers()
  {
    $userTypes = UserType::get();
    $users = User::where([['is_admin', 0]])->simplePaginate(10);
    return view('admin.users', compact(['userTypes', 'users']));
  }

  public function searchUser(Request $request)
  {
    $sdt = $request->data;
    $users = User::where('username', 'LIKE', "%{$sdt}%")->simplePaginate(10);
    return view('admin.users', compact(['users']));
  }

  public function postCreateUserType(Request $request)
  {
    // dd(json_decode($request->mission_need, true));
    // validate data
    $validated = $request->validate([
      'name' => 'required|max:255',
      'mission_need' => 'required',
      'page_weight' => 'required'
    ]);
    $name = $validated['name'];
    $missionNeed = json_decode($validated['mission_need'], true);
    $pageWeight = json_decode($validated['page_weight'], true);

    foreach ($pageWeight as $key => $value) {
      $pageType = PageType::where('id', $key)->get();
      if (!$pageType->first()) {
        return redirect()->to('/management/users')->with("error", "Page type not correct id");
      }
    }
    foreach ($missionNeed as $key => $value) {
      if ($value < 0) {
        return redirect()->to('/management/users')->with("error", "Mission need must greater than zero");
      }
      $pageType = PageType::where('id', $key)->get();
      if (!$pageType->first()) {
        return redirect()->to('/management/users')->with("error", "Page type not correct id");
      }
    }
    $userType = new UserType();
    $userType->name = $name;
    $userType->mission_need = $missionNeed;
    $userType->page_weight = $pageWeight;

    $userType->save();

    return redirect()->to('/management/users');
  }
  public function editUserType(Request $request)
  {
    // dd(json_decode($request->mission_need, true));
    // validate data
    $validated = $request->validate([
      'id' => 'required',
      'name' => 'required',
      'mission_need' => 'required',
      'page_weight' => 'required'
    ]);
    $name = $validated['name'];
    $missionNeed = json_decode($validated['mission_need'], true);
    $pageWeight = json_decode($validated['page_weight'], true);

    $userType = UserType::where('id', $validated['id']);
    if (!$userType->get()->first()) {
      return redirect()->to('/management/users')->with("error", "User type not correct id");
    }

    foreach ($pageWeight as $key => $value) {
      $pageType = PageType::where('id', $key);
      if (!$pageType->get()->first()) {
        return redirect()->to('/management/users')->with("error", "Page type not correct id");
      }
    }

    foreach ($missionNeed as $key => $value) {
      if ($value < 0) {
        return redirect()->to('/management/users')->with("error", "Mission need must greater than zero");
      }
      $pageType = PageType::where('id', $key)->get();
      if (!$pageType->first()) {
        return redirect()->to('/management/users')->with("error", "Page type not correct id");
      }
    }

    $userType->update([
      'name' => $name,
      'mission_need' => $missionNeed,
      'page_weight' => $pageWeight,
    ]);

    return redirect()->to('/management/users');
  }

  public function postChangeUserType(Request $request, $id)
  {
    // Edit user user_type
    $userTypeID = $request['user_type_id'];

    // $user = User::where('id', $id)->first();
    $user = User::where('id', $id)->first();
    if ($user) {
      $type = UserType::where('id', $userTypeID)
        ->get('id')
        ->first();
      if ($type) {
        // $user->user_type_id = $type->id;
        // $user->mission_count = $type->mission_need;
        // $user->save();
        User::where('id', $id)->update(['user_type_id' => $type->id]);
      }
    }
    return redirect()->to('/management/users');
  }

  public function postUnblockUser(Request $request, $id)
  {
    $user = User::where('id', $id)->first();
    if ($user and $user->status == 0) {
      $user->status = 1;
      $user->mission_attempts = 0;
      $user->save();
    }
    return redirect()->to('/management/users');
  }
  public function postBlockUser(Request $request, $id)
  {
    $user = User::where('id', $id)->first();
    if ($user and $user->status == 1) {
      $user->status = 0;
      $user->save();
    }
    return redirect()->to('/management/users');
  }

  public function registerManual(Request $request) //only admin
  {
    if (!isset($request->name)) {
      return Redirect::to('/management/users');
    }
    if (!isset($request->password)) {
      return Redirect::to('/management/users')->with('error', 'Không đầy đủ thông tin cần thiết!');
    }
    $checkUser = User::where('username', $request->name);
    if ($checkUser->count() != 0) {
      return Redirect::to('/management/users')->with('error', 'Tên tài khoản đã có người sử dụng!');
    }
    $request->validate([
      'name' => 'required',
      'phone' => 'required|digits:10',
      'password' => 'required'
    ], [
      'phone.digits' => 'SĐT không phù hợp'
    ]);
    $input = $request->all();
    DB::transaction(function () use ($input) {
      $type =  UserType::where('is_default', 1)->get('id')->first();
      $user = new User();
      $user->username = $input['name'];
      $user->phone_number = $input['phone'];
      $user->password = bcrypt($input['password']);
      $user->is_admin = 0;
      $user->status = 1; // Set status to inactive / unverfied
      $user->user_type_id = $type->id;
      $user->wallet = 0;
      $user->verified = 1;
      $user->commission = 0;
      $user->reference = "5326bb69-45d0-496b-9ca5-5e3999380074";
      $user->save();
    });

    // Send otp sms
    return Redirect::to('/management/users')->with('message', 'Đăng ký thành công!');
  }

  public function changePassword(Request $request, $id)
  {
    $request->validate([
      'pwd' => 'required',
    ], [
      'pwd.required' => "Vui lòng nhập mật khẩu"
    ]);
    $user  = User::where("id", $id);
    if (!empty($user->get())) {
      $user->update(['password' => bcrypt($request['pwd'])]);
      # code...
      return Redirect::to('/management/users')->with(['message' => 'Đặt lại mật khẩu của người dùng thành công']);
    }
  }

  public function addMoneyForUser(Request $request, $id)
  {
    $request->validate([
      'amount' => 'required|numeric|not_in:0',
    ], [
      'amount.required' => "Vui lòng nhập số tiền",
      'amount.numeric' => "Vui lòng nhập đúng định dạng"
    ]);
    $user = User::where('id', $id);
    $logData = array();
    $logData['type'] = $request["amount"] < 0 ? TransactionTypeConstants::ADMIN_MINUS : TransactionTypeConstants::ADMIN_ADD;
    $logData['amount'] = $request["amount"] < 0 ? ($request["amount"] * -1) : $request["amount"];

    if (!empty($user->get())) {
      $userData = $user->first();
      if (
        $logData['type'] == TransactionTypeConstants::ADMIN_MINUS &&
        $logData['amount'] > $userData->wallet
      ) {
        return Redirect::to('/management/users')->with(['message' => 'Lỗi không đủ tiền trong ví tài khoản!']);
      }
      $logData['before'] = $userData->wallet;
      $logData['user_id'] = $userData->id;
      $user->increment('wallet', $request['amount']);
      $userData = $user->first();
      $logData['after'] = $userData->wallet;
      $logData['status'] = TransactionStatusConstants::APPROVED;
      $log = new LogMissionTransaction($logData);
      $log->save();
      return Redirect::to('/management/users')->with(['message' => 'Thành công']);
    }
    return Redirect::to('/management/users')->with(['message' => 'Lỗi']);
  }

  public function showUserTransactions(Request $request, $id)
  {
    // $logTable = $request->query('type') === 'traffic' ? 'log_traffic_transactions' : 'log_mission_transactions';
    // $userTable = $request->query('type') === 'traffic' ? 'user_traffics' : 'user_missions';

    $logTable = 'log_mission_transactions';
    $userTable = 'user_missions';

    $user = User::where('id', $id)->first();
    if (!$user) {
      return view('admin.userTransactions')->withErrors("User không tồn tại");
    }

    $transactions = DB::table($logTable)->where(['user_id' => $id, $logTable . '.status' => TransactionStatusConstants::APPROVED])
      ->join($userTable, $userTable . '.id', '=', $logTable . '.user_id')
      ->select($logTable . '.amount', $logTable . '.before', $logTable . '.after', $logTable . '.created_at', $logTable . '.type', $logTable . '.status', $userTable . '.username')
      ->orderBy('created_at', 'desc')
      ->simplePaginate(15);

    return view('admin.userTransactions', compact(['transactions', 'user']));
  }

  public function showUsersTransactions(Request $request)
  {
    // $type = $request->query('type') === 'traffic' ? 'traffic' : 'mission';
    $type = 'mission';
    // $logTable = $request->query('type') === 'traffic' ? 'log_traffic_transactions' : 'log_mission_transactions';
    // $userTable = $request->query('type') === 'traffic' ? 'user_traffics' : 'user_missions';

    $logTable = 'log_mission_transactions';
    $userTable = 'user_missions';

    $username = $request->has('username') ? $request->query('username') : "";
    $fromDay = $request->has('from') ? $request->query('from') : "";
    $toDay = $request->has('to') ? $request->query('to') : "";
    // $sort = $request->query('sortIn') ?: $request->query('sortOut') ?: 'desc';
    $sort = $request->query('sort') ?: 'desc';
    $typeSort = 'created_at';
    // if ($request->query('sortIn')) $typeSort = 'total_income';
    // else if ($request->query('sortOut')) $typeSort = 'total_outcome';

    $data = DB::table($logTable) //->where([$logTable.'.status' => TransactionStatusConstants::APPROVED])
      ->where($logTable . '.status', TransactionStatusConstants::APPROVED)
      ->join($userTable, $userTable . '.id', '=', $logTable . '.user_id')
      ->select(
        $userTable . '.username',
        DB::raw(
          'SUM(
          CASE WHEN type = "' . TransactionTypeConstants::ADMIN_ADD . '" or type = "' . TransactionTypeConstants::REWARD . '" or type = "' . TransactionTypeConstants::TOPUP . '" or type = "' . TransactionTypeConstants::COMMISSION . '"
          THEN amount
          ELSE 0 END
        ) as total_income'
        ),
        DB::raw(
          'SUM(
          CASE WHEN type = "' . TransactionTypeConstants::ADMIN_MINUS . '" or type = "' . TransactionTypeConstants::PAY . '" or type = "' . TransactionTypeConstants::WITHDRAW . '"
          THEN amount
          ELSE 0 END
        ) as total_outcome'
        ),
        DB::raw(
          'date_format(' . $logTable . '.created_at, "%d-%m-%Y") as created_at'
        )
      )
      ->where([$logTable . '.status' => TransactionStatusConstants::APPROVED]);

    if (!empty($username)) {
      $data = $data->where('username', 'like', "{$username}");
    }
    if (!empty($fromDay) and !empty($toDay)) {
      $fromDay = Carbon::createFromFormat("Y-m-d", $fromDay)->startOfDay();
      $toDay = Carbon::createFromFormat("Y-m-d", $toDay)->addDay()->startOfDay();
      $data = $data->whereBetween($logTable . ".created_at", [$fromDay, $toDay]);
    }
    $data = $data->groupBy([DB::raw('DAY(' . $logTable . '.created_at)'), 'username'])
      ->orderBy($typeSort, $sort)
      ->simplePaginate(20);

    return view('admin.userHistories', compact(['data', 'username', 'fromDay', 'toDay', 'type', 'sort']));
  }

  public function managementSettings()
  {
    $settings = Setting::all();
    return view('admin.setting', compact(['settings']));
  }

  public function changeSettingValue(Request $request)
  {
    $validated = $request->validate([
      "minimum_reward" => "required|numeric",
      "minimum_withdraw" => "required|numeric",
      "delay_day_week" => "required|numeric",
      "delay_day_month" => "required|numeric",
      "commission_rate_1" => "required|numeric",
      "commission_rate_2" => "required|numeric",
      "max_ref_user_per_day_week" => "required|numeric",
      "max_ref_user_per_day_month" => "required|numeric",
      "ref_user_required_week" => "required|numeric",
      "ref_user_required_month" => "required|numeric",
    ]);
    foreach ($validated as $key => $value) {
      $setting = Setting::where("name", $key)->first();
      if (!$setting) {
        continue;
      }
      $setting->update([
        "value" => $value
      ]);
    }
    return redirect()->to('/management/setting');
  }

  public function viewErrors()
  {
    return view("admin.tableErrors");
  }
}
