<?php

namespace App\Providers;

use App\Constants\MissionStatusConstants;
use App\Constants\OntimeTypeConstants;
use App\Constants\PagePriorityConstants;
use App\Models\Mission;
use App\Models\PageType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class ContentServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    //
    view()->composer('regispage.index', function ($view) {
      $onsite = PageType::all();
      $view->with('onsite', $onsite);
    });
    view()->composer('admin.editTraffic', function ($view) {
      $onsite = PageType::all();
      $priority = new ReflectionClass(PagePriorityConstants::class);
      $view->with('priority', $priority->getConstants());
      $view->with('onsite', $onsite);
    });
    view()->composer('admin.users', function ($view) {
      $types = DB::table('user_types')->get();
      $view->with('user_types', $types);
    });
    view()->composer('mission.mission', function ($view) {
      $missions = Mission::where('user_id', Auth::user()->id)
        ->orderBy('updated_at', 'desc')
        ->limit(10)
        ->get();
      $view->with('missions', $missions);
    });
  }
}
