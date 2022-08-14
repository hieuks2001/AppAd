@extends('main')
@section('dashboard')
    @include('box.patternBox1')
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl mt-10">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Thống kê</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full bg-white">
            <!-- head -->
            <thead class="bg-white">
                <tr>
                    <th class="bg-slate-200">Ngày</th>
                    <th class="bg-slate-200">Hoa hồng</th>
                    <th class="bg-slate-200">Số tiền nhiệm vụ</th>
                </tr>
            <tbody>
               @foreach($statistical as $key => $value)
                <tr>
                    <td class="bg-white">{{$value->date}}</td>
                    <td class="bg-white">b</td>
                    <td class="bg-white">{{$value->mission_reward}}</td>
                </tr>
               @endforeach
            </tbody>
        </table>
        <div class="btn-group flex justify-center mt-5">
          <a class="btn btn-outline btn-sm {{$statistical->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$statistical->previousPageUrl()}}">Previous</a>
          <a class="btn btn-outline btn-sm {{!$statistical->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$statistical->nextPageUrl()}}">Next</a>
        </div>
    </div>
@endsection
