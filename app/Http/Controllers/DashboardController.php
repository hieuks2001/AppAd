<?php

namespace App\Http\Controllers;

use App\Constants\PagePriorityConstants;
use App\Constants\PageStatusConstants;
use App\Constants\OntimeTypeConstants;
use App\Constants\TransactionStatusConstants;
use App\Constants\MissionStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogMissionTransaction;
use App\Models\LogTrafficTransaction;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\PageType;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserMission;
use App\Models\UserType;
use Brick\Math\Exception\NumberFormatException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use ReflectionClass;
use stdClass;

class DashboardController extends Controller
{
  public function managementTraffic()
  {
    $pages = Page::where('status', PageStatusConstants::APPROVED)->simplePaginate(10);
    $notApprovedPages = Page::where('status', PageStatusConstants::PENDING)->simplePaginate(5);
    return view('admin.traffic', compact(['pages', 'notApprovedPages']));
  }

  public function searchTrafficApproved(Request $request)
  {
    $key = $request->data;
    $pages = Page::whereHas('user', function ($query) use ($key) {
      $query->where('url', 'LIKE', "%{$key}%");
      $query->orWhere('username', 'LIKE', "%{$key}%");
    })
      ->where('status', PageStatusConstants::APPROVED)
      ->simplePaginate(10);
    $notApprovedPages = Page::where('status', PageStatusConstants::PENDING)->simplePaginate(5);
    return view('admin.traffic', compact(['pages', 'notApprovedPages']));
  }

  public function searchTrafficNotApproved(Request $request)
  {
    $key = $request->data;
    $pages = Page::where('status', PageStatusConstants::APPROVED)->simplePaginate(10);
    $notApprovedPages = Page::whereHas('user', function ($query) use ($key) {
      $query->where('url', 'LIKE', "%{$key}%");
      $query->orWhere('username', 'LIKE', "%{$key}%");
    })
      ->where('status', PageStatusConstants::PENDING)
      ->simplePaginate(5);
    return view('admin.traffic', compact(['pages', 'notApprovedPages']));
  }

  public function managementMissions()
  {
    $missions = Page::join("missions", "missions.page_id", "=", "pages.id")
      ->where([
        ['pages.status', "=", PageStatusConstants::APPROVED],
        ['missions.status', "=", MissionStatusConstants::COMPLETED],
      ])
      ->orderBy("pages.price_per_traffic", "DESC")
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
      ->orderBy("pages.price_per_traffic", "DESC")
      ->simplePaginate(
        $perPage = 20,
        $columns = [
          "missions.id", "missions.ip", "missions.origin_url", "missions.updated_at", "missions.reward",
          "pages.url", "pages.price_per_traffic", "pages.hold_percentage"
        ]
      );
    return view('admin.missions', compact(['missions']));
  }

  public function getApproveTraffic($id)
  {
    $priority = new ReflectionClass(PagePriorityConstants::class);
    $page = Page::where('status', PageStatusConstants::PENDING)->where('id', $id)->first();
    if (!$page) {
      return redirect()->to('/management/traffic');
    }
    return view('admin.editTraffic')->with('page', $page)->with('priority', $priority->getConstants());
  }

  public function postApproveTraffic(Request $request, $id)
  {
    $page = Page::where('id', $id)->first();
    $user = $page->user;

    if ($user->wallet >= $page->price) {
      DB::transaction(function () use ($page, $user) {
        $page->status = PageStatusConstants::APPROVED;

        $log = new LogTrafficTransaction();
        $log->user_id = $page->user_id;
        $log->amount  = $page->price * -1;
        $log->type = TransactionTypeConstants::PAY;
        $log->status = TransactionStatusConstants::APPROVED;

        // DB::table('users')->where('id', $page->user_id)->decrement('wallet', $page->price);
        DB::table('user_traffics')->where('id', $page->user_id)->decrement('wallet', $page->price);
        $page->save();
        $log->save();
      });
      return redirect()->to('/management/traffic');
    } else {
      return redirect()->to('/management/traffic')->with("error", "Người dùng này không có đủ tiền!.");
    }
  }

