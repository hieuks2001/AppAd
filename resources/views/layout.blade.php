<!DOCTYPE html>
<html lang="en" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
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
    @yield('register')
    @yield('countdown')
    <script src="{{ asset('js/web3.js') }}"></script>
    <div id="canihelpu" style="text-align: center;margin: 20px 0">
        <span id="countdown" style="font-size: 4rem;font-weight: bold"></span>
        <p style="font-size: 2rem;font-weight: bold">Vui lòng đợi giây lát để lấy code</p>
    </div>
    {{-- <script>
        var value = "f0df0fe5-e3dd-4798-871c-53841af60510"
    </script>
    <script src="{{ asset('ican.js') }}"></script> --}}
    @stack('scripts')
</body>

</html>
