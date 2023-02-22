@extends('usdt.index')
@section('withdraw')
@include('box.patternBox3')
@if ($errors->all())
<div class="alert alert-error shadow-lg my-5">
  <div>
    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
      viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    @foreach ($errors->all() as $error)
    <span>
      {{ $error }}
    </span>
    @endforeach
  </div>
</div>
@endif
<!--<div class="alert alert-warning shadow-lg mt-5">-->
<!--  <div>-->
<!--    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>-->
<!--    <span>memtraffic.com gặp sự cố về tài khoản thanh toán tiền. Các đơn rút tiền sẽ được thực hiện vào ngày 21-1 sau khi xử lý sự cố</span>-->
<!--  </div>-->
<!--</div>-->
<div class="container md:w-2/5 mx-auto my-10">
  <form action="/withdraw" method="post" class="" enctype="multipart/form-data">
    @csrf
    <div class="shadow-2xl p-5 rounded-2xl text-center">
      <input type="text" name="amount" placeholder="Nhập số tiền VND muốn rút" class="input input-bordere w-full  mb-5"
        required id="amount">
      {{-- <p class="text-xl mb-5" id="convert-money">~ 0 VND</p> --}}
      <button class="btn btn-block">Rút tiền</button>
    </div>
  </form>
</div>
<div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Thống kê</h3>
    <!-- <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" /> -->
  </div>
  <br />
  <table class="table w-full bg-white">
    <!-- head -->
    <thead class="bg-white">
      <tr>
        <th class="bg-slate-200">Ngày</th>
        <th class="bg-slate-200">Hình thức</th>
        <th class="bg-slate-200">Số tiền</th>
        <th class="bg-slate-200">Trạng thái</th>
      </tr>
    <tbody>
      @if (isset($data))
      @foreach ($data as $item)
      <tr>
        <td class="bg-white">{{$item->created_at}}</td>
        <td class="bg-white">momo</td>
        <td class="bg-white">{{$item->amount}}</td>
        <td class="bg-white">
          @if ($item->status == 0)
          Đang duyệt
          @else
          @if ($item->status == 1)
          Đã duyệt
          @else
          Đã huỷ
          @endif
          @endif
        </td>
      </tr>
      @endforeach
      @endif
    </tbody>
  </table>
  <div class="btn-group flex justify-center mt-5">
    <a class="btn btn-outline btn-sm {{$data->onFirstPage() ? 'btn-disabled' : ''}}"
      href="{{$data->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$data->hasMorePages() ? 'btn-disabled' : ''}}"
      href="{{$data->nextPageUrl()}}">Next</a>
  </div>
</div>
@endsection
