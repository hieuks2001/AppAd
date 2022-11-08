<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Encryption\Encrypter;

class VerifyCsrfToken extends Middleware
{
  /**
   * The URIs that should be excluded from CSRF verification.
   *
   * @var array
   */
  protected $except = [
    //
    "page-init/",
    "generate-code/",
  ];
  public function __construct(Application $app, Encrypter $encrypter)
  {
    parent::__construct($app, $encrypter);
    $this->except = array_push($this->except, env("TELEGRAM_BOT_TOKEN") . '/webhook');
  }
}