  public function postEditTraffic(Request $request, $id)
  {
    $request->validate([
      'page_type' => 'required|uuid',
      'timeout' => 'required',
      'hold_percentage' => 'required|min:1|max:100'
    ]);
    $page = Page::where('id', $id)->first();

    try {
      // Store page image
      if ($request->file('image')) {
        $oldImage = $page->image;

        $filename = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images'), $filename);
        $page->image = $filename;

        // Delete old file
        if (!empty($oldImage)) {
          File::delete(public_path('images') . DIRECTORY_SEPARATOR . $oldImage);
        }
      }

      if ($request['page_type']) {
        $page_type = PageType::where('id', $request['page_type'])->first();
        $page->price_per_traffic = $page_type->onsite[$page->onsite];
        $page->price = $page->traffic_sum * $page->price_per_traffic;
        $page->page_type_id = $page_type->id;
      }

      if ($request['timeout']) {
        if (Carbon::parse($request['timeout'])) {
          $page->timeout = $request['timeout'];
        }
      }

      if ($request['hold_percentage']) {
        $hold = $request['hold_percentage'];
        if ($hold <= 0  || $hold > 100) {
          throw new NumberFormatException('hold_percentage not in correct format (1 - 100)');
        }
        $page->hold_percentage = $hold;
      }

      $page->priority = $request['priority'];

      $page->note = $request['note'];

      $page->save();
    } catch (\Throwable $th) {
      dd($th);
      File::delete(public_path('images') . DIRECTORY_SEPARATOR . $filename);
      return redirect()->to('/management/traffic');
    }

    return redirect()->to('/management/traffic');
  }

  public function delApproveTraffic($id)
  {
    $page = Page::where('id', $id)->first();
    $user = Auth::user();

    DB::transaction(function () use ($page, $user) {
      $page->status = PageStatusConstants::CANCEL;

      $log = new LogTrafficTransaction();
      $log->user_id = $page->user_id;
      $log->amount  = $page->price;
      $log->type = TransactionTypeConstants::REFUND;
      $log->status = TransactionStatusConstants::APPROVED;

      // Delete image file
      if (!empty($page->image)) {
        File::delete(public_path('images') . DIRECTORY_SEPARATOR . $page->image);
      }


      DB::table('users')->where('id', $page->user_id)->increment('wallet', $page->price);

      $page->save();
      $log->save();
    });

    return redirect()->to('/management/traffic');
  }

  // Management User - UserType

  public function managementUsers()
  {
    $userTypes = UserType::get();
    $users = UserMission::where([['status', 1], ['is_admin', 0]])->simplePaginate(10);
    $usersTraffic = User::where([['status', 1], ['is_admin', 0]])->simplePaginate(10);
    return view('admin.users', compact(['userTypes', 'users', 'usersTraffic']));
  }

  public function searchUser(Request $request)
  {
    $sdt = $request->data;
    $users = UserMission::where('username', 'LIKE', "%{$sdt}%")->simplePaginate(10);
    $usersTraffic = User::where([['status', 1], ['is_admin', 0]])->simplePaginate(10);
    return view('admin.users', compact(['users', 'usersTraffic']));
  }

  public function searchUserTraffic(Request $request)
  {
    $sdt = $request->data;
    $usersTraffic = User::where('username', 'LIKE', "%{$sdt}%")->simplePaginate(10);
    $users = UserMission::where([['status', 1], ['is_admin', 0]])->simplePaginate(10);
    return view('admin.users', compact(['users', 'usersTraffic']));
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
    $user = UserMission::where('id', $id)->first();
    if ($user) {
      $type = UserType::where('id', $userTypeID)
        ->get('id')
        ->first();
      if ($type) {
        // $user->user_type_id = $type->id;
        // $user->mission_count = $type->mission_need;
        // $user->save();
        UserMission::where('id', $id)->update(['user_type_id' => $type->id]);
      }
    }
    return redirect()->to('/management/users');
  }

  public function postUnblockUser(Request $request, $id)
  {
    $user = User::where('id', $id)->first();
    if ($user and $user->status == 0) {
      $user->status = 1;
      $user->save();
    }
    return redirect()->to('/management/users');
  }

  public function postApproveTrafficTransaction(Request $rq, $id)
  {
    $transaction = LogTrafficTransaction::where([
      'id' => $id,
      'status' => TransactionStatusConstants::PENDING,
    ]);
    if (!$transaction) {
      return;
    }
    DB::transaction(function () use ($transaction) {
      $rs = DB::table('user_traffics')->where('id', $transaction->user_id)->increment('wallet', $transaction->amount);
      $transaction->status = TransactionStatusConstants::APPROVED;
    });
    return;
  }

