@extends('main')
@section('balance')
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Danh sách cấp dưới của bạn</h3>
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <td>Loại</td>
        <td>Số tiền biến động</td>
        <td>Trạng thái</td>
        <td>Cập nhập lúc</td>
      </tr>
    <tbody>
      @foreach ($transactions as $key => $value)
      <tr>
        <td>@switch($value->type)
          @case('commission')
          Trả cho cấp trên (@switch($value->status)
          @case(1)
          Bạn mất
          @break
          @case(-1)
          Bạn nhận
          @break
          @default
          Hệ thống tạm giữ
          @endswitch)
          @break
          @case('reward')
          Nhận thưởng nhiệm vụ
          @break
          @case('withdraw')
          Rút tiền
          @break
          @case('admin_add')
          Admin cộng
          @break
          @default
          Unknown
          @endswitch</td>
        <td>{{$value->amount }}</td>
        <td>@switch($value->status)
          @case(1)
          Đã duyệt
          @break
          @case(-1)
          Đã huỷ
          @break
          @default
          Hệ thống tạm giữ
          @endswitch</td>
        <td>{{$value->updated_at}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="btn-group flex justify-center mt-5">
    <a class="btn btn-outline btn-sm {{$transactions->onFirstPage() ? 'btn-disabled' : ''}}"
      href="{{$transactions->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$transactions->hasMorePages() ? 'btn-disabled' : ''}}"
      href="{{$transactions->nextPageUrl()}}">Next</a>
  </div>
</div>
@endsection