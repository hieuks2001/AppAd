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
                    <th class="bg-slate-200">Ghi chú</th>
                    <th class="bg-slate-200">Trạng thái</th>
                </tr>
            <tbody>
                <tr>
                    <td class="bg-white">a</td>
                    <td class="bg-white">a</td>
                    <td class="bg-white">a</td>
                    <td class="bg-white">b</td>
                    <td class="bg-white">c</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
