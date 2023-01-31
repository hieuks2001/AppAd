@extends('layout')
@section('register')
<div class="container px-5 md:px-0 md:w-1/3 mx-auto h-screen grid place-items-center">
    <!-- Put this part before </body> tag -->
    <input type="checkbox" id="my-modal-6" class="modal-toggle" checked="true" />
    <div class="modal modal-bottom sm:modal-middle">
      <div class="modal-box">
        <h3 class="font-bold text-lg">Lưu ý</h3>
        <p class="py-4">Số điện thoại đăng ký phải có momo để có thể rút tiền về Momo. </p>
        <p class="py-4">Tất cả các tài khoản đăng ký không có momo chúng tôi không có trách nhiệm phải thanh toán khi không tuân thủ quy định trên. </p>
        <div class="modal-action">
          <label for="my-modal-6" class="btn">Đồng ý!</label>
        </div>
      </div>
    </div>
  <div class="flex flex-col items-center">
    <h2 class="text-4xl mb-10">
      Đăng ký
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
    @if ($errors->all())
    @foreach ($errors->all() as $err)
    <div class="alert alert-error mb-5 shadow-lg">
      <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 stroke-current" fill="none"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>
          {{ $err }}
        </span>
      </div>
    </div>
    @endforeach
    @endif
    <form method="POST" action="{{ URL::to('/register') }}">
      @csrf
      <input type="text" name="username" placeholder="Số điện thoại" class="input input-bordered w-full mb-5">
      <input type="password" name="password" placeholder="Mật khẩu" class="input input-bordered w-full mb-5">
      <input type="password" name="re_password" placeholder="Nhập lại mật khẩu"
        class="input input-bordered w-full mb-5">
      <input type="text" name="ref" hidden value="{{ request()->get('ref') }}">
      <button class="btn btn-block">Đăng ký</button>
    </form>
    <div class="">
      Bạn đã có tài khoản? <a href="{{ URL::to('login') }}">Đăng nhập</a>
    </div>
  </div>
</div>
@endsection
