<!DOCTYPE html>
<html
  lang="en"
  class="scroll-smooth"
>

<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex">
  <meta name="googlebot" content="noindex">
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
  >
  <meta
    http-equiv="X-UA-Compatible"
    content="ie=edge"
  >
  <meta
    name="csrf-token"
    content="{{ csrf_token() }}"
  >
  <script
    src="https://code.jquery.com/jquery-3.1.1.min.js"
    integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
    crossorigin="anonymous"
  ></script>
  <meta
    name="csrf-token"
    content="{{ csrf_token() }}"
  />
  <link
    rel="stylesheet"
    href="{{ asset('css/app.css') }}"
  >
  <title>Document</title>
</head>

<body>
  @yield('main')
  @yield('admin')
  @yield('login')
  @yield('register')
  @yield('countdown')
  @stack('scripts')
  <script data-src-embed="https://embed.168chat.com/" data-src-js-embed="https://168chat.com/" id="embed-live168" data-id=62f5b31e41ac664b7a09e8ff src="https://168chat.com/embed/template/index.js"></script>
  <script>
    //init iframe live chat and pass some params (extras)
    window.Live168API.init({
      webId: "62f5b31e41ac664b7a09e8ff",
      extras: {
        vToken: "you-verify-token",
        userId: "",
      },
    });
     window.Live168API.on("ready", () => {
        console.log("iframe loaded");
      });
  </script>
</body>

</html>
