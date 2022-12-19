@extends('admin')
@section('management-users')
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Loại người dùng (nhiemvu.app)</h3>
    <div class="flex">
      <label for="modal-create--user_type" class="btn modal-button btn-accent gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
          stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tạo
      </label>
    </div>
  </div>
  <br>
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>ID</th>
        <th>Tên</th>
        <th></th>
      </tr>
    <tbody>
      @foreach ($user_types as $key => $value)
      <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->name }}</td>
        <td>
          <label for="modal-edit--user_type" class="btn btn-square btn-outline btn-sm"
            onclick="onClickUserType('{{ $value->id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </label>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
{{-- <div class="overflow-x-auto rounded-2xl bg-white p-5 mb-10 drop-shadow-2xl">
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
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="feather feather-dollar-sign">
              <line x1="12" y1="1" x2="12" y2="23"></line>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
          </label>
        </td>
        <td>
          <a class="btn btn-square btn-outline btn-sm" href="/management/user/{{$value->id}}/transaction?type=traffic">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
              class="bi bi-clock-history" viewBox="0 0 16 16">
              <path
                d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
              <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
              <path
                d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
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
</div> --}}
<div class="overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">nhiemvu.app</h3>
    <div class="flex">
      <label for="modal-create--user_traffic" class="btn modal-button btn-accent gap-2 mr-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
          stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tạo
      </label>
      <form action="{{action('DashboardController@searchUser')}}" method="post">
        @csrf
        <input type="text" name="data" placeholder="Tìm kiếm sđt" class="input input-ghost w-full max-w-xs">
        <input type="submit" hidden>
      </form>
    </div>
  </div>
  <br>
  <table class="table w-full" id="table-users">
    <!-- head -->
    <thead>
      <tr>
        <th>ID</th>
        <th>SĐT</th>
        <th>Loại</th>
        <th></th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $key => $value)
      <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->username }}</td>
        <td>{{ $user_types[array_search($value->user_type_id, array_column(json_decode(json_encode($user_types),TRUE),
          'id'))]->name }}</td>
        <td>
          <label for="modal-edit--user_mission" class="btn btn-square btn-outline btn-sm"
            onclick="onClickUser('{{ $value->id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </label>
        </td>
        <td>
          @if ($value->status)
          <!-- volume on icon -->
          <svg class="swap-on fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
            viewBox="0 0 20 20" fill="currentColor">
            <path
              d="M10 2a5 5 0 00-5 5v2a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2H7V7a3 3 0 015.905-.75 1 1 0 001.937-.5A5.002 5.002 0 0010 2z" />
          </svg>
          @else
          <form class="mb-0" action="{{action('DashboardController@postUnblockUser',$value->id)}}" method="post">
            @csrf
            <button type="submit">
              <!-- volume off icon -->
              <svg class="swap-off fill-current w-6 h-6" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                  d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                  clip-rule="evenodd" />
              </svg>
            </button>
          </form>
          @endif
        </td>
        <td>
          <label for="modal-change--password" class="btn btn-square btn-outline btn-sm"
            onclick="onUserChangePassword('{{$value->id}}','mission')">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
            </svg>
          </label>
        </td>
        <td>
          <label for="modal-change--wallet" class="btn btn-square btn-outline btn-sm"
            onclick="onUserChangeWallet('{{$value->id}}','mission')">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="feather feather-dollar-sign">
              <line x1="12" y1="1" x2="12" y2="23"></line>
              <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
          </label>
        </td>
        <td>
          <a class="btn btn-square btn-outline btn-sm" href="/management/user/{{$value->id}}/transaction?type=mission">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
              class="bi bi-clock-history" viewBox="0 0 16 16">
              <path
                d="M8.515 1.019A7 7 0 0 0 8 1V0a8 8 0 0 1 .589.022l-.074.997zm2.004.45a7.003 7.003 0 0 0-.985-.299l.219-.976c.383.086.76.2 1.126.342l-.36.933zm1.37.71a7.01 7.01 0 0 0-.439-.27l.493-.87a8.025 8.025 0 0 1 .979.654l-.615.789a6.996 6.996 0 0 0-.418-.302zm1.834 1.79a6.99 6.99 0 0 0-.653-.796l.724-.69c.27.285.52.59.747.91l-.818.576zm.744 1.352a7.08 7.08 0 0 0-.214-.468l.893-.45a7.976 7.976 0 0 1 .45 1.088l-.95.313a7.023 7.023 0 0 0-.179-.483zm.53 2.507a6.991 6.991 0 0 0-.1-1.025l.985-.17c.067.386.106.778.116 1.17l-1 .025zm-.131 1.538c.033-.17.06-.339.081-.51l.993.123a7.957 7.957 0 0 1-.23 1.155l-.964-.267c.046-.165.086-.332.12-.501zm-.952 2.379c.184-.29.346-.594.486-.908l.914.405c-.16.36-.345.706-.555 1.038l-.845-.535zm-.964 1.205c.122-.122.239-.248.35-.378l.758.653a8.073 8.073 0 0 1-.401.432l-.707-.707z" />
              <path d="M8 1a7 7 0 1 0 4.95 11.95l.707.707A8.001 8.001 0 1 1 8 0v1z" />
              <path
                d="M7.5 3a.5.5 0 0 1 .5.5v5.21l3.248 1.856a.5.5 0 0 1-.496.868l-3.5-2A.5.5 0 0 1 7 9V3.5a.5.5 0 0 1 .5-.5z" />
            </svg>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="btn-group flex justify-center mt-5">
    <a class="btn btn-outline btn-sm {{$users->onFirstPage() ? 'btn-disabled' : ''}}"
      href="{{$users->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$users->hasMorePages() ? 'btn-disabled' : ''}}"
      href="{{$users->nextPageUrl()}}">Next</a>
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
<!-- Modal edit User -->
<input type="checkbox" id="modal-edit--user_mission" class="modal-toggle">
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-edit--user_mission" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    <form id="form-edit" method="post" class="mb-0"> {{-- action in js dom --}}
      @csrf
      <p class="label-text font-bold">
        Tên người dùng:
        <span class="item font-normal username"></span>
      </p>
      <div class="form-control max-w-full">
        <label class="label">
          <span class="label-text font-bold">Loại người dùng</span>
        </label>
        <div class="flex">
          <select name="user_type_id" class="item select select-bordered  mb-3 w-full flex-1 user_type_id">
            @foreach ($user_types as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
          </select>
          {{-- <input type="text" class="input input-bordered max_traffic ml-3 w-14 read-only:bg-slate-200" readonly />
          --}}
          <input name="user_id" type="hidden" value="">
        </div>
      </div>
      <button type="submit" class="btn btn-block">Sửa</button>
    </form>
  </div>
</div>

<!-- Modal create user type -->
<input type="checkbox" id="modal-create--user_type" class="modal-toggle">
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-create--user_type" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    <h3 class="mb-3 text-lg font-bold">Tạo loại người dùng mới</h3>
    <form id="form-create" action="/management/usertypes" method="post" class="mb-0">
      @csrf
      <input type="text" name="name" placeholder="Tên loại người dùng" class="input input-bordered w-full">
      <div class="form-control mb-3">
        <label class="label">
          <span class="label-text">Số lượng cần của mỗi loại trang</span>
        </label>
        @foreach ($page_types as $key => $item)
        @if ($key !== count($page_types) - 1)
        <label class="input-group">
          <div class="flex items-center w-full pl-4 bg-slate-200">Số lượng cần để lên
            <span class="font-bold">
              loại {{$item->name + 1}}
            </span>
          </div>
          <input type="text" placeholder="10" data-mission_need="{{$item->id}}" oninput="handleChange(this)"
            class="mission_need input input-bordered w-14">
        </label>
        @endif
        @endforeach
      </div>
      <div class="form-control mb-3">
        <label class="label">
          <span class="label-text">Tỉ lệ random của từng site</span>
          <span class="label-text">%</span>
        </label>
        @foreach ($page_types as $item)
        <label class="input-group">
          <div class="flex items-center w-full bg-slate-200">
            <span class="font-bold">
              loại {{$item->name}}
            </span>
          </div>
          <input type="text" placeholder="10" data-page_weight="{{$item->id}}" oninput="handleChange(this)"
            class="page_weight input input-bordered w-14">
        </label>
        @endforeach
      </div>
      <input type="text" name="mission_need" hidden>
      <input type="text" name="page_weight" hidden>
      <button type="submit" class="btn btn-block">Tạo</button>
    </form>
  </div>
</div>
<!-- Modal edit user type -->
<input type="checkbox" id="modal-edit--user_type" class="modal-toggle">
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-edit--user_type" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    <h3 class="mb-3 text-lg font-bold">Chỉnh sửa loại người dùng</h3>
    <form id="form-edit--user_type" action="{{action('DashboardController@editUserType')}}" method="post" class="mb-0">
      @csrf
      <input type="text" id="ut_id" name="id" hidden>
      <input type="text" id="ut_name" name="name" placeholder="Tên loại người dùng" class="input input-bordered w-full">
      <div class="form-control mb-3">
        <label class="label">
          <span class="label-text">Số lượng cần của mỗi loại trang</span>
        </label>
        @foreach ($page_types as $key => $item)
        @if ($item->name != count($page_types))
        <label class="input-group">
          <div class="flex items-center w-full pl-4 bg-slate-200">Số lượng cần để lên
            <span class="font-bold">
              loại {{(int)$item->name+1}}
            </span>
          </div>
          <input type="text" placeholder="10" data-mission_need="{{$item->id}}" oninput="handleEditChange(this)"
            class="mission_need input input-bordered w-24">
        </label>
        @endif
        @endforeach
      </div>
      <div class="form-control mb-3">
        <label class="label">
          <span class="label-text">Tỉ lệ random của từng site</span>
          <span class="label-text">%</span>
        </label>
        @foreach ($page_types as $item)
        <label class="input-group">
          <div class="flex items-center w-full bg-slate-200">
            <span class="font-bold">
              loại {{$item->name}}
            </span>
          </div>
          <input type="text" placeholder="10" data-page_weight="{{$item->id}}" oninput="handleEditChange(this)"
            class="page_weight input input-bordered w-24">
        </label>
        @endforeach
      </div>
      <input type="text" name="mission_need" hidden>
      <input type="text" name="page_weight" hidden>
      <button type="submit" class="btn btn-block">Xong</button>
    </form>
  </div>
</div>

<!-- Modal create user -->
<input type="checkbox" id="modal-create--user_traffic" class="modal-toggle">
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-create--user_traffic" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    <h3 class="mb-3 text-lg font-bold">Tạo người dùng mới</h3>
    <form id="" action="{{action('DashboardController@registerManual')}}" method="POST" class="mb-0">
      @csrf
      <input type="text" name="name" placeholder="Tên người dùng" class="input input-bordered w-full mb-3">
      <input type="text" name="phone" placeholder="Số điện thoại" class="input input-bordered w-full mb-3">
      <input type="text" name="password" placeholder="Mật khẩu" class="input input-bordered w-full mb-3">
      <button type="submit" class="btn btn-block">Tạo</button>
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
          <span class="label-text font-bold text-2xl">Số tiền muốn thêm hoặc bớt: <span
              class="font-normal"></span><br>Lưu ý: thêm dấu "-" đằng trước để trừ tài khoản!</span>
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
  const users = {!! json_encode($users->toArray(), JSON_HEX_TAG) !!}
    const user_types = {!! json_encode($user_types->toArray(), JSON_HEX_TAG) !!}
    const formEdit = document.getElementById('form-edit');
    const items = document.querySelectorAll('#form-edit .item');
    const userTypeEle = document.querySelector("#form-edit .user_type_id");
    const maxTrafficEle = document.querySelector("#form-edit .max_traffic");
    function onClickUser(uid) {
      const user = users.data.find(user => user.id == uid)
      /* Setting the action attribute of the form to the url of the user. */
      formEdit.action = `/admin/users/${uid}`;
      items.forEach(item => {
        let key = item.classList[item.classList.length - 1];
        if (key in user) {
          if (key === "user_type_id") {
            item.value = user[key];
          } else {
            item.textContent = user[key];
          }
        }
      })
    }
    const mission_need = {};
    const page_weight = {};
    const eleMissionNeed = document.querySelector("#form-create input[name='mission_need']");
    const elePageWeight = document.querySelector("#form-create input[name='page_weight']");
    console.log(eleMissionNeed);
    console.log(elePageWeight);
    function handleChange(ele) {
      if ("mission_need" in ele.dataset) {
        mission_need[ele.dataset.mission_need] = ele.value;
        eleMissionNeed.value = JSON.stringify(mission_need);
      }
      if ("page_weight" in ele.dataset) {
        page_weight[ele.dataset.page_weight] = ele.value;
        elePageWeight.value = JSON.stringify(page_weight);
      }
    }
    const elePageTypeMissionNeedSend = document.querySelector("#form-edit--user_type input[name='mission_need']");
    const elePageTypePageWeightSend = document.querySelector("#form-edit--user_type input[name='page_weight']");
    let mission_need__edit = {}
    let page_weight__edit = {}
    function onClickUserType(id) {
      const eleUserTypeId = document.querySelector("#form-edit--user_type #ut_id")
      const eleUserTypeName = document.querySelector("#form-edit--user_type #ut_name")
      const allElePageTypeMissionNeed = document.querySelectorAll("#form-edit--user_type [data-mission_need]")
      const allElePageTypePageWeight = document.querySelectorAll("#form-edit--user_type [data-page_weight]")
      const usertype = user_types.find(type=>type.id === id);
      eleUserTypeId.value = usertype.id;
      eleUserTypeName.value = usertype.name;
      elePageTypeMissionNeedSend.value = JSON.stringify(usertype.mission_need);
      elePageTypePageWeightSend.value = JSON.stringify(usertype.page_weight);
      mission_need__edit = usertype.mission_need
      page_weight__edit = usertype.page_weight
      allElePageTypeMissionNeed.forEach(element => {
        const key = Object.keys(element.dataset)[0]
        element.value = usertype[key][element.dataset[key]]
      });
      allElePageTypePageWeight.forEach(element => {
        const key = Object.keys(element.dataset)[0]
        element.value = usertype[key][element.dataset[key]]
      });
    }
    const formChangePwd = document.getElementById("form-change--pwd");
    const labelFormChangePwd = document.querySelector("#form-change--pwd label span span");
    console.log(labelFormChangePwd);
    function onUserChangePassword(uid,type) {
      // const data = type === 'traffic' ? usersTraffic.data : users.data
      const data = users.data
      const user = data.find(ele=>ele.id===uid)
      labelFormChangePwd.textContent = user.username
      console.log(`management/user/${user.id}/change_password`);
      formChangePwd.action = `/management/user/${user.id}/change_password?type=${type}`
    }
    function handleEditChange(ele) {
      if ("mission_need" in ele.dataset) {
        mission_need__edit[ele.dataset.mission_need] = +ele.value;
        elePageTypeMissionNeedSend.value = JSON.stringify(mission_need__edit);
      }
      if ("page_weight" in ele.dataset) {
        page_weight__edit[ele.dataset.page_weight] = +ele.value;
        elePageTypePageWeightSend.value = JSON.stringify(page_weight__edit);
      }
    }
    const formChangeWallet = document.getElementById("form-change--wallet");
    const labelFormChangeWallet = document.querySelector("#form-change--wallet label span span");
    console.log(labelFormChangeWallet);
    function onUserChangeWallet(uid,type) {
      // const data = type === 'traffic' ? usersTraffic.data : users.data
      const data = users.data
      const user = data.find(ele=>ele.id===uid)
      labelFormChangeWallet.textContent = user.username
      console.log(`management/user/${user.id}/change_wallet`);
      formChangeWallet.action = `/management/user/${user.id}/change_wallet?type=${type}`
    }
</script>
@endsection
