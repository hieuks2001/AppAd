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
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Username</th>
                    <th>Tổng traffic</th>
                    <th>traffic/ngày</th>
                    <th>Time onsite(s)</th>
                    <th>Loại site</th>
                    <th>Đã trả (USDT)</th>
                    <th>Traffic còn lại</th>
                </tr>
            <tbody>
                @foreach ($pages as $key => $value)
                    <tr>
                        <td>{{ $value->url }}</td>
                        <td>{{ $value->user->username }}</td>
                        <td>{{ $value->traffic_sum }}</td>
                        <td>{{ $value->traffic_per_day }}</td>
                        <td>{{ $value->onsite }}</td>
                        <td>{{ $value->pageType->name }}</td>
                        <td>{{ $value->price }}</td>
                        <td>{{ $value->traffic_remain }}</td>
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
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Username</th>
                    <th>Wallet (USDT)</th>
                    <th>Tổng traffic</th>
                    <th>traffic/ngày</th>
                    <th>Time onsite(s)</th>
                    <th>Loại site</th>
                    <th>Phải trả (USDT)</th>
                    <th>Hành động</th>
                </tr>
            <tbody>
                @foreach ($notApprovedPages as $key => $value)
                    <tr data-row="{{ $value->id }}">
                        <td>{{ $value->url }}</td>
                        <td>{{ $value->user->username }}</td>
                        <td>{{ $value->user->wallet }}</td>
                        <td>{{ $value->traffic_sum }}</td>
                        <td>{{ $value->traffic_per_day }}</td>
                        <td>{{ $value->onsite }}</td>
                        <td>{{ $value->pageType->name }}</td>
                        <td>{{ $value->price }}</td>
                        <td>
                            <label for="modal-approve--traffic" class="btn btn-info btn-block btn-sm"
                                onclick="
                            onClick({{ $value }})">Sửa</label>
                            @if (!empty($value->image))
                                <form class="mb-0"
                                    action="{{ action('DashboardController@postApproveTraffic', $value->id) }}"
                                    method="post">
                                    @csrf
                                    <button class="btn btn-success btn-block btn-sm">Duyệt</button>
                                </form>
                            @endif
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
            @include('admin.editTraffic', $notApprovedPages)
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
        const noteForm = document.getElementById('noteForm');
        let formItems = document.querySelectorAll('#form .item');

        function onClick(row) {
            console.log(row);
            form.action = `/management/traffic/${row.id}/edit`
            formItems.forEach((item, row_i) => {
                let data = item.classList[item.classList.length - 1]
                if (data in row) {
                    if (data == 'user') {
                        item.textContent = row[data].username
                    } else if (data == "page_type_id") {
                        item.value = row[data]
                    } else if (data == "page_type") {
                        for (const k in row[data].onsite) {
                            const option = document.createElement("option");
                            option.value = k;
                            if (row.onsite == k) {
                                console.log(row.onsite);
                                option.setAttribute("selected", true)
                            }
                            option.textContent = `${row[data].name} Time onsite > ${k}s`;
                            selectSiteTypeOnsiteEle.appendChild(option);
                        }
                    } else {
                        item.textContent = row[data]
                    }
                }
            })
            if (row.image) {
                output.src = "/images/" + row.image
            }
        }
    </script>
@endsection
