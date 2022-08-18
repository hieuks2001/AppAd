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
use App\Constants\PageStatusConstants;
use App\Constants\MissionStatusConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogTransaction;
use App\Models\PageType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
  public function getTrafficOrder()
  {
    // Pending Traffic order
    return redirect()->to("/regispage/tab-1");
  }

  public function postTrafficOrder(StoreOrderPageTraffic $request)
  {
    // First validate the request -> Automatic redirect to GET if error occurred.
    $validated = $request->validated();

    try {
      $user = Auth::user();
      $page = new Page($validated);

      DB::transaction(function () use ($user, $page, $validated) {

        $page->traffic_remain = $page->traffic_sum;
        $page->user_id = $user->id;

        $page_type = PageType::where('id', $validated['page_type'])->first();
        $page->page_type_id = $page_type->id;
        $page->price_per_traffic = $page_type->onsite[$page->onsite];
        // Cal the price (onsite price * traffic sum)
        $page->price = $page->traffic_sum * $page->price_per_traffic;

        // //Add log 
        // $log = new LogTransaction();
        // $log->user_id = $user->id;
        // $log->amount  = $page->price;
        // $log->type = TransactionTypeConstants::PAY;

        // DB::table('users')->where('id', $user->id)->decrement('wallet', $page->price);
        // $log->save();
        $page->save();
      });
      return redirect()->back()->with(['message' => 'Thêm thành công!', "pageId" => $page->id]);
    } catch (\Throwable $th) {
      // *TODO: Add transaction here: Rollback usdt
      return redirect()->back()->with('error', 'Thêm thất bại!');
    }
  }
  public function regispageTab1()
  {
    // Pending Traffic order
    $pages = Page::where('status', PageStatusConstants::PENDING)
      ->where('user_id', Auth::user()->id)->simplePaginate(10);
    return view('regispage.tab1')->with('pages', $pages);
  }
  public function regispageTab2()
  {
    // Running Traffic order (Approved page)
    $pages = Page::where('status', PageStatusConstants::APPROVED)
      ->where('user_id', Auth::user()->id)
      ->where('traffic_remain', '>', 0)->simplePaginate(10);
    return view('regispage.tab2')->with('pages', $pages);
  }
  public function regispageTab3()
  {
    $missions = Page::join("missions","missions.page_id","=","pages.id")
    ->where([
      ['pages.user_id',"=", Auth::user()->id],
      ['pages.status',"=", PageStatusConstants::APPROVED],
      ['missions.status',"=", MissionStatusConstants::COMPLETED],
    ])
    ->orderBy("pages.price_per_traffic", "DESC")
    ->simplePaginate(
      $perPage = 10, $columns = [
        "missions.id", "missions.ip", "missions.updated_at",
        "pages.url", "pages.price_per_traffic", "pages.hold_percentage"
      ]
      );
    return view('regispage.tab3')->with('missions', $missions);
  }
  public function regispageTab3Search(Request $request)
  {
    $key = $request->data;
    $missions = Page::join("missions","missions.page_id","=","pages.id")
    ->where([
      ['pages.user_id',"=", Auth::user()->id],
      ['pages.status',"=", PageStatusConstants::APPROVED],
      ['missions.status',"=", MissionStatusConstants::COMPLETED],
    ])
    ->where(function($query) use ($key){
      $query->where('url','LIKE',"%{$key}%");
      $query->orWhere('origin_url', 'LIKE', "%{$key}%");
    })
    ->orderBy("pages.price_per_traffic", "DESC")
    ->simplePaginate(
      $perPage = 10, $columns = [
        "missions.id", "missions.ip", "missions.updated_at",
        "pages.url", "pages.price_per_traffic", "pages.hold_percentage"
      ]
      );
    return view('regispage.tab3',compact(['missions']));
  }
  public function regispageTab4()
  {
    // Completed Traffic order (Approved page)
    $pages = Page::where('status', PageStatusConstants::APPROVED)
      ->where('user_id', Auth::user()->id)
      ->where('traffic_remain', 0)->simplePaginate(10);
    return view('regispage.tab4')->with('pages', $pages);
  }
  public function regispageTab5()
  {
    // Canceled Traffic order (Error)
    $pages = Page::where('status', PageStatusConstants::CANCEL)
      ->where('user_id', Auth::user()->id)->simplePaginate(10);
    return view('regispage.tab5')->with('pages', $pages);
  }
}
