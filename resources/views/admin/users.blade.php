@extends('admin')
@section('management-users')
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl mb-10">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Loại người dùng</h3>
            <div class="flex">
                <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs mr-3" />
                <label for="modal-create" class="btn modal-button gap-2 btn-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Tạo
                </label>
            </div>
        </div>
        <br />
        <table class="table w-full ">
            <!-- head -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Số traffic tối đa / ngày</th>
                </tr>
            <tbody>
                @foreach ($userTypes as $key => $value)
                    <tr>
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->max_traffic }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Danh sách người dùng</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full" id="table-users">
            <!-- head -->
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Loại</th>
                    <th></th>
                </tr>
            <tbody>
                @foreach ($users as $key => $value)
                    <tr>
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->username }}</td>
                        <td>{{ $value->userType->name }}</td>
                        <td>
                            <label for="modal-edit" class="btn btn-square btn-outline btn-sm"
                                onclick="onClickUser('{{ $value->id }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </label>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <input type="checkbox" id="modal-edit" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative">
            <label for="modal-edit" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
            <form id="form-edit" method="post" class="mb-0"> {{-- action in js dom --}}
                @csrf
                <p class="label-text font-bold">
                    Tên người dùng:
                    <span class="item font-normal username"></span>
                </p>
                <div class="form-control max-w-full">
                    <label class="label">
                        <span class="label-text font-bold">Loại người dùng</span>
                    </label>
                    <div class="flex">
                        <select name="user_type" class="item flex-1 select select-bordered w-full mb-3 user_type">
                            @foreach ($user_types as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" class="input input-bordered ml-3 w-14 read-only:bg-slate-200 max_traffic"
                            readonly />
                        <input name="user_id" type="hidden" value="">
                    </div>
                </div>
                <button type="submit" class="btn btn-block">Sửa</button>
            </form>
        </div>
    </div>
    <input type="checkbox" id="modal-create" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box relative">
            <label for="modal-create" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
            <h3 class="font-bold text-lg mb-3">Tạo loại người dùng mới</h3>
            <form id="form-create" action="/management/usertypes" method="post" class="mb-0">
                @csrf
                <input type="text" name="name" placeholder="Tên loại người dùng"
                    class="input input-bordered w-full mb-3" />
                <input type="text" name="max_traffic" placeholder="Giới hạn nhiệm vụ trong ngày"
                    class="input input-bordered w-full mb-3" />
                <button type="submit" class="btn btn-block">Tạo</button>
            </form>
        </div>
    </div>
    <script>
        const users = {!! json_encode($users->toArray(), JSON_HEX_TAG) !!}
        const user_types = {!! json_encode($user_types->toArray(), JSON_HEX_TAG) !!}
        const formEdit = document.getElementById('form-edit');
        const items = document.querySelectorAll('#form-edit .item');
        const userTypeEle = document.querySelector("#form-edit .user_type");
        const maxTrafficEle = document.querySelector("#form-edit .max_traffic");

        function onClickUser(uid) {
            const user = users.find(user => user.id == uid)
            /* Setting the action attribute of the form to the url of the user. */
            formEdit.action = `/admin/users/${uid}`;
            items.forEach(item => {
                let key = item.classList[item.classList.length - 1];
                if (key in user) {
                    if (key === "user_type") {
                        item.value = user[key].id;
                        const userTypeObj = user_types.find(type => type.id == user[key].id);
                        maxTrafficEle.value = userTypeObj.max_traffic;
                    } else {
                        item.textContent = user[key];
                    }
                }
            })
        }
        userTypeEle.addEventListener("change", function(e) {
            const userType = e.target.value;
            const userTypeObj = user_types.find(user => user.id == userType);
            maxTrafficEle.value = userTypeObj.max_traffic;
        });
    </script>
@endsection