  public function postCancelTrafficTransaction(Request $rq, $id)
  {
    $transaction = LogTrafficTransaction::where([
      'id' => $id,
      'status' => TransactionStatusConstants::PENDING,
    ]);
    if (!$transaction) {
      return;
    }
    $transaction->status = TransactionStatusConstants::CANCELED;
    $transaction->save();
    return;
  }

  public function postApproveMissionTransaction(Request $rq, $id)
  {
    $transaction = LogMissionTransaction::where([
      'id' => $id,
      'status' => TransactionStatusConstants::PENDING,
    ]);
    if (!$transaction) {
      return;
    }
    DB::transaction(function () use ($transaction) {
      $rs = UserMission::where('id', $transaction->user_id)->increment('wallet', $transaction->amount);
      $transaction->status = TransactionStatusConstants::APPROVED;
    });
    return;
  }

  public function postCancelMissionTransaction(Request $rq, $id)
  {
    $transaction = LogMissionTransaction::where([
      'id' => $id,
      'status' => TransactionStatusConstants::PENDING,
    ]);
    if (!$transaction) {
      return;
    }
    $transaction->status = TransactionStatusConstants::CANCELED;
    $transaction->save();
    return;
  }

  public function registerManual(Request $request) //only admin
  {
    if (!isset($request->name)) {
      return Redirect::to('/management/users');
    }
    if (!isset($request->password)) {
      return Redirect::to('/management/users')->with('error', 'Không đầy đủ thông tin cần thiết!');
    }
    $checkUser = UserMission::where('username', $request->name);
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
      $user = new UserMission();
      $user->username = $input['name'];
      $user->phone_number = $input['phone'];
      $user->password = bcrypt($input['password']);
      $user->is_admin = 0;
      $user->status = 1; // Set status to inactive / unverfied
      $user->user_type_id = $type->id;
      $user->wallet = 0;
      $user->verified = 1;
      $user->commission = 0;
      $user->save();
    });

    // Send otp sms
    return Redirect::to('/management/users')->with('message', 'Đăng ký thành công!');
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

  public function getPageTypes(Request $request)
  {
    # code...
    $pageTypes = PageType::orderBy("name")->get();
    $pageTypeInit = [];
    $pageTypeInit[OntimeTypeConstants::TYPE_60] = "";
    $pageTypeInit[OntimeTypeConstants::TYPE_70] = "";
    $pageTypeInit[OntimeTypeConstants::TYPE_90] = "";
    $pageTypeInit[OntimeTypeConstants::TYPE_120] = "";
    $pageTypeInit[OntimeTypeConstants::TYPE_150] = "";
    return view("admin.pageTypes")->with(["pageTypes" => $pageTypes, "pageTypeInit" => $pageTypeInit]);
  }
  public function createPageType(Request $request)
  {
    # code...
    $validated = $request->validate([
      OntimeTypeConstants::TYPE_60 . "s" => "required|numeric",
      OntimeTypeConstants::TYPE_70 . "s" => "required|numeric",
      OntimeTypeConstants::TYPE_90 . "s" => "required|numeric",
      OntimeTypeConstants::TYPE_120 . "s" => "required|numeric",
      OntimeTypeConstants::TYPE_150 . "s" => "required|numeric",
    ]);
    $lastPageTypeExist = PageType::orderBy("name", "desc")->first();
    DB::transaction(function () use ($validated, $lastPageTypeExist) {
      $pageType = new PageType();
      $pageType->name = (string)((int)$lastPageTypeExist->name + 1);
      $pageType->onsite = [
        (int)OntimeTypeConstants::TYPE_60 => (float)$validated[OntimeTypeConstants::TYPE_60 . "s"],
        (int)OntimeTypeConstants::TYPE_70 => (float)$validated[OntimeTypeConstants::TYPE_70 . "s"],
        (int)OntimeTypeConstants::TYPE_90 => (float)$validated[OntimeTypeConstants::TYPE_90 . "s"],
        (int)OntimeTypeConstants::TYPE_120 => (float)$validated[OntimeTypeConstants::TYPE_120 . "s"],
        (int)OntimeTypeConstants::TYPE_150 => (float)$validated[OntimeTypeConstants::TYPE_150 . "s"],
      ];
      $pageType->save();
    });
    return Redirect::to("/management/pages");
  }
  public function editPageTypes(Request $request, $id)
  {
    # code...
    (float)$validated = $request->validate([
      OntimeTypeConstants::TYPE_60 . "s" => "required|numeric",
      OntimeTypeConstants::TYPE_70 . "s" => "required|numeric",
      OntimeTypeConstants::TYPE_90 . "s" => "required|numeric",
      OntimeTypeConstants::TYPE_120 . "s" => "required|numeric",
      OntimeTypeConstants::TYPE_150 . "s" => "required|numeric",
    ]);
    $pageType = PageType::where('id', $id)->update([
      'onsite' => [
        (int)OntimeTypeConstants::TYPE_60 => (float)$validated[OntimeTypeConstants::TYPE_60 . "s"],
        (int)OntimeTypeConstants::TYPE_70 => (float)$validated[OntimeTypeConstants::TYPE_70 . "s"],
        (int)OntimeTypeConstants::TYPE_90 => (float)$validated[OntimeTypeConstants::TYPE_90 . "s"],
        (int)OntimeTypeConstants::TYPE_120 => (float)$validated[OntimeTypeConstants::TYPE_120 . "s"],
        (int)OntimeTypeConstants::TYPE_150 => (float)$validated[OntimeTypeConstants::TYPE_150 . "s"],
      ]
    ]);
    return Redirect::to("/management/pages");
  }
  public function changePassword(Request $request, $id)
  {
    $request->validate([
      'pwd' => 'required',
    ], [
      'pwd.required' => "Vui lòng nhập mật khẩu"
    ]);
    $table = $request->query('type') === 'traffic' ? 'user_traffics' : 'user_missions';
    $user  = DB::table($table)->where("id", $id);
    if (!empty($user->get())) {
      $user->update(['password' => bcrypt($request['pwd'])]);
      # code...
      return Redirect::back()->with(['message' => 'Đặt lại mật khẩu của người dùng thành công']);
    }
  }

  public function addMoneyForUser(Request $request, $id){
    $request->validate([
      'amount' => 'required|numeric|not_in:0',
    ], [
      'amount.required' => "Vui lòng nhập số tiền",
      'amount.numeric' => "Vui lòng nhập đúng định dạng"
    ]);
    $table = $request->query('type') === 'traffic' ? 'user_traffics' : 'user_missions';
    $user = DB::table($table)->where('id', $id);
    $logData = array();
    $logData['type'] = $request["amount"] < 0 ? TransactionTypeConstants::ADMIN_MINUS : TransactionTypeConstants::ADMIN_ADD;
    $logData['amount'] = $request["amount"] < 0 ? ($request["amount"] * -1) : $request["amount"];

    if (!empty($user->get())) {
      $userData = $user->first();
      $logData['before'] = $userData->wallet;
      $logData['user_id'] = $userData->id;
      $user->increment('wallet', $request['amount']);
      $userData = $user->first();
      $logData['after'] = $userData->wallet;
      $logData['status'] = TransactionStatusConstants::APPROVED;
      // Add log
      if ($request->query('type') === 'traffic'){
        $log = new LogTrafficTransaction($logData);
        $log->save();
      } else {
        $log = new LogMissionTransaction($logData);
        $log->save();
      }
      return Redirect::back()->with(['message' => 'Thành công']);
    }
    return Redirect::back()->with(['message' => 'Lỗi']);
  }

  public function showUserTransactions(Request $request, $id){
    $logTable = $request->query('type') === 'traffic' ? 'log_traffic_transactions' : 'log_mission_transactions';
    $userTable = $request->query('type') === 'traffic' ? 'user_traffics' : 'user_missions';

    $user = DB::table($userTable)->where('id', $id)->first();
    if (!$user){
      return view('admin.userTransactions')->withErrors("User không tồn tại");
    }

    $transactions = DB::table($logTable)->where(['user_id' => $id, $logTable.'.status' => TransactionStatusConstants::APPROVED])
      ->join($userTable, $userTable.'.id' , '=', $logTable.'.user_id')
      ->select($logTable.'.amount', $logTable.'.before', $logTable.'.after', $logTable.'.created_at', $logTable.'.type', $logTable.'.status', $userTable.'.username')
      ->simplePaginate(15);

    return view('admin.userTransactions', compact(['transactions', 'user']));
  }
}
