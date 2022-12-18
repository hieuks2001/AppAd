@extends('admin')
@section('management-users')
<div class="overflow-x-auto rounded-2xl bg-white p-5 mb-10 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">memtraffic.com</h3>
    <form action="{{action('DashboardController@searchUserTraffic')}}" method="post">
      @csrf
      <input type="text" name="data" placeholder="Tìm kiếm sđt" class="input input-ghost w-full max-w-xs">
      <input type="submit" hidden>
    </form>
  </div>
  <br>
  <table class="table w-full" id="table-users">
    <!-- head -->
    <thead>
      <tr>
        <th>ID</th>
        <th>SĐT</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($usersTraffic as $key => $value)
      <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->username }}</td>
        <td>
          <label for="modal-change--password" class="btn btn-square btn-outline btn-sm"
            onclick="onUserChangePassword('{{$value->id}}','traffic')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
          </label>
        </td>
        <td>
          <label for="modal-change--wallet" class="btn btn-square btn-outline btn-sm"
            onclick="onUserChangeWallet('{{$value->id}}','traffic')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign">
              <line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
          </label>
        </td>
        <td>
          <a class="btn btn-square btn-outline btn-sm" href="/management/user/{{$value->id}}/transaction?type=traffic">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clock-history" viewBox="0 0 16 16">
              <path d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z"/>
              <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z"/>
              <path d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z"/>
            </svg>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="btn-group flex justify-center mt-5">
    <a class="btn btn-outline btn-sm {{$usersTraffic->onFirstPage() ? 'btn-disabled' : ''}}"
      href="{{$usersTraffic->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$usersTraffic->hasMorePages() ? 'btn-disabled' : ''}}"
      href="{{$usersTraffic->nextPageUrl()}}">Next</a>
  </div>
</div>
<!-- Modal change password -->
<input type="checkbox" id="modal-change--password" class="modal-toggle">
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-change--password" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    <form id="form-change--pwd" method="post" class="mb-0"> {{-- action in js dom --}}
      @csrf
      <div class="form-control max-w-full">
        <label class="label">
          <span class="label-text font-bold text-2xl">Đặt lại mật khẩu: <span class="font-normal"></span></span>
        </label>
        <div class="flex">
          <input type="text" name="pwd" class="input input-bordered mb-3 w-full" />
        </div>
      </div>
      <button type="submit" class="btn btn-block">Đặt lại</button>
    </form>
  </div>
</div>

<!-- Modal change user's wallet -->
<input type="checkbox" id="modal-change--wallet" class="modal-toggle">
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-change--wallet" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    <form id="form-change--wallet" method="post" class="mb-0"> {{-- action in js dom --}}
      @csrf
      <div class="form-control max-w-full">
        <label class="label">
          <span class="label-text font-bold text-2xl">Số tiền muốn thêm hoặc bớt: <span class="font-normal"></span><br>Lưu ý: thêm dấu "-" đằng trước để trừ tài khoản!</span>
        </label>
        <div class="flex">
          <input type="number" step="any" name="amount" class="input input-bordered mb-3 w-full" />
        </div>
      </div>
      <button type="submit" class="btn btn-block">Cập nhật</button>
    </form>
  </div>
</div>
@if(Session::has('error') or Session::has('message') or $errors->has('pwd'))
<input type="checkbox" id="modal-notificate" class="modal-toggle" checked />
@endif
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-notificate" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    @if (Session::has('error') or $errors->has('pwd'))
    <p class="my-5 text-xl">{{Session::get('error') ?? $errors->first('pwd')}}</p>
    @else
    <p class="my-5 text-xl">{{Session::get('message')}}</p>
    @endif
  </div>
</div>
<script>
  const usersTraffic = {!! json_encode($usersTraffic->toArray(), JSON_HEX_TAG) !!}
    const formChangePwd = document.getElementById("form-change--pwd");
    const labelFormChangePwd = document.querySelector("#form-change--pwd label span span");
    console.log(labelFormChangePwd);
    function onUserChangePassword(uid,type) {
      const data = type === 'traffic' ? usersTraffic.data : users.data
      const user = data.find(ele=>ele.id===uid)
      labelFormChangePwd.textContent = user.username
      console.log(`management/user/${user.id}/change_password`);
      formChangePwd.action = `/management/user/${user.id}/change_password?type=${type}`
    }
    const formChangeWallet = document.getElementById("form-change--wallet");
    const labelFormChangeWallet = document.querySelector("#form-change--wallet label span span");
    console.log(labelFormChangeWallet);
    function onUserChangeWallet(uid,type) {
      const data = type === 'traffic' ? usersTraffic.data : users.data
      const user = data.find(ele=>ele.id===uid)
      labelFormChangeWallet.textContent = user.username
      console.log(`management/user/${user.id}/change_wallet`);
      formChangeWallet.action = `/management/user/${user.id}/change_wallet?type=${type}`
    }
</script>
@endsection
