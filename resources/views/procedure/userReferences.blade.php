@extends('main')
@section('ref')

@isset($notifications)
@foreach ($notifications as $noti)
<div class="alert alert-warning shadow-lg">
  <div>
      <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
          viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{$noti->content}}</span>
  </div>
</div>
@endforeach

@endisset

@isset($lv1)
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Cấp trên Level 1 - Bạn phải trả {{$commisonRateV1->value}}% hoa hồng</h3>
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>Username</th>
        <th>SĐT</th>
        <th>Trạng thái</th>
      </tr>
    <tbody>
        <tr>
          <td>{{ $lv1->username }}</td>
          <td>{{ $lv1->phone_number }}</td>
          <td>{{
            $lv1->status == 1 ? "Đang hoạt động" : "Khoá"
          }}</td>
        </tr>
    </tbody>
  </table>
</div>

@else
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Chúc mừng, bạn không là cấp dưới của tài khoản nào.</h3>
  </div>
</div>

@endisset


@isset($lv2)
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Cấp trên Level 2 - Bạn phải trả {{$commisonRateV2->value}}% hoa hồng</h3>
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>Username</th>
        <th>SĐT</th>
        <th>Trạng thái</th>
      </tr>
    <tbody>
        <tr>
          <td>{{ $lv2->username }}</td>
          <td>{{ $lv2->phone_number }}</td>
          <td>{{
            $lv2->status == 1 ? "Đang hoạt động" : "Khoá"
          }}</td>
        </tr>
    </tbody>
  </table>
</div>
@endisset

<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Danh sách cấp dưới của bạn</h3>
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>Username</th>
        <th>SĐT</th>
        <th>Ngày được mời</th>
        <th>Trạng thái</th>
      </tr>
    <tbody>
      @foreach ($users as $key => $value)
        <tr>
          <td>{{ $value->username }}</td>
          <td>{{ $value->phone_number }}</td>
          <td>{{ $value->created_at }}</td>
          <td>{{
            $value->status == 1 ? "Đang hoạt động" : "Khoá"
          }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="btn-group flex justify-center mt-5">
    <a class="btn btn-outline btn-sm {{$users->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$users->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$users->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$users->nextPageUrl()}}">Next</a>
  </div>
</div>
@endsection
