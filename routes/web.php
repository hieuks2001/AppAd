<?php

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// INdex
Route::get('/', 'UserController@index');

Route::get('/login', 'UserController@login')->name('login');
Route::get('/register', 'UserController@register');
Route::post('/login', 'UserController@login');
Route::post('/register', 'UserController@register');
Route::get('/logout', 'UserController@logout');

// Missions
Route::group(['middleware' => ['checkLogin']], function () {
    Route::post('/paste-key', 'UserController@pastekey');
    Route::get('/tu-khoa', 'UserController@tukhoa');
    Route::get('/cancel-mission', 'UserController@cancelmission');
});

// Pages
Route::group(['middleware' => ['checkLogin']], function () {
    Route::post('/add-page', 'UserController@addpage');
    Route::get('/regispage', 'UserController@regispage');
});

// Admin dashboard
Route::group(['middleware' => ['checkAdmin']], function () {
    Route::get('admin/dashboard', 'DashboardController@dashboard');
});

Route::post('/test1', 'MissionController@test');
Route::get('/test', function () {
    $mission = DB::table('missions')->where('ms_status', 'already')->first();
    return view('test.countdown', ['mission' => $mission]);
});

Route::post('/createkw', function (Request $request) {

    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomkey = substr(str_shuffle($permitted_chars), 0, 10);
    $mission = DB::table('missions')->where('ms_status', 'already')->where('ms_name', $request->name)->update(['ms_code' => $randomkey]);
    return 0;
});
