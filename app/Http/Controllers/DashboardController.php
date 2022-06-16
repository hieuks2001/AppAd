<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function managementTraffic()
  {
    return view('admin.traffic');
  }
  public function managementUsers()
  {
    return view('admin.users');
  }
}
