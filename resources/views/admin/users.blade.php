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
          <label for="modal-edit--user_type" class="btn btn-square btn-outline btn-sm" onclick="onClickUserType('{{ $value->id }}')">
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
      </tr>
    </thead>
    <tbody>
      @foreach ($usersTraffic as $key => $value)
      <tr>
        <td>{{ $value->id }}</td>
        <td>{{ $value->username }}</td>
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
          <label for="modal-edit--user_mission" class="btn btn-square btn-outline btn-sm" onclick="onClickUser('{{ $value->id }}')">
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
        @if ($key !== count($page_types) - 1)
        <label class="input-group">
          <div class="flex items-center w-full pl-4 bg-slate-200">Số lượng cần để lên
            <span class="font-bold">
              loại {{$item->name+1}}
            </span>
          </div>
          <input type="text" placeholder="10" data-mission_need="{{$item->id}}" oninput="handleEditChange(this)"
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
          <input type="text" placeholder="10" data-page_weight="{{$item->id}}" oninput="handleEditChange(this)"
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
@if(Session::has('error') or Session::has('message'))
  <input type="checkbox" id="modal-notificate" class="modal-toggle" checked/>
@endif
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-notificate" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    @if (Session::has('error'))
      <p class="my-5 text-xl">{{Session::get('error')}}</p>
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
</script>
@endsection