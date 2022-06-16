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

class PageController extends Controller
{
    public function getTrafficOrder()
    {
        $onsite = new ReflectionClass(OntimeTypeConstants::class);
        $onsite = $onsite->getConstants();

        return view('regispage.tab1')->with('onsite', $onsite);
    }

    public function postTrafficOrder(StoreOrderPageTraffic $request)
    {
        // First validate the request -> Automatic redirect to GET if error occurred.
        $validated = $request->validated();

        try {
            $user = Auth::user();

            $page = new Page($validated);

            $page->user_uuid = $user->id;

            // Demo only
            $page->price = $page->traffic_sum * OntimePriceConstants::TYPE_PRICE[$page->onsite];

            $page->save();

        } catch (\Throwable $th) {
            // *TODO: Add transaction here: Rollback usdt
            return redirect()->back()->with('error', 'Thêm thất bại!');
        }
        return redirect()->back()->with('message', 'Thêm thành công!');
    }
}
