<form id="form" enctype="multipart/form-data" method="POST" class="mb-0">
    @csrf
    <p class="font-bold mb-3">URL: <span class="item font-normal url"></span></p>
    <p class="font-bold mb-3">Username: <span class="item font-normal user"></span></p>
    <p class="font-bold mb-3">Tổng số lượng traffic: <span id="traffic_sum" class="item font-normal traffic_sum"></span>
    </p>
    <p class="font-bold mb-3">Số lượng traffic trong ngày: <span class="item font-normal traffic_per_day"></span></p>
    <p class="font-bold mb-3">Time onsite: <span class="item font-normal onsite"></span></p>
    <p class="font-bold mb-3">Phải trả (USDT): <span id="price" class="item font-normal price"></span></p>

    <label class="block">
        <span class="sr-only">Choose profile photo</span>
        <input type="file" accept="image/*"
            class="block w-full text-sm text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-violet-50 file:text-violet-700
                                        hover:file:bg-violet-100 mb-5"
            name="image" id="fileUpload">
    </label>
    <div class="flex justify-between items-center">
        <p>
            Chọn mức ưu tiên
        </p>
        <select class="select w-full max-w-xs" name="priority" id="" required>
            <option selected disabled> Vui lòng chọn ưu tiên </option>
            @foreach ($priority as $key => $value)
                <option value="{{ $value }}">{{ $key }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex justify-between items-center">
        <p>
            Chọn loại site tương ứng
        </p>
        <select id="site_types" class="item select w-full max-w-xs page_type_id" name="page_type" required>
            <option selected disabled> Vui lòng chọn loại site </option>
            @foreach ($onsite as $key => $value)
                <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="flex justify-between items-center">
        <p>
            Chọn gói onsite tương ứng
        </p>
        <select id="site_type__onsite" name="onsite" class="item select select w-full max-w-xs mb-5 page_type">
            <option selected disabled> Vui lòng chọn gói Onsite </option>
            {{-- @foreach ($onsite as $key1 => $value1)
                @foreach ($value1->onsite as $key => $value)
                    <option value="{{ $key }}">{{ $value1->name }} Time onsite > {{ $key }}
                    </option>
                @endforeach
            @endforeach --}}
        </select>
    </div>

    <div class="flex justify-between items-center">
        <p>
            Chọn timeout
        </p>
        <input type="time" name="timeout" id="">
    </div>
    <div>
        <p>Ghi chú</p>
        <textarea name="note" class="w-full textarea textarea-bordered" placeholder="Ghi chú ở đây"></textarea>
    </div>
    <div class="mb-5 w-full">
        <div class="max-w-xs rounded mx-auto">
            <img id="output" class="object-contain" />
        </div>
    </div>
    <button type="submit" class="btn btn-block">Submit</button>
</form>

<script>
    const traffic = {!! json_encode($notApprovedPages->toArray(), JSON_HEX_TAG) !!}
    const siteTypes = {!! json_encode($onsite->toArray(), JSON_HEX_TAG) !!}
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
        handlePriceChange(trafficSumEle.textContent, e.target.value)
    })

    function handlePriceChange(trafficSumValue, timeOnsiteValue) {
        const siteType = selectSiteTypesEle.value;
        const trafficSum = trafficSumValue;
        const timeOnsite = siteTypes.find(site => site.id == siteType).onsite[timeOnsiteValue];
        console.log(timeOnsite, trafficSum);
        priceEle.textContent = `${(timeOnsite * trafficSum).toFixed(2)}`;
    }
</script>
