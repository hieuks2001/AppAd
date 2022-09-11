<?php

use App\Models\Missions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ms', 'api\MissionController@getMission');
Route::post('/ms', 'api\MissionController@postMission');
Route::post('/key', 'api\MissionController@pasteKey');
Route::post('/cancel', 'api\MissionController@cancelMission');
