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
                                        hover:file:bg-violet-100"
            name="image" id="fileUpload">
    </label>
    <div class="flex justify-between items-center">
        <p>
            Chọn mức ưu tiên
        </p>
        <select class="item select max-w-xs priority" name="priority" id="" required>
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
        <select id="site_types" class="item select max-w-xs page_type_id" name="page_type" required>
            <option selected disabled> Vui lòng chọn loại site </option>
            @foreach ($onsite as $key => $value)
                <option value="{{ $value->id }}">{{ $value->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex justify-between items-center mb-3">
        <p>
            Chọn timeout
        </p>
        <input type="text" name="timeout" id="timeout" class="item timeout" hidden>
        <div class="flex items-center">
            <input type="number" id="hour" class="input input-bordered" placeholder="HH" maxlength="2"
                min="0" max="24">
            <span class="mx-3">:</span>
            <input type="number" id="minute" class="input input-bordered" placeholder="MM" maxlength="2"
                min="0" max="59">
        </div>
    </div>
    <div class="flex justify-between items-center mb-3">
        <p class="mb-1">Hoa hồng mình hưởng</p>
        <input type="number" class="item input input-bordered hold_percentage" name="hold_percentage" max=100 min=1
            id="">
    </div>
    <div>
        <p class="mb-1">Ghi chú</p>
        <textarea name="note" class="w-full textarea textarea-bordered" placeholder="Ghi chú ở đây"></textarea>
    </div>
    <div class="my-5 w-full">
        <div class="max-w-xs rounded mx-auto">
            <img id="output" class="object-contain" />
        </div>
    </div>
    <button type="submit" class="btn btn-block">Submit</button>
</form>
<script>
    let timeoutEle = document.getElementById("timeout");
    let hourEle = document.getElementById("hour");
    let minuteEle = document.getElementById("minute");

    hourEle.addEventListener("input", (e) => {
        disable(e, hourEle, {
            min: 0,
            max: 24
        })
        timeoutEle.value = `${e.target.value}:${timeoutEle.value.split(":")[1]}`
    })

    minuteEle.addEventListener("input", (e) => {
        disable(e, minuteEle, {
            min: 0,
            max: 59
        })
        timeoutEle.value = `${timeoutEle.value.split(":")[0]}:${e.target.value}`
    })

    function disable(e, ele, minmax) {
        if (e.target.value > minmax.max) {
            ele.value = e.target.value.substr(0, 1)
        }
        if (e.target.value.length >= 2) {
            ele.value = e.target.value.substr(0, 2)
        }
    }
</script>
