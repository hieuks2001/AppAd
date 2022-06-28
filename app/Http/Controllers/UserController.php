<?php

namespace App\Http\Controllers;

use App\Models\Missions;
use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Web3\Web3;
use Web3\Contract;
use Web3\Utils;

class UserController extends Controller
{
  public function login(Request $request)
  {
    if (Auth::check()) {
      return Redirect::to('/');
    }
    if (isset($request->username)) {
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
    if (isset($request->username)) {
      if ($request->password != $request->re_password) {
        return Redirect::to('/register')->with('error', 'Mật khẩu không trùng khớp!');
      } else {
        $type =  DB::table('page_types')->orderBy('mission_need', 'asc')->first();
        $user = new User();
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->is_admin = 0;
        $user->status = 1;
        $user->wallet = 0;
        $user->page_type_id = $type->id;
        $user->commission = 0;
        $user->save();
        return Redirect::to('/login')->with('message', 'Đăng ký thành công!');
      }
    } else {
      return view('procedure.register');
    }
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
      return view("dashboard.index");
    } else {
      return Redirect::to('/login');
    }
  }

  // ================== MISSIONS ==========================
  public function pastekey(Request $request)
  {
    $user = Auth::user();
    //rule here
    $ms = Missions::where('user_id', $user->id)->where([
      ["ip", $request->ip()],
      ["user_agent", $request->userAgent()],
      ["status", 0]
    ]);
    $msCode = $ms->get("code")->first()->code;
    if ($msCode == $request->key) {
      $ms->update(["status" => 1]);
      return Redirect::to('/tu-khoa');
    } else {
      return Redirect::to('/tu-khoa')->withErrors('Sai mã!');
    }
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
    $rs = $wallet->update(['wallet' => $wallet->wallet + $amount]);
    return Redirect::to("/deposit");
  }

  public function withdrawView()
  {
    $abi = '[{"inputs":[],"payable":false,"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"owner","type":"address"},{"indexed":true,"internalType":"address","name":"spender","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Approval","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"previousOwner","type":"address"},{"indexed":true,"internalType":"address","name":"newOwner","type":"address"}],"name":"OwnershipTransferred","type":"event"},{"anonymous":false,"inputs":[{"indexed":true,"internalType":"address","name":"from","type":"address"},{"indexed":true,"internalType":"address","name":"to","type":"address"},{"indexed":false,"internalType":"uint256","name":"value","type":"uint256"}],"name":"Transfer","type":"event"},{"constant":true,"inputs":[],"name":"_decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"_symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"owner","type":"address"},{"internalType":"address","name":"spender","type":"address"}],"name":"allowance","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"approve","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[{"internalType":"address","name":"account","type":"address"}],"name":"balanceOf","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"burn","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"decimals","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"subtractedValue","type":"uint256"}],"name":"decreaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"getOwner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"spender","type":"address"},{"internalType":"uint256","name":"addedValue","type":"uint256"}],"name":"increaseAllowance","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"mint","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"name","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[],"name":"renounceOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":true,"inputs":[],"name":"symbol","outputs":[{"internalType":"string","name":"","type":"string"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":true,"inputs":[],"name":"totalSupply","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"payable":false,"stateMutability":"view","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transfer","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"sender","type":"address"},{"internalType":"address","name":"recipient","type":"address"},{"internalType":"uint256","name":"amount","type":"uint256"}],"name":"transferFrom","outputs":[{"internalType":"bool","name":"","type":"bool"}],"payable":false,"stateMutability":"nonpayable","type":"function"},{"constant":false,"inputs":[{"internalType":"address","name":"newOwner","type":"address"}],"name":"transferOwnership","outputs":[],"payable":false,"stateMutability":"nonpayable","type":"function"}]';
    $web3 = new Web3('https://bsc-dataseed.binance.org');
    $contract = new Contract($web3->provider, $abi);
    $temp = $contract->at("0x55d398326f99059fF775485246999027B3197955")->call("balanceOf", "0xB3822db2D50F93dED229711391e7801Db8858Ab2", function ($err, $data) {
      if ($err !== null) {
        return view("usdt.withdraw")->withErrors("error");
      }
      error_log(print_r(Utils::toString($data[0]), true));
      return $data;
    });
    error_log(print_r($temp, true));
    return view("usdt.withdraw", ["data" => $temp]);
  }

  public function withdraw(Request $request)
  {
    return view("usdt.deposit");
  }
}
