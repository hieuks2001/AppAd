<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array
   */
  protected $except = [
    //
    "check/",
    "generate-code/",
    "mstest/missiontoday_newuser/",
    "mstest/missiontoday_olduser_do_mission/",
    "mstest/mission_checkweek/",
    "login/"
  ];
}
