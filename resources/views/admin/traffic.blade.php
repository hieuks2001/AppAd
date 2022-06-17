@extends('admin')
@section('management-traffic')
    @php
    $page;
    @endphp
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl mb-10">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Kho Traffic</h3>
            <input type="text" placeholder="Search..." class="input w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full ">
            <!-- head -->
            <thead class="">
                <tr>
                    <th>URL</th>
                    <th>Username</th>
                    <th>Tổng số lượng traffic</th>
                    <th>Số lượng traffic / ngày</th>
                    <th>Time onsite(s)</th>
                    <th>Đã trả (USDT)</th>
                    <th>Số Traffic còn lại</th>
                </tr>
            <tbody>
                @foreach ($pages as $key => $value)
                    <tr>
                        <td class="">{{ $value->url }}</td>
                        <td class="">{{ $value->user->username }}</td>
                        <td class="">{{ $value->traffic_sum }}</td>
                        <td class="">{{ $value->traffic_per_day }}</td>
                        <td class="">{{ $value->onsite }}</td>
                        <td class="">{{ $value->price }}</td>
                        <td class="">{{ $value->traffic_remain }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Traffic cần duyệt</h3>
            <input type="text" placeholder="Search..." class="input w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full ">
            <!-- head -->
            <thead class="">
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
                        <td class="">{{ $value->url }}</td>
                        <td class="">{{ $value->user->username }}</td>
                        <td class="">{{ $value->traffic_sum }}</td>
                        <td class="">{{ $value->traffic_per_day }}</td>
                        <td class="">{{ $value->onsite }}</td>
                        <td class="">{{ $value->price }}</td>
                        <td class="">
                            <label for="modal-approve--traffic" class="btn btn-success btn-block btn-sm"
                                onclick="
                            onClick({{ $value }})">Duyệt</label>
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
    <div class="modal text-slate-800">
        <div class="modal-box relative">
            <label for="modal-approve--traffic" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
            @include('admin.editTraffic')
        </div>
    </div>
    <script>
        var output = document.getElementById('output');
        var fileUpload = document.getElementById('fileUpload');
        fileUpload?.addEventListener('change', (event) => {
            if (event.target.files.length > 0) {
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                    URL.revokeObjectURL(output.src) // free memory
                }
            } else {
                output.removeAttribute('src');
            }
        });

        const form = document.getElementById('form');
        let formItems = document.querySelectorAll('#form .item');

        function onClick(row) {
            form.action = `/management/traffic/${row.id}`
            formItems.forEach((item, row_i) => {
                let data = item.classList[item.classList.length - 1]
                if (data in row) {
                    if (data == 'user') {
                        item.innerHTML = row[data].username
                    } else {
                        item.innerHTML = row[data]
                    }
                }
            })
        }
    </script>
@endsection
