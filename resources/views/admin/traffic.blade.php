@extends('admin')
@section('management-traffic')
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Kho Traffic</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full bg-white">
            <!-- head -->
            <thead class="bg-white">
                <tr>
                    <th class="bg-slate-200">ID</th>
                    <th class="bg-slate-200">Username</th>
                    <th class="bg-slate-200">Tổng số lượng traffic</th>
                    <th class="bg-slate-200">Số lượng traffic / ngày</th>
                    <th class="bg-slate-200">Time onsite(s)</th>
                    <th class="bg-slate-200">Đã trả (USDT)</th>
                    <th class="bg-slate-200">Số Traffic còn lại</th>
                </tr>
            <tbody>
                <tr>
                    <td class="bg-white">a</td>
                    <td class="bg-white">b</td>
                    <td class="bg-white">c</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
