@extends('main')
@section('regispage')
    @include('box.patternBox2')
    <br />
    <div class="container md:w-2/5 mx-auto" style="margin-top:5%">

        @if ($errors->all())
            @foreach ($errors->all() as $err)
                <div class="alert alert-error shadow-lg mb-5">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>
                            {{ $err }}
                        </span>
                    </div>
                </div>
            @endforeach
        @endif
        @if (session()->has('message'))
            <div class="alert alert-success shadow-lg mb-5">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        @php
                            echo Session::get('message');
                        @endphp
                    </span>
                </div>
            </div>
        @endif
        <form action="/add-page" method="post" class="" enctype="multipart/form-data">
            @csrf
            <div class="shadow-2xl p-5 rounded-2xl text-center">
                <input type="text" name="url" placeholder="Nhập url đích muốn chạy traffic"
                    class="input input-bordered w-full  mb-5" required>
                <input type="text" name="keyword" placeholder="Nhập từ khóa" class="input input-bordered w-full  mb-5"
                    required>
                <input type="text" name="traffic_per_day" placeholder="Nhập lượng Traffic mỗi ngày"
                    class="input input-bordered w-full  mb-5" required>
                <input type="text" name="traffic_sum" id="traffic_sum" placeholder="Nhập tổng Traffic"
                    class="input input-bordered w-full  mb-5" required>
                <select name="page_type" id="site_types" class="select select-bordered w-full mb-5">
                    <option selected disabled> Vui lòng chọn loại site </option>
                    @foreach ($onsite as $key => $value)
                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                    @endforeach
                </select>
                <select name="onsite" id="site_type__onsite" class="select select-bordered w-full mb-5">
                    <option selected disabled> Vui lòng chọn gói Onsite </option>
                    {{-- @foreach ($onsite as $key1 => $value1)
                        @foreach ($value1->onsite as $key => $value)
                            <option value="{{ $key }}">{{ $value1->name }} Time onsite > {{ $key }}
                            </option>
                        @endforeach
                    @endforeach --}}
                </select>
                <p id="price" class="input input-bordered w-full read-only:bg-slate-200 p-2 text-start">Tổng USDT phải
                    trả</p>
                <!-- <label class="block">
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
        @foreach ($onsite as $key => $value)
            console.log("{{ $value->name }}")
            console.log(@json($value->onsite))
        @endforeach

        const siteTypes = {!! json_encode($onsite->toArray(), JSON_HEX_TAG) !!}
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


        const selectSiteTypesEle = document.getElementById("site_types");
        const selectSiteTypeOnsiteEle = document.getElementById("site_type__onsite");
        const priceEle = document.getElementById("price");
        const trafficSumEle = document.getElementById("traffic_sum");

        selectSiteTypesEle.addEventListener("change", (e) => {
            while (selectSiteTypeOnsiteEle.childNodes.length > 2) {
                selectSiteTypeOnsiteEle.removeChild(selectSiteTypeOnsiteEle.lastChild);
            }
            const siteType = siteTypes.find(siteType => siteType.id == e.target.value);
            for (const k in siteType.onsite) {
                const option = document.createElement("option");
                option.value = k;
                option.textContent = `${siteType.name} Time onsite > ${k}s`;
                selectSiteTypeOnsiteEle.appendChild(option);
            }
        })

        selectSiteTypeOnsiteEle.addEventListener("change", (e) => {
            handlePriceChange(trafficSumEle.value, e.target.value)
        })
        trafficSumEle.addEventListener("input", (e) => {
            handlePriceChange(e.target.value, selectSiteTypeOnsiteEle.value)
        })

        function handlePriceChange(trafficSumValue, timeOnsiteValue) {
            const siteType = selectSiteTypesEle.value;
            const trafficSum = trafficSumValue;
            const timeOnsite = siteTypes.find(site => site.id == siteType).onsite[timeOnsiteValue];
            console.log(timeOnsite, trafficSum);
            priceEle.textContent = `${(timeOnsite * trafficSum).toFixed(2)} USDT`;
        }
        window.scrollTo(0, document.body.scrollHeight);
    </script>
@endsection
