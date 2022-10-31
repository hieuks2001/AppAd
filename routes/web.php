<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', 'UserController@index')->middleware('checkLogin');

Route::get('/login', 'UserController@login')->name('login');
Route::post('/login', 'UserController@login');

Route::get('/register', 'UserController@register')->name('register');
Route::post('/register', 'UserController@register');

Route::get('/change-password', 'UserController@changePassword');
Route::post('/change-password', 'UserController@changePassword');

Route::get('/logout', 'UserController@logout');

// usdt
Route::group(['middleware' => ['checkLogin']], function () {
  Route::get('/withdraw', 'UserController@withdrawView');
  Route::post('/withdraw', 'UserController@withdraw');
});

// Missions
Route::group(['middleware' => ['checkLogin']], function () {
  Route::post('/paste-key', 'UserController@pastekey');
  Route::post('/tu-khoa', 'MissionController@postMission');
  Route::get('/tu-khoa', 'MissionController@getMission');
  Route::get('/cancel-mission', 'MissionController@cancelmission');
});
Route::group(['middleware' => ['checkDomain']], function () {
  Route::post('/generate-code', 'MissionController@generateCode');
  Route::post('/check', 'MissionController@check');
});

// // Pages
// Route::group(['middleware' => ['checkLogin']], function () {
//   Route::get('/regispage', 'PageController@getTrafficOrder');
//   Route::post('/add-page', 'PageController@postTrafficOrder');
//   Route::get('/regispage/tab-1', 'PageController@regispageTab1');
//   Route::get('/regispage/tab-2', 'PageController@regispageTab2');
//   Route::get('/regispage/tab-3', 'PageController@regispageTab3');
//   Route::get('/regispage/tab-4', 'PageController@regispageTab4');
// });

// Admin dashboard
Route::group(['middleware' => ['checkAdmin']], function () {
  Route::get('management/traffic', 'DashboardController@managementTraffic');
  Route::get('management/users', 'DashboardController@managementUsers');

  // Manager Traffic
  Route::get('management/traffic/{id}', 'DashboardController@getApproveTraffic');
  Route::post('management/traffic/{id}', 'DashboardController@postApproveTraffic');
  Route::post('management/traffic/{id}/edit', 'DashboardController@postEditTraffic');
  Route::post('management/traffic/{id}/del', 'DashboardController@delApproveTraffic');

  // Manager User
  Route::post('admin/users/{id}', 'DashboardController@postChangeUserType');
  Route::post('management/usertypes', 'DashboardController@postCreateUserType');
});

// Verify user
Route::post('/verify/renew', 'UserController@verifyRenewOtp');
Route::post('/verify', 'UserController@verifyOtpToken');
Route::get('/verify', 'UserController@verifyOtp');


// User
Route::group(['middleware' => ['checkLogin']], function () {
  Route::post('/update-ref', 'UserController@updateReference');
  Route::get('/ref', 'UserController@getReference');
  Route::get('/user-ref', 'UserController@getUserReferences');
});

// Testing
Route::get('/ms/gen', 'DevController@createTestMissison');
// Route::get('/ms/user', 'DevController@createUserTest');
Route::get('/ms/user', 'DevController@UpdateUserType');
Route::get('/ms/get', 'DevController@getMission');
Route::get('/ms/done', 'DevController@completeMission');

// Route::get('/dev/ms', 'DevController@createLogMonth');
// Route::get('/dev/clearms', 'DevController@clearMission');
// Route::get('/dev/test', 'DevController@testMission');
// Route::get('/dev/testuser', 'DevController@createUserMonth');
// Route::get('/dev/testuser2', 'DevController@createUserWeek');
// Route::get('/dev/ms2', 'DevController@createLogWeek');

Route::post('/mstest/missiontoday_newuser', 'DevController@missionTodayNewUser');
Route::post('/mstest/missiontoday_olduser_do_mission', 'DevController@missionTodayOldUserDoMission');
Route::post('/mstest/mission_checkweek', 'DevController@checkUserUpdateWeek');
