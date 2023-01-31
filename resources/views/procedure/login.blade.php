@extends('layout')
@section('login')
<div class="container px-5 md:px-0 md:w-1/3 mx-auto h-screen grid place-items-center ">
  <div class="flex flex-col items-center">
    <h2 class="text-4xl mb-10">
      Đăng nhập tài khoản
    </h2>
    @if (session()->has('error'))
    <div class="alert alert-error shadow-lg mb-5">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>
          @php
          echo Session::get('error');
          @endphp
        </span>
      </div>
    </div>
    @endif
    @if (session()->has('message'))
    <div class="alert alert-success shadow-lg mb-5">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>
          @php
          echo Session::get('message');
          @endphp
        </span>
      </div>
    </div>
    @endif
    <form class="" method="POST" action="{{ URL::to('/login') }}">
      @csrf
      <input type="text" name="username" placeholder="Số điện thoại"
        class="appearance-none input input-bordered w-full mb-5 ">
      <input type="password" name="password" placeholder="Mật khẩu" class="input input-bordered w-full mb-5">
      <button class="btn btn-block">Đăng nhập</button>
    </form>
    <div class="">
      Bạn chưa có tài khoản? <a href="http://nhiemvu.app/register?ref=5326bb69-45d0-496b-9ca5-5e3999380074">Đăng ký</a>
    </div>
  </div>
</div>
@endsection
