@extends('layout')
@section('main')
    <div class="drawer  drawer-mobile text-white">
        <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col items-center">
            <!-- Navbar -->
            <div class="w-full navbar bg-base-300">
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
                        <li><a href="#" class="ui item">{{ Session::get('user')->username }} </a></li>
                        <li><a href="/logout" class="ui item"><i class="logout red icon"></i></a></li>
                    </ul>
                </div>
            </div>
            <!-- Page content here -->
            <div class="container px-5 py-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5 text-center md:text-start">
                    <div class="bg-blue-600 p-5 rounded-xl">
                        <div class="ui inverted">
                            <h2 class="text-3xl font-bold">USDT</h2>
                            <b>Thu nhập</b>
                        </div>
                    </div>
                    <div class="bg-yellow-500 p-5 rounded-xl">
                        <div class="ui yellow inverted">
                            <h2 class="text-3xl font-bold">USDT</h2>
                            <b>Hoa hồng</b>
                        </div>
                    </div>
                    <div class="bg-red-500 p-5 rounded-xl">
                        <div class="ui purple inverted">
                            <h2 class="text-3xl font-bold">USDT</h2>
                            <b>Tổng cộng</b>
                        </div>
                    </div>
                    <div class="bg-green-500 p-5 rounded-xl">
                        <div class="ui pink inverted">
                            <h2 class="text-3xl font-bold">USDT</h2>
                            <b>Số dư</b>
                        </div>
                    </div>
                </div>
                <br>
                @yield('mission')
            </div>
        </div>
        <div class="drawer-side">
            <label for="my-drawer-3" class="drawer-overlay"></label>
            <ul class="menu p-4 overflow-y-auto w-80 bg-base-100">
                <!-- Sidebar content here -->
                <li><a href="{{ URL::to('tu-khoa') }}">Nhiệm vụ từ khóa</a></li>
                <li><a>Sidebar Item 2</a></li>
            </ul>
        </div>
    </div>
@endsection
