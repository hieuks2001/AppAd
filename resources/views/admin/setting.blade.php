@extends('admin')
@section('management-users')
@php
$labels = [];
$labels["minimum_reward"] = "Mức thu nhập tối thiểu hằng ngày";
$labels["delay_day_week"] = "Số lượng ngày nghỉ tối đa của 1 user (Tuần)";
$labels["delay_day_month"] = "Số lượng ngày nghỉ tối đa của 1 user (Tháng)";
$labels["commission_rate_1"] = "Mức % tiền ăn từ cấp dưới (1 Level)";
$labels["commission_rate_2"] = "Mức % tiền ăn từ cấp dưới (2 Level)";
$labels["max_ref_user_per_day_week"] = "Số lượng User được mời tối đa mỗi ngày (Ăn trọn theo tuần)";
$labels["max_ref_user_per_day_month"] = "Số lượng User được mời tối đa mỗi ngày (Tách line tháng)";
$labels["ref_user_required_week"] = "Số lượng người dùng yêu cầu (Ăn trọn theo tuần)";
$labels["ref_user_required_month"] = "Số lượng người dùng yêu cầu (Tách line tháng)";
$labels["minimum_withdraw"] = "Mức rút tối thiểu";

@endphp
<div class="flex justify-center">
  <div class="ms:w-4/5 md:w-3/5 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
    <form id="form-edit" method="POST" class="mb-0" action="{{ URL::to('/management/setting') }}">
      @csrf
      @foreach ($settings as $setting)
      <div class="items-end mb-5">
        <div class="">
          <p class="text-md sm:text-lg md:text-xl font-bold text-slate-800 mb-2">{{$labels[$setting->name]}}</p>
          <input type="number" step="any" required name="{{$setting->name}}" value="{{$setting->value}}"
            class="input input-bordered input-sm w-full">
        </div>
      </div>
      @endforeach
      <button type="submit" class="btn btn-block">Lưu</button>
    </form>
  </div>
</div>

@if(Session::has('error') or Session::has('message'))
<input type="checkbox" id="modal-notificate" class="modal-toggle" checked />
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
@endsection
