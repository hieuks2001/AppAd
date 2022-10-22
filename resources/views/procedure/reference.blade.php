@extends('main')
@section('ref')
@if ($errors->all())
<div class="alert alert-error shadow-lg mb-5">
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
<br>
<div class="flex justify-center">
  {{-- <form action="{{ URL::to('update-ref') }}" method="post">
    @csrf
    <input type="text" name="reference" placeholder="Mã giới thiệu" class="input input-bordered w-full mb-5">
    <button type="submit" class="btn btn-success text-xl">Cập nhật</button>
  </form> --}}

  <input readonly class="input input-bordered w-full max-w-xs" value="{{route('register',
    ['ref'=>Auth::user()->id])}}" />
  <div class="tooltip tooltip-left ml-3" data-tip="sao chép từ khóa">
    <label class="swap btn btn-square">
      <input type="checkbox" id="btn-copy-ref" />
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
  <script>
    document.getElementById("btn-copy-ref").addEventListener("change",(e)=>{
      if (e.currentTarget.checked) {
        navigator.clipboard.writeText({!!json_encode(route('register',['ref'=>Auth::user()->id]))!!})
      } else {
        document.getElementById("btn-copy-ref").checked = true
      }
    })
  </script>
</div>
@endsection
