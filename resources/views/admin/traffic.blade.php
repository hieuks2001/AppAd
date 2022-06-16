@extends('admin')
@section('management-traffic')
    @php
    $page;
    @endphp
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl mb-10">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Kho Traffic</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full bg-white">
            <!-- head -->
            <thead class="bg-white">
                <tr>
                    <th class="bg-slate-200">URL</th>
                    <th class="bg-slate-200">Username</th>
                    <th class="bg-slate-200">Tổng số lượng traffic</th>
                    <th class="bg-slate-200">Số lượng traffic / ngày</th>
                    <th class="bg-slate-200">Time onsite(s)</th>
                    <th class="bg-slate-200">Đã trả (USDT)</th>
                    <th class="bg-slate-200">Số Traffic còn lại</th>
                </tr>
            <tbody>
                @foreach ($pages as $key => $value)
                    <tr>
                        <td class="bg-white">{{ $value->url }}</td>
                        <td class="bg-white">{{ $value->user->username }}</td>
                        <td class="bg-white">{{ $value->traffic_sum }}</td>
                        <td class="bg-white">{{ $value->traffic_per_day }}</td>
                        <td class="bg-white">{{ $value->onsite }}</td>
                        <td class="bg-white">{{ $value->price }}</td>
                        <td class="bg-white">{{ $value->traffic_remain }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Traffic cần duyệt</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full bg-white">
            <!-- head -->
            <thead class="bg-white">
                <tr>
                    <th class="bg-slate-200">URL</th>
                    <th class="bg-slate-200">Username</th>
                    <th class="bg-slate-200">Tổng số lượng traffic</th>
                    <th class="bg-slate-200">Số lượng traffic / ngày</th>
                    <th class="bg-slate-200">Time onsite(s)</th>
                    <th class="bg-slate-200">Đã trả (USDT)</th>
                    <th class="bg-slate-200">Hành động</th>
                </tr>
            <tbody>
                @foreach ($notApprovedPages as $key => $value)
                    <tr data-row="{{ $value->id }}">
                        <td class="bg-white">{{ $value->url }}</td>
                        <td class="bg-white">{{ $value->user->username }}</td>
                        <td class="bg-white">{{ $value->traffic_sum }}</td>
                        <td class="bg-white">{{ $value->traffic_per_day }}</td>
                        <td class="bg-white">{{ $value->onsite }}</td>
                        <td class="bg-white">{{ $value->price }}</td>
                        <td class="bg-white">
                            <label for="modal-approve--traffic" class="btn btn-success btn-block btn-sm"
                                onclick="@php
                                    $page= $value
                                @endphp">Duyệt</label>
                            <form class="mb-0"
                                action="{{ action('DashboardController@delApproveTraffic', $value->id) }}"
                                method="post">
                                @csrf
                                <button class="btn btn-error btn-block btn-sm">Huỷ</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <input type="checkbox" id="modal-approve--traffic" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative">
            <label for="modal-approve--traffic" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
            @include('admin.editTraffic', ['page' => $page]))
        </div>
    </div>
@endsection
