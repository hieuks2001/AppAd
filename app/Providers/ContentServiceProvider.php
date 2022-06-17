<?php

namespace App\Providers;

use App\Constants\OntimeTypeConstants;
use App\Constants\PagePriorityConstants;
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
      $onsite = new ReflectionClass(OntimeTypeConstants::class);
      $onsite = $onsite->getConstants();
      error_log(print_r($onsite, true));
      $view->with('onsite', $onsite);
    });
    view()->composer('admin.editTraffic', function ($view) {
      $priority = new ReflectionClass(PagePriorityConstants::class);
      $view->with('priority', $priority->getConstants());
    });
    view()->composer('admin.users', function ($view) {
      $types = DB::table('user_types')->get();
      $view->with('user_types', $types);
    });
  }
}
