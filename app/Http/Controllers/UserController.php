<?php

namespace App\Http\Controllers;

use App\Models\Missions;
use App\Models\User;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

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
                if (Auth::user()->isAdmin){
                    return Redirect::to('/admin/dashboard');
                }
                return Redirect::to('/');
            } else {
                return Redirect::to('/login')->with('error', 'Sai tên đăng nhập hoặc mật khẩu!');
            }
        } else {
            return view('procedure.login');
        }
    }

    public function logout(){
        Auth::logout();
        return Redirect::to('/login');
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
                $user->password = bcrypt($request->password);
                $user->isAdmin = 0;
                $user->status = 1;
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
        $user = Auth::user();
        $ms = Missions::where('ms_userUUID', $user->user_uuid)->where('ms_status', 'already')->first();
        $missons = Missions::where('ms_userUUID', $user->user_uuid)->get();
        if ($ms) {
            return Redirect::to('/tu-khoa');
        } else {
            return view('mission.startmission', ['missions' => $missons]);
        }
    }

    // ================== MISSIONS ==========================
    public function pastekey(Request $request)
    {
        $user = Auth::user();
        $ms = Missions::where('ms_userUUID',$user->user_uuid)->where('ms_status', 'already')->first();
        if ($ms->ms_code == $request->key) {
            $user = Missions::where('user_uuid',$user->user_uuid)->first();
            print_r($user);
            $us = Missions::where('user_uuid',$user->user_uuid)->update(
                ['wallet' => $user->wallet + $ms->ms_price]
            );
            $ms = Missions::where('ms_userUUID',$user->user_uuid)->where('ms_status', 'already')->update(['ms_status' => 'done']);

            return Redirect::to('/');
        } else {
            return redirect()->back()->with('loi', 'Sai mã!');
        }
    }

    public function tukhoa()
    {
        $user = Auth::user();
        $ms = Missions::where('ms_userUUID', $user->user_uuid)->where('ms_status', 'already')->first();
        $missons = Missions::where('ms_userUUID', $user->user_uuid)->get();

        if ($ms) {
            $page = Page::where('page_name', $ms->ms_name)->first();
            return view('mission.mission', ['mission' => $ms, 'missions' => $missons, 'page' => $page]);
        } else {
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
                    $mission->ms_userUUID =$user->user_uuid;
                    $mission->ms_countdown = 60;
                    $mission->ms_price = 0.35;
                    $mission->ms_status = 'already';
                    $mission->save();
                    return Redirect::to('/tu-khoa');
                } else {
                    // return Redirect::to('/')->with('error', 'Nhận nhiệm vụ không thành công!');
                }
            }
        }
    }

    public function cancelmission()
    {
        $user = Auth::user();
        $ms = Missions::where('ms_userUUID', $user->user_uuid)->where('ms_status', 'already')->update(['ms_status' => 'cancel']);
        return Redirect::to('/');
    }

    // ================== END MISSIONS ==========================


    // ====================== PAGES ============================
    public function regispage()
    {

        return view('menu.regispage');
    }
    public function addpage(Request $request)
    {
        // Nếu người dùng có chọn file để upload
        if ($request->file('image')) {
            $filename = time() . '.' . request()->image->getClientOriginalExtension();

            request()->image->move(public_path('images'), $filename);
            $db = Page::insert(['page_name' => $request->pagename, 'page_image' => $filename]);
            if ($db) {
                return redirect()->back()->with('message', 'Thêm thành công!');
            } else {
                return redirect()->back()->with('error', 'Thêm thất bại!');
            }
        }
    }

    // ====================== END PAGES ============================

}
