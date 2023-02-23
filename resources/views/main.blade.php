@extends('layout')
@section('main')
<div class="drawer drawer-mobile">
  <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
  <div class="drawer-content flex flex-col items-center">
    <!-- Navbar -->
    <div class="w-full navbar bg-gray-800 sticky top-0 z-50 text-white">
      <div class="flex-none lg:hidden">
        <label for="my-drawer-3" class="btn btn-square btn-ghost">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            class="inline-block w-6 h-6 stroke-current">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </label>
      </div>
      <div class="flex-1 px-2 mx-2">
        <div class="twelve wide column center aligned">
          {{-- 1VND = 23,000VND --}}
        </div>
      </div>
      <div class="flex">
        <!-- Navbar menu content here -->
        {{-- <a href="{{URL::to('login')}}" class="ui inverted item">Login</a>
        <a href="{{URL::to('register')}}" class="ui inverted item">Sign Up</a> --}}
        <div class="dropdown dropdown-end">
          <label tabindex="0" class="btn m-1">{{ Auth::user()->username }}</label>
          <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-gray-800 rounded-box w-52">
            <li><a href="/change-password">Đổi mật khẩu</a></li>
          </ul>
        </div>
        <a href="/logout" class="ml-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 stroke-red-500" fill="none" viewBox="0 0 24 24"
            stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </a>
      </div>
    </div>
    <!-- Page content here -->
    <div class="container px-5 py-5">
      {{-- @include('notification') --}}
      @yield('dashboard')
      @yield('mission')
      @yield('regispage')
      @yield('usdt')
      @yield('balance')
      @yield('ref')
    </div>
  </div>
  <div class="drawer-side text-white">
    <label for="my-drawer-3" class="drawer-overlay"></label>
    <ul class="menu p-4 overflow-y-auto w-80 bg-gray-800">
      <!-- Sidebar content here -->
      <li><a class="menu-item" href="{{ URL::to('') }}">Tổng quan</a></li>
      <li><a class="menu-item" href="{{ URL::to('tu-khoa') }}">Nhiệm vụ từ khóa</a></li>
      {{-- <li><a class="menu-item" href="{{ URL::to('regispage') }}">Mua traffic user</a></li> --}}
      {{-- <li><a class="menu-item" href="{{ URL::to('deposit') }}">Nạp tiền</a></li> --}}
      <li><a class="menu-item" href="{{ URL::to('withdraw') }}">Rút tiền</a></li>
      <li><a class="menu-item" href="{{ URL::to('ref') }}">Mã giới thiệu</a></li>
      <li><a class="menu-item" href="{{ URL::to('balance') }}">Biến động số dư</a></li>
      <li><a class="menu-item" href="{{ URL::to('user-ref-up') }}">Cấp trên</a></li>
      <li><a class="menu-item" href="{{ URL::to('user-ref') }}">Cấp dưới</a></li>
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