@extends('admin')
@section('management-traffic')
@php
$page;
@endphp
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Kho Traffic</h3>
    <input type="text" placeholder="Search..." class="input w-full max-w-xs" />
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>URL</th>
        <th>Username</th>
        <th>Tổng traffic</th>
        <th>traffic/ngày</th>
        <th>Time onsite(s)</th>
        <th>Loại site</th>
        <th>Đã trả (VND)</th>
        <th>Traffic còn lại</th>
      </tr>
    <tbody>
      @foreach ($pages as $key => $value)
      <tr>
        <td>{{ $value->url }}</td>
        <td>{{ $value->user->username }}</td>
        <td>{{ $value->traffic_sum }}</td>
        <td>{{ $value->traffic_per_day }}</td>
        <td>{{ $value->onsite }}</td>
        <td>{{ $value->pageType->name }}</td>
        <td>{{ number_format($value->price,5) }}</td>
        <td>{{ $value->traffic_remain }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Traffic cần duyệt</h3>
    <input type="text" placeholder="Search..." class="input w-full max-w-xs" />
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>URL</th>
        <th>Username</th>
        <th>Wallet (VND)</th>
        <th>Tổng traffic</th>
        <th>traffic/ngày</th>
        <th>Time onsite(s)</th>
        <th>Loại site</th>
        <th>Phải trả (VND)</th>
        <th>Hành động</th>
      </tr>
    <tbody>
      @foreach ($notApprovedPages as $key => $value)
      <tr data-row="{{ $value->id }}">
        <td>{{ $value->url }}</td>
        <td>{{ $value->user->username }}</td>
        <td>{{ number_format($value->user->wallet,5) }}</td>
        <td>{{ $value->traffic_sum }}</td>
        <td>{{ $value->traffic_per_day }}</td>
        <td>{{ $value->onsite }}</td>
        <td>{{ $value->pageType->name }}</td>
        <td>{{ number_format($value->price,5) }}</td>
        <td>
          <label for="modal-approve--traffic" class="btn btn-info btn-block btn-sm"
            onclick="onClick({{ $value }})">Sửa</label>
          @if (!empty($value->image))
          <form class="mb-0" action="{{ action('DashboardController@postApproveTraffic', $value->id) }}" method="post">
            @csrf
            <button class="btn btn-success btn-block btn-sm">Duyệt</button>
          </form>
          @endif
          <form class="mb-0" action="{{ action('DashboardController@delApproveTraffic', $value->id) }}" method="post">
            @csrf
            <button class="btn btn-error btn-block btn-sm">Huỷ</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
<input type="checkbox" id="modal-approve--traffic" class="modal-toggle" />
<div class="modal modal-bottom sm:modal-middle text-slate-800">
  <div class="modal-box relative">
    <label for="modal-approve--traffic" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    @include('admin.editTraffic')
  </div>
</div>
<input type="checkbox" id="my-modal" class="modal-toggle" @if (session()->has('error')) checked @endif
/>
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box">
    <h3 class="text-lg font-bold">Thông báo!</h3>
    <p class="py-4">
      @php
      echo Session::get('error');
      @endphp
    </p>
    <div class="modal-action">
      <label for="my-modal" class="btn">HỦY!</label>
    </div>
  </div>
</div>
<script>
  var output = document.getElementById('output');
    var fileUpload = document.getElementById('fileUpload');
    fileUpload?.addEventListener('change', (event) => {
      if (event.target.files.length > 0) {
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
          URL.revokeObjectURL(output.src) // free memory
        }
      } else {
        output.removeAttribute('src');
      }
    });

    const form = document.getElementById('form');
    const noteForm = document.getElementById('noteForm');
    let formItems = document.querySelectorAll('#form .item');

    function onClick(row) {
      output.src = "";
      form.action = `/management/traffic/${row.id}/edit`
      formItems.forEach((item, row_i) => {
        let data = item.classList[item.classList.length - 1]
        if (data in row) {
          if (data == 'user') {
            item.textContent = row[data].username
          } else if (["page_type_id", "priority", "hold_percentage"].includes(
              data)) {
            item.value = row[data]
          } else if (data == "timeout") {
            let date = new Date(row[data] * 1000)
            item.value = `${date.getHours()}:${ date.getMinutes()}`
            console.log(
              `${date.getHours()}:${ date.getMinutes()}`);
            let hourEle = document.getElementById("hour");
            let minuteEle = document.getElementById("minute");
            hourEle.value = date.getHours()
            minuteEle.value = date.getMinutes()
          } else {
            item.textContent = row[data]
          }
        }
      })
      if (row.image) {
        output.src = "/images/" + row.image
      }
    }
</script>
@endsection
