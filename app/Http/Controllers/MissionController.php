<?php

namespace App\Http\Controllers;

use App\Models\Missions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class MissionController extends Controller
{
    public function test(Request $request){        
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomkey = substr(str_shuffle($permitted_chars), 0, 10);
        $mission = Missions::where('ms_status', 'already')->where('ms_name',$request->name)->update(['ms_code'=>$randomkey]);      
        return Redirect::to('/test');
    }
}
