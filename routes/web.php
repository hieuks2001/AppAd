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

Route::get('/register', 'UserController@register');
Route::post('/register', 'UserController@register');

Route::get('/change-password', 'UserController@changePassword');
Route::post('/change-password', 'UserController@changePassword');

Route::get('/logout', 'UserController@logout');

// usdt
Route::group(['middleware' => ['checkLogin']], function () {
  Route::get('/deposit', 'UserController@depositView');
  Route::post('/deposit', 'UserController@deposit');
  Route::get('/withdraw', 'UserController@withdrawView');
  Route::post('/withdraw', 'UserController@withdraw');
});

// // Missions
// Route::group(['middleware' => ['checkLogin']], function () {
//   Route::post('/paste-key', 'UserController@pastekey');
//   Route::post('/tu-khoa', 'MissionController@postMission');
//   Route::get('/tu-khoa', 'MissionController@getMission');
//   Route::get('/cancel-mission', 'MissionController@cancelmission');
// });
Route::group(['middleware' => ['checkDomain']], function () {
  Route::post('/page-init', 'MissionController@pageInit');
  Route::post('/generate-code', 'MissionController@generateCode');
});

// Pages
Route::group(['middleware' => ['checkLogin']], function () {
  Route::get('/regispage', 'PageController@getTrafficOrder');
  Route::post('/add-page', 'PageController@postTrafficOrder');
  Route::get('/regispage/tab-1', 'PageController@regispageTab1');
  Route::get('/regispage/tab-2', 'PageController@regispageTab2');
  Route::get('/regispage/tab-3', 'PageController@regispageTab3');
  Route::post('/regispage/tab-3/search', 'PageController@regispageTab3Search');
  Route::get('/regispage/tab-4', 'PageController@regispageTab4');
  Route::get('/regispage/tab-5', 'PageController@regispageTab5');
});

// Admin dashboard
Route::group(['middleware' => ['checkAdmin']], function () {
  Route::get('management/traffic', 'DashboardController@managementTraffic');
  Route::get('management/users', 'DashboardController@managementUsers');
  // Route::get('management/missions', 'DashboardController@managementMissions');

  // Manager Mission
  Route::post('management/mission/search', 'DashboardController@searchMission');

  // Manager Traffic
  Route::post('management/traffic/search/approved', 'DashboardController@searchTrafficApproved');
  Route::post('management/traffic/search/not-approved', 'DashboardController@searchTrafficNotApproved');
  Route::get('management/traffic/{id}', 'DashboardController@getApproveTraffic');
  Route::post('management/traffic/{id}', 'DashboardController@postApproveTraffic');
  Route::post('management/traffic/{id}/edit', 'DashboardController@postEditTraffic');
  Route::post('management/traffic/{id}/del', 'DashboardController@delApproveTraffic');

  // Manager User
  // Route::post('admin/users/{id}', 'DashboardController@postChangeUserType');
  // Route::post('management/usertypes', 'DashboardController@postCreateUserType');
  // Route::post('management/usertypes/edit', 'DashboardController@editUserType');
  // Route::post('management/user/search', 'DashboardController@searchUser');
  Route::post('management/userTraffic/search', 'DashboardController@searchUserTraffic');
  // Route::post('management/user/{id}', 'DashboardController@postUnblockUser');
  Route::post('management/user/{id}/change_password', 'DashboardController@changePassword');
  Route::post('management/user/{id}/change_wallet', 'DashboardController@addMoneyForUser');
  Route::get('management/user/{id}/transaction', 'DashboardController@showUserTransactions');
  Route::get('management/user/transactions', 'DashboardController@showUsersTransactions');

  // Manager Page
  Route::get('management/pages', 'DashboardController@getPageTypes');
  Route::post('management/pages', 'DashboardController@createPageType');
  Route::post('management/pages/{id}', 'DashboardController@editPageTypes');

  Route::post('management/user-register', 'DashboardController@registerManual');

  // Manager Seting
  Route::post('/management/setting', 'DashboardController@changeSettingValue');
  Route::get('/management/setting', 'DashboardController@managementSettings');
});


// Verify user
Route::get('/verify', 'UserController@verifyOtp');
Route::post('/verify', 'UserController@verifyOtpToken');
Route::post('/verify/renew', 'UserController@verifyRenewOtp');

Route::post("/" . env("TELEGRAM_BOT_TOKEN") . "/webhook", 'TelegramController@getUpdate');
