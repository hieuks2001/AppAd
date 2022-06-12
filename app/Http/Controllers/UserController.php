<?php

namespace App\Http\Controllers;

use App\Models\Missions;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
  public function AuthLogin()
  {

    $user = Session::get('user');
    if (!$user) {
      return Redirect::to('/login')->send();
    } else {
      return Redirect::to('/');
    }
  }

  public function login(Request $request)
  {
    $user = Session::get('user');
    if ($user) {
      return Redirect::to('/');
    }
    if (isset($request->username)) {
      $user = DB::table('users')->where('password', md5($request->password))->where('username', $request->username)->first();
      if ($user) {
        Session::put('user', $user);
        return Redirect::to('/');
      } else {
        return Redirect::to('/login')->with('error', 'Sai tên đăng nhập hoặc mật khẩu!');
      }
    } else {
      return view('procedure.login');
    }
  }
  public function register(Request $request)
  {
    if (isset($request->username)) {
      if ($request->password != $request->re_password) {
        return Redirect::to('/register')->with('error', 'Mật khẩu không trùng khớp!');
      } else {
        $user = new User();
        $user->user_uuid = Str::uuid();
        $user->username = $request->username;
        $user->password = md5($request->password);
        $user->wallet = 0;
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
    $this->AuthLogin();
    // $ms = DB::table('missions')->where('ms_userUUID', Session::get('user')->user_uuid)->where('ms_status', 'already')->first();
    // $missons = DB::table('missions')->where('ms_userUUID', Session::get('user')->user_uuid)->get();
    // // if ($ms) {
    // //   return Redirect::to('/tu-khoa')->with('error', "");
    // // } else {
    // //   return view('mission.mission', ['missions' => $missons]);
    // // }
    return view("dashboard.index");
  }

  // ================== MISSIONS ==========================
  public function pastekey(Request $request)
  {
    $this->AuthLogin();

    $ms = DB::table('missions')->where('ms_userUUID', Session::get('user')->user_uuid)->where('ms_status', 'already')->first();
    if ($ms->ms_code == $request->key) {
      $user = DB::table('users')->where('user_uuid', Session::get('user')->user_uuid)->first();
      print_r($user);
      $us = DB::table('users')->where('user_uuid', Session::get('user')->user_uuid)->update(
        ['wallet' => $user->wallet + $ms->ms_price]
      );
      $ms = DB::table('missions')->where('ms_userUUID', Session::get('user')->user_uuid)->where('ms_status', 'already')->update(['ms_status' => 'done']);

      return Redirect::to('/');
    } else {
      return redirect()->back()->with('loi', 'Sai mã!');
    }
  }

  public function tukhoa()
  {
    $this->AuthLogin();
    $ms = DB::table('missions')->where('ms_userUUID', Session::get('user')->user_uuid)->where('ms_status', 'already')->first();
    $missons = DB::table('missions')->where('ms_userUUID', Session::get('user')->user_uuid)->get();
    if ($ms) {
      $page = DB::table('pages')->where('page_name', $ms->ms_name)->first();
      return view('mission.mission', ['mission' => $ms, 'missions' => $missons, 'page' => $page]);
    } else {
      // $all_missions = DB::table('missions')->where('ms_status', 'already')->get();
      // $pages = DB::table('pages')->get();
      // $list = [];
      // foreach ($all_missions as $key => $value) {
      //   array_push($list, $value->ms_name);
      // }
      // print_r($list);
      // foreach ($pages as $key => $value) {
      //   if (!in_array($value->page_name, $list)) {
      //     // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      //     // $randomkey = substr(str_shuffle($permitted_chars), 0, 4) . '88';
      //     $mission = new Missions();
      //     $mission->ms_name = $value->page_name;
      //     $mission->ms_userUUID = Session::get('user')->user_uuid;
      //     $mission->ms_countdown = 60;
      //     $mission->ms_price = 0.35;
      //     $mission->ms_status = 'already';
      //     $mission->save();
      //     return Redirect::to('/');
      //   } else {
      //     return Redirect::to('/tu-khoa')->with('error', 'Nhận nhiệm vụ không thành công!');
      //   }
      // }
      return view('mission.mission', ['missions' => $missons])->withErrors("Bạn chưa nhận nhiệm vụ!");
    }
  }
  public function getMission()
  {
    $this->AuthLogin();
    $all_missions = DB::table('missions')->where('ms_status', 'already')->get();
    $pages = DB::table('pages')->get();
    $list = [];
    foreach ($all_missions as $key => $value) {
      array_push($list, $value->ms_name);
    }
    print_r($list);
    foreach ($pages as $key => $value) {
      if (!in_array($value->page_name, $list)) {
        // $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $randomkey = substr(str_shuffle($permitted_chars), 0, 4) . '88';
        $mission = new Missions();
        $mission->ms_name = $value->page_name;
        $mission->ms_userUUID = Session::get('user')->user_uuid;
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

  public function cancelmission()
  {
    $ms = DB::table('missions')->where('ms_userUUID', Session::get('user')->user_uuid)->where('ms_status', 'already')->update(['ms_status' => 'cancel']);
    return Redirect::to('/tu-khoa');
  }

  // ==================END MISSIONS ==========================


  // ====================== PAGES ============================
  public function regispage()
  {
    return view('regispage.tab1');
  }
  public function regispageTab1()
  {
    return view('regispage.tab1');
  }
  public function regispageTab2()
  {
    return view('regispage.tab2');
  }
  public function regispageTab3()
  {
    return view('regispage.tab3');
  }
  public function regispageTab4()
  {
    return view('regispage.tab4');
  }
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

  public function deposit(Request $request)
  {
    $this->AuthLogin();
    return view("usdt.deposit");
  }
  public function withdraw(Request $request)
  {
    $this->AuthLogin();
    return view("usdt.withdraw");
  }
  // ====================== END PAGES ============================

}
