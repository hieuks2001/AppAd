@extends('main')
@section('regispage')
    @include('box.patternBox2')
    <br />
    <div class="container md:w-2/5 mx-auto" style="margin-top:5%">
        @if ($errors->all())
            <div class="ui error message">
                @foreach ($errors->all() as $err)
                    {{ $err }}
                @endforeach
            </div>
        @endif
        @if (session()->has('message'))
            <div class="ui success message">
                @php
                    echo Session::get('message');
                @endphp
            </div>
        @endif
        <form action="/add-page" method="post" class="" enctype="multipart/form-data">
            @csrf
            <div class="shadow-2xl p-5 rounded-2xl text-center">
                <input type="text" name="url" placeholder="Nhập url đích muốn chạy traffic"
                    class="input input-bordered w-full  mb-5" required>
                <input type="text" name="keyword" placeholder="Nhập từ khóa" class="input input-bordered w-full  mb-5"
                    required>
                <input type="number" name="traffic_per_day" placeholder="Nhập lượng Traffic mỗi ngày"
                    class="input input-bordered w-full  mb-5" required>
                <input type="number" name="traffic_sum" placeholder="Nhập tổng Traffic"
                    class="input input-bordered w-full  mb-5" required>
                <select name="page_type" id="" class="select select-bordered w-full mb-5">
                    <option selected disabled> Vui lòng chọn loại site </option>
                    @foreach ($onsite as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                </select>
                <select name="onsite" id="" class="select select-bordered w-full mb-5">
                    <option selected disabled> Vui lòng chọn gói Onsite </option>
                    @foreach ($onsite as $key1 => $value1)
                        @foreach ($value1->onsite as $key => $value)
                        <option value="{{ $key }}">{{ $value1->name }} Time onsite > {{ $key }}</option>
                        @endforeach
                    @endforeach
                </select>

                <!-- <label class="block">
                            <span class="sr-only">Choose profile photo</span>
                            <input type="file" accept="image/*"
                                class="block w-full text-sm text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-violet-50 file:text-violet-700
                                        hover:file:bg-violet-100 mb-5"
                                name="image" id="fileUpload" required> -->
                </label>
                <div class="avatar mb-5">
                    <div class="max-w-xs rounded">
                        <img id="output" class="object-contain" />
                    </div>
                </div>
                <button class="btn btn-block">Submit</button>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl mt-10">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Nhiệm vụ đã thực hiện</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        @php
            $tabs = ['Đang chờ', 'Đang chạy', 'Hoàn thành', 'Lỗi'];
        @endphp
        <div class="tabs tabs-boxed my-3 bg-transparent">
            @foreach ($tabs as $index => $tab)
                <a class="tab text-lg" href="/regispage/tab-{{ (int) $index + 1 }}">{{ $tab }}</a>
            @endforeach
        </div>
        <br />
        @yield('tab1-blade')
        @yield('tab2-blade')
        @yield('tab3-blade')
        @yield('tab4-blade')
    </div>
    <script>
        @foreach( $onsite as $key => $value)
            console.log("{{$value->name}}")
            console.log(@json($value->onsite))
        @endforeach
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
        let tabs = document.querySelectorAll('.tab');
        if (window.location.href.endsWith("regispage")) {
            tabs[0].classList.add("tab-active", "text-white")
        } else {
            tabs.forEach(tab => {
                if (window.location.href.includes(tab.getAttribute('href'))) {
                    tab.classList.add("tab-active", "text-white")
                }
            });
        }
        window.scrollTo(0, document.body.scrollHeight);
    </script>
@endsection
