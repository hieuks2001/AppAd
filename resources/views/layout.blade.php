<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex">
  <meta name="googlebot" content="noindex">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"
    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/ethjs@0.3.4/dist/ethjs.min.js"></script>
  <script src="https://cdn.ethers.io/lib/ethers-5.0.umd.min.js" type="text/javascript"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <title>Document</title>
</head>

<body>
  @yield('main')
  @yield('admin')
  @yield('login')
  @yield('change_password')
  @yield('register')
  @yield('countdown')
  <script data-src-embed="https://embed.168livechat.com/" data-src-js-embed="https://app.168livechat.com/" id="embed-live168" data-id=62f5b2c7ad9dd00d38f6aa87 src="https://app.168livechat.com/embed/template/index.js"></script>
  <script>
    //init iframe live chat and pass some params (extras)
      window.Live168API.init({
        webId: "62f5b2c7ad9dd00d38f6aa87",
        extras: {
          userId: {{Auth::check() ? Auth::user()->username : "null"}},
          domain: window.location.hostname,
          ref: document.referrer,
        },
      });
  </script>
  @stack('scripts')
</body>

</html>
