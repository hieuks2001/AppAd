@extends('regispage.index')
@section('tab1-blade')
@parent
<table class="table w-full bg-white">
  <!-- head -->
  <thead class="bg-white">
    <tr>
      <th class="bg-slate-200">URL</th>
      <th class="bg-slate-200">Từ khóa</th>
      <th class="bg-slate-200">Onsite (giây)</th>
      <th class="bg-slate-200">Mỗi ngày</th>
      <th class="bg-slate-200">Tổng</th>
      <th class="bg-slate-200">Số tiền</th>
      <th class="bg-slate-200">Ghi chú</th>
      <th class="bg-slate-200">Trạng thái</th>
    </tr>
  <tbody>
    @foreach($pages as $key => $value)
    <tr>
      <td class="bg-white">{{$value->url}}</td>
      <td class="bg-white">{{$value->keyword}}</td>
      <td class="bg-white">{{$value->onsite}}</td>
      <td class="bg-white">{{$value->traffic_per_day}}</td>
      <td class="bg-white">{{$value->traffic_sum}}</td>
      <td class="bg-white">{{number_format($value->price,0)}}</td>
      @if (!empty($value->note))
      <td class="bg-white">{{$value->note}}</td>
      @else
      <td class="bg-white"></td>
      @endif
      @switch($value->status)
      @case (0)
      <td class="bg-white">Đang chờ</td>
      @break
      @case (1)
      <td class="bg-white">Đã duyệt</td>
      @break
      @case (-1)
      <td class="bg-white">Đã huỷ</td>
      @break
      @endswitch
    </tr>
    @endforeach
  </tbody>
</table>
<div class="btn-group flex justify-center mt-5">
  <a class="btn btn-outline btn-sm {{$pages->onFirstPage() ? 'btn-disabled' : ''}}"
    href="{{$pages->previousPageUrl()}}">Previous</a>
  <a class="btn btn-outline btn-sm {{!$pages->hasMorePages() ? 'btn-disabled' : ''}}"
    href="{{$pages->nextPageUrl()}}">Next</a>
</div>
@endsection
