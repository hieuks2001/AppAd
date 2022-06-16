@extends('admin')
@section('management-users')
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl mb-10">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Loại người dùng</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full bg-white">
            <!-- head -->
            <thead class="bg-white">
                <tr>
                    <th class="bg-slate-200">ID</th>
                    <th class="bg-slate-200">Tên</th>
                    <th class="bg-slate-200">Số traffic tối đa / ngày</th>
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
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Danh sách người dùng</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full bg-white">
            <!-- head -->
            <thead class="bg-white">
                <tr>
                    <th class="bg-slate-200">ID</th>
                    <th class="bg-slate-200">Username</th>
                    <th class="bg-slate-200">Loại</th>
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
