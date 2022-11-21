@extends('admin')
@section('management-users')
@php
$labels = [];
$labels["reward"] = "Thưởng nhiệm vụ";
$labels["admin_add"] = "ADMIN cộng";
$labels["admin_minus"] = "ADMIN trừ";
$labels["topup"] = "Nạp";
$labels["pay"] = "Trả tiền dịch vụ";
$labels["refund"] = "Hoàn tiền";
$labels["withdraw"] = "Rút";
$labels["commission"] = "Nhận hoa hồng";

@endphp

  @php
  $page;
  @endphp
  @if (!empty($user))
  <div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-slate-800">{{$user->username}}</h3>
      <h3 class="text-2xl font-bold text-slate-800">Lịch sử giao dịch</h3>
    </div>
    <br />
    <table class="table w-full">
      <!-- head -->
      <thead>
        <tr>
          <th>Loại</th>
          <th>Mức giao dịch</th>
          <th>Số tiền trước</th>
          <th>Số tiền sau</th>
          <th>Ngày thực hiện</th>
          <th>Trạng thái</th>
          <th>Người thực hiện</th>
        </tr>
      <tbody>
        @foreach ($transactions as $key => $value)
          <tr>
            <td>{{ $labels[$value->type] }}</td>
            <td>{{ $value->amount }}</td>
            <td>{{ $value->before }}</td>
            <td>{{ $value->after }}</td>
            <td>{{ $value->created_at}}</td>
            <td>{{ $value->status == 1 ? "Đã duyệt" : "Chưa duyệt"}}</td>
            <td>
              @if ($value->type === "admin_add" or $value->type === "admin_minus")
                ADMIN
              @else
                {{$value->username}}
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="btn-group flex justify-center mt-5">
      <a class="btn btn-outline btn-sm {{$transactions->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$transactions->previousPageUrl()}}">Previous</a>
      <a class="btn btn-outline btn-sm {{!$transactions->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$transactions->nextPageUrl()}}">Next</a>
    </div>
  </div>
  @else
  <div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-slate-800">Lịch sử giao dịch</h3>
    </div>
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-slate-800">Không tồn tại lịch sử giao dịch</h3>
    </div>
  </div>
  @endif

@endsection
