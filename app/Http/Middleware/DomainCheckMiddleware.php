<?php

namespace App\Http\Middleware;

use App\Models\Page;
use Closure;
use Illuminate\Support\Facades\Log;
class DomainCheckMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    // Dev enviroment only
    if ($request->ip() == '127.0.0.1') {
      $originUrl = "http://localhost";
    } else {
      $originUrl = $request->headers->get('origin');
    }
    $encrypted = hex2bin($request->key1);
    $key = hex2bin($request->key2);
    $iv = hex2bin($request->key3);
    $data = json_decode(openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv));
    $pageId = "";
    if(is_null($request->id)){
    	$pageId = $data->id;
    }else{
    	$pageId = $request->id;
    }
    // $originUrl = $request->headers->get('origin');
    $requestHost = parse_url($originUrl, PHP_URL_HOST);
    $page = Page::where('id', $pageId)->get('url')->first();
    if(!is_null($page)) {
    $pageUrl = parse_url($page->url, PHP_URL_HOST);
    if ($requestHost == $pageUrl) {
      return $next($request);
    }
    }
    return response()->json(["error" => "Site url not correct!"], 401);
  }
}
