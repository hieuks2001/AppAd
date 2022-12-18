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
      <th class="bg-slate-200">Mã nhúng</th>
    </tr>
  <tbody>
    @foreach($pages as $key => $value)
    <tr>
      <td class="bg-white">{{$value->url}}</td>
      <td class="bg-white">{{$value->keyword}}</td>
      <td class="bg-white">{{$value->onsite}}</td>
      <td class="bg-white">{{$value->traffic_per_day}}</td>
      <td class="bg-white">{{$value->traffic_sum}}</td>
      <td class="bg-white">{{number_format($value->price, 5)}}</td>
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
      <td class="bg-white">
        <button class="btn btn-square btn-sm" onclick="copyCode('{{$value->id}}')">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5" />
          </svg>
        </button>
      </td>
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
<code hidden>
  &#x3C;div style=&#x22;width: 100%; display: flex; justify-content: center&#x22;&#x3E;
    &#x3C;button
      id=&#x22;getCode&#x22;
      style=&#x22;
        margin-left: 10px;
        display: block;
        padding: 10px 20px;
        outline: none;
        border: 0;
        background-color: red;
        color: white;
        font-weight: bold;
        border-radius: 10px;
      &#x22;
    &#x3E;
      L&#x1EA5;y m&#xE3;
    &#x3C;/button&#x3E;
  &#x3C;/div&#x3E;
  &#x3C;script&#x3E;
    var value = &#x27;__code__&#x27;;
  &#x3C;/script&#x3E;
  &#x3C;script&#x3E;
    const URL_API = &#x27;{{config("app.url")}}&#x27;;
  &#x3C;/script&#x3E;
  &#x3C;script src=&#x22;{{ asset('js/ican.js') }}&#x22;&#x3E;&#x3C;/script&#x3E;
</code>
<script>
  function copyCode(pageId) {
    navigator.clipboard.writeText(document.getElementsByTagName("code")[0].textContent.replace('__code__',pageId))
  }
</script>
@endsection
