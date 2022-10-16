@extends('admin')
@section('management-users')
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Mức thu nhập tối thiểu hằng ngày</h3>
  </div>
  <br>
  <form method="POST" action="{{ URL::to('/management/setting') }}">
    @csrf
    <input type="hidden" name="name" value="{{$minimumReward->name}}"
        class="appearance-none input input-bordered w-full mb-5 ">
    <input type="number" step="any" name="value" value="{{$minimumReward->value}}" class="input input-bordered w-full mb-5">
    <button class="btn btn-block">Lưu</button>
  </form>
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Số lượng ngày nghỉ tối đa của 1 user (Tuần)</h3>
  </div>
  <br>
  <form method="POST" action="{{ URL::to('/management/setting') }}">
    @csrf
    <input type="hidden" name="name" value = "{{$delayDayWeek->name}}"
        class="appearance-none input input-bordered w-full mb-5 ">
    <input type="number" name="value" value="{{$delayDayWeek->value}}" class="input input-bordered w-full mb-5">
    <button class="btn btn-block">Lưu</button>
  </form>
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Số lượng ngày nghỉ tối đa của 1 user (Tháng)</h3>
  </div>
  <br>
  <form method="POST" action="{{ URL::to('/management/setting') }}">
    @csrf
    <input type="hidden" name="name" value = "{{$delayDayMonth->name}}"
        class="appearance-none input input-bordered w-full mb-5 ">
    <input type="number" name="value" value="{{$delayDayMonth->value}}" class="input input-bordered w-full mb-5">
    <button class="btn btn-block">Lưu</button>
  </form>
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
@endsection
