@extends('usdt.index')
@section('withdraw')
    @include('box.patternBox3')
    <div class="container md:w-2/5 mx-auto my-10">
        <form action="/withdraw" method="post" class="" enctype="multipart/form-data">
            @csrf
            <div class="shadow-2xl p-5 rounded-2xl text-center">
                <input type="text" name="amount" placeholder="Nhập số tiền USDT muốn rút"
                    class="input input-bordere w-full  mb-5" required>
                <button class="btn btn-block">Rút tiền</button>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
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
          <a class="btn btn-outline btn-sm {{$data->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$data->previousPageUrl()}}">Previous</a>
          <a class="btn btn-outline btn-sm {{!$data->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$data->nextPageUrl()}}">Next</a>
        </div>
      </div>
@endsection
