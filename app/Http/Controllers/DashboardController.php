<?php

namespace App\Http\Controllers;

use App\Constants\PagePriorityConstants;
use App\Constants\TransactionTypeConstants;
use App\Models\LogTransaction;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use ReflectionClass;

class DashboardController extends Controller
{
	public function managementTraffic()
	{
		$pages = Page::where('is_approved', 1)->get();
		$notApprovedPages = Page::where('is_approved', 0)->get();
		return view('admin.traffic', compact(['pages', 'notApprovedPages']));
	}

	public function getApproveTraffic($id)
	{
		$priority = new ReflectionClass(PagePriorityConstants::class);
		$page = Page::where('is_approved', 0)->where('id', $id)->first();
		if (!$page){
			return redirect()->to('/management/traffic');
		}
		return view('admin.editTraffic')->with('page', $page)->with('priority', $priority->getConstants());
	}

	public function postApproveTraffic(Request $request, $id)
	{
		$page = Page::where('id', $id)->first();
		try {
			// Store page image
			$filename = time() . '.' . request()->image->getClientOriginalExtension();
			request()->image->move(public_path('images'), $filename);

			$page->priority = $request['priority'];
			$page->image = $filename;
			// Set approved to TRUE
			$page->is_approved = 1;

			$page->save();

		} catch (\Throwable $th) {
			return redirect()->to('/management/traffic');
		}

		return redirect()->to('/management/traffic');
	}

	public function delApproveTraffic($id)
	{
		$page = Page::where('id', $id)->first();
		$user = Auth::user();

		DB::transaction(function() use ($page, $user){
			$page->is_approved = 2;
			
			$log = new LogTransaction();
			$log->user_id = $page->user_id;
			$log->amount  = $page->price;
			$log->type = TransactionTypeConstants::REFUND;


			DB::table('users')->where('id', $page->user_id)->increment('wallet', $page->price);
			
			$page->save();
			$log->save();
		});

		return redirect()->to('/management/traffic');
	}

	// Management User - UserType

	public function managementUsers()
	{
		$userTypes = DB::table('user_types')->get();
		$users = User::where('status', 1)->get();

		return view('admin.users', compact(['userTypes', 'users']));
	}

	public function postCreateUserType(Request $request){
        
        $validated = $request->validate([
            'name' => 'required|max:255',
            'max_traffic' => 'required|numeric|gt:0'
        ]);
        
        $userType = new UserType($validated);

        $userType->save();
        
        return redirect()->to('/management/users');
    }

	public function postChangeUserType(Request $request, $id){
        // Edit user user_type 
        $userTypeID = $request['user_type'];
        
		$user = User::where('id', $id)->first();
		if($user){
			$type = UserType::where('id', $userTypeID)->first();
			if ($type) {
				$user->user_type_id = $type->id;
				$user->save();
			}
		}      
        return redirect()->to('/management/users');
    }
}
