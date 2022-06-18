<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreOrderPageTraffic;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use ReflectionClass;
use App\Constants\OntimeTypeConstants;
use App\Constants\OntimePriceConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
  public function getTrafficOrder()
  {
    return view('regispage.tab1');
  }

  public function postTrafficOrder(StoreOrderPageTraffic $request)
  {
    // First validate the request -> Automatic redirect to GET if error occurred.
    $validated = $request->validated();

    try {
      $user = Auth::user();
      $page = new Page($validated);

      DB::transaction(function () use ($user, $page) {

        $page->traffic_remain = $page->traffic_sum;
        $page->user_id = $user->id;
        $page->price_per_traffic = OntimePriceConstants::TYPE_PRICE[$page->onsite];
        $page->price = $page->traffic_sum * $page->price_per_traffic;

        //Add log 
        $log = new LogTransaction();
        $log->user_id = $user->id;
        $log->amount  = $page->price;
        $log->type = TransactionTypeConstants::PAY;

        DB::table('users')->where('id', $user->id)->decrement('wallet', $page->price);
        $log->save();
        $page->save();
      });
    } catch (\Throwable $th) {
      // *TODO: Add transaction here: Rollback usdt
      dd($th);
      return redirect()->back()->with('error', 'Thêm thất bại!');
    }
    return redirect()->back()->with('message', 'Thêm thành công!');
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
}
