@extends('layout')
@section('login')
<div class="container px-5 md:px-0 md:w-1/3 mx-auto h-screen grid place-items-center ">
  <div class="flex flex-col items-center">
    <h2 class="text-4xl mb-10">
      Xác minh tài khoản của bạn
    </h2>
    @if (session()->has('error'))
    <div class="alert alert-error shadow-lg w-full mb-5">
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
    <div class="flex-col">
      <form class="" method="POST" action="{{ URL::to('/verify') }}">
        @csrf
        <input type="text" name="otp" placeholder="OTP" required
          class="appearance-none input input-bordered w-full mb-5 ">
        <button class="btn btn-block">Xác minh</button>
      </form>
      {{-- @if (session()->has('getCode') && Session::get('getCode') == true) --}}
      <form class="mt-3" method="POST" action="{{ URL::to('/verify/renew') }}">
        @csrf
        <button class="btn btn-block btn-outline">Nhận lại mã</button>
      </form>
      {{-- @endif --}}
    </div>
  </div>
</div>
@endsection
