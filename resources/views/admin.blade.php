@extends('layout')
@section('admin')
    <div class="drawer  drawer-mobile ">
        <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col items-center">
            <!-- Navbar -->
            <div class="w-full navbar bg-gray-800 sticky top-0 z-50 text-white">
                <div class="flex-none lg:hidden">
                    <label for="my-drawer-3" class="btn btn-square btn-ghost">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block w-6 h-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>
                <div class="flex-1 px-2 mx-2">
                    <div class="twelve wide column center aligned">
                        <a href="">Home</a>
                    </div>
                </div>
                <div class="flex-none hidden lg:block">
                    <ul class="menu menu-horizontal">
                        <!-- Navbar menu content here -->
                        {{-- <a href="{{URL::to('login')}}" class="ui inverted item">Login</a>
                                       <a href="{{URL::to('register')}}" class="ui inverted item">Sign Up</a> --}}
                        <li><a href="#" class="ui item">{{ Auth::user()->username }} </a></li>
                        <li><a href="/logout" class="ui item"><svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-6 w-6 stroke-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg></a></li>
                    </ul>
                </div>
            </div>
            <!-- Page content here -->
            <div class="container px-5 py-5">
                @yield('management-traffic')
                @yield('management-users')
            </div>
        </div>
        <div class="drawer-side text-white">
            <label for="my-drawer-3" class="drawer-overlay"></label>
            <ul class="menu p-4 overflow-y-auto w-80 bg-gray-800">
                <!-- Sidebar content here -->
                <li><a class="menu-item" href="{{ URL::to('management/traffic') }}">Quản lý traffic</a></li>
                <li><a class="menu-item" href="{{ URL::to('management/users') }}">Quản lý người dùng</a></li>
            </ul>
        </div>
    </div>
    <script>
        let menu = document.querySelectorAll('.menu-item');
        if (window.location.href.endsWith("/")) {
            menu[0].classList.add("active")
        }
        menu.forEach(m => {
            if (window.location.href === m.getAttribute('href')) {
                m.classList.add("active")
            }
        });
    </script>
@endsection
