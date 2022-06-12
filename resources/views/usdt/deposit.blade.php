@extends('main')
@section('deposit')
    @include('box.patternBox2')
    <div class="mt-5">
        <input type="text" placeholder="Nhập số tiền muốn nạp" class="input input-bordered w-full max-w-xs " />
        <button class="btn btn-primary">Nạp tiền</button>
    </div>
@endsection
