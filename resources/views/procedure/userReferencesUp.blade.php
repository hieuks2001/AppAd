@extends('main')
@section('ref')


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
        <!--<th>Username</th>-->
        <th>SĐT</th>
        <th>Trạng thái</th>
      </tr>
    <tbody>
        <tr>
          <!--<td>{{ substr($lv1->phone_number,0,6) }}</td>-->
          <td>{{ substr($lv1->phone_number,0,6) }}</td>
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
          <td>{{ substr($lv2->phone_number,0,6) }}</td>
          <td>{{
            $lv2->status == 1 ? "Đang hoạt động" : "Khoá"
          }}</td>
        </tr>
    </tbody>
  </table>
</div>
@endisset
@endsection
