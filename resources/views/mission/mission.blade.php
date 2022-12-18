@extends('main')
@section('mission')
@include('box.patternBox1')
<br />
@if (!isset($mission))
@include('mission.startmission')
@else
@if ($errors->all())
@if ($errors->all()[0] === "Nhiệm vụ bị quá hạn, vui lòng hủy và nhận lại")
<div class="alert alert-error mb-5 shadow-lg">
  <div>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 stroke-current" fill="none"
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
@endif
<div class="container mx-auto select-none overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl md:w-2/3 relative">
  <div class="tooltip tooltip-left absolute right-5" data-tip="sao chép từ khóa">
    <label class="swap btn btn-square">
      <input type="checkbox" id="btn-copy-kw" />
      <svg xmlns="http://www.w3.org/2000/svg" class="swap-off h-6 w-6" fill="none" viewBox="0 0 24 24"
        stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
      </svg>
      <svg xmlns="http://www.w3.org/2000/svg" class="swap-on h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
        <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
        <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h8a2 2 0 00-2-2H5z" />
      </svg>
    </label>
  </div>
  @php
  $array_kw = explode(",", $page->keyword);
  $kw = $array_kw[array_rand($array_kw,1)]; //name & type
  $imgTmp = explode(".", $page->image);
  $imgName = $imgTmp[0] . '-' . trim($kw) . '.' . $imgTmp[1];
  @endphp
  <picture class="mx-auto">
    <source media="(max-width: 799px)" srcset="{{config('app.img_url')}}/images/small/{{$imgName}}" />
    <source media="(min-width: 800px)" srcset="{{config('app.img_url')}}/images/large/{{$imgName}}" />
    <img src="{{config('app.img_url')}}/images/large/{{$imgName}}" alt="">
  </picture>
  @if ($errors->all())
  @if ($errors->all()[0] !== "Nhiệm vụ bị quá hạn, vui lòng hủy và nhận lại")
  <div class="alert alert-error my-3 shadow-lg">
    <div>
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 stroke-current" fill="none"
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
  @endif
  <form action="/paste-key" method="post" class="mb-0">
    @csrf
    <input type="text" name="key" placeholder="Nhập mã của bạn vào đây..." class="input input-bordered mb-3 w-full">
    <button class="btn btn-block mb-3">Hoàn thành nhiệm vụ</button>
    <a class="btn btn-block btn-outline" href="/cancel-mission">Bỏ qua nhiệm
      vụ</a>
  </form>
</div>
<script>
  document.getElementById("btn-copy-kw").addEventListener("change",(e)=>{
        if (e.currentTarget.checked) {
          navigator.clipboard.writeText({!!json_encode($page->keyword)!!})
        } else {
          document.getElementById("btn-copy-kw").checked = true
        }
      })
</script>
@endif
<br />
<br />
<div class="overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Nhiệm vụ đã thực hiện</h3>
    {{-- <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" /> --}}
  </div>
  <br />
  <table class="table w-full bg-white">
    <!-- head -->
    <thead class="bg-white">
      <tr>
        <th class="bg-slate-200">Nhiệm vụ</th>
        <th class="bg-slate-200">Bắt đầu nhiệm vụ</th>
        <th class="bg-slate-200">Hoàn thành lúc</th>
        <th class="bg-slate-200">Số tiền</th>
        <th class="bg-slate-200">Trạng thái</th>
      </tr>
    <tbody>
      @foreach ($missions as $mission)
      <tr>
        <td>{{ $mission->id }}</td>
        <td>{{ $mission->created_at }}</td>
        @if($mission->status == 1)
        <td>{{ $mission->updated_at }}</td>
        @else
        <td></td>
        @endif
        <td>{{ number_format($mission->reward,5) }}</td>
        @switch($mission->status)
        @case (0)
        <td class="bg-white">Đang chờ</td>
        @break

        @case (1)
        <td class="bg-white">Hoàn thành</td>
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
    <a class="btn btn-outline btn-sm {{$missions->onFirstPage() ? 'btn-disabled' : ''}}"
      href="{{$missions->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$missions->hasMorePages() ? 'btn-disabled' : ''}}"
      href="{{$missions->nextPageUrl()}}">Next</a>
  </div>
</div>
@endsection
