<form id="form" enctype="multipart/form-data" method="POST" class="mb-0">
    @csrf
    <p class="font-bold mb-3">URL: <span class="item font-normal url"></span></p>
    <p class="font-bold mb-3">Username: <span class="item font-normal user"></span></p>
    <p class="font-bold mb-3">Tổng số lượng traffic: <span class="item font-normal traffic_sum"></span></p>
    <p class="font-bold mb-3">Số lượng traffic trong ngày: <span class="item font-normal traffic_per_day"></span></p>
    <p class="font-bold mb-3">Time onsite: <span class="item font-normal onsite"></span></p>
    <p class="font-bold mb-3">Đã trả (USDT): <span class="item font-normal price"></span></p>

    <label class="block">
        <span class="sr-only">Choose profile photo</span>
        <input type="file" accept="image/*"
            class="block w-full text-sm text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-violet-50 file:text-violet-700
                                        hover:file:bg-violet-100 mb-5"
            name="image" id="fileUpload" required>
    </label>
    <div class="flex justify-between items-center">
        <p>
            Chọn mức ưu tiên
        </p>
        <select class="select w-full max-w-xs" name="priority" id="" required>
            @foreach ($priority as $key => $value)
                <option value="{{ $value }}">{{ $key }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-5 w-full">
        <div class="max-w-xs rounded mx-auto">
            <img id="output" class="object-contain" />
        </div>
    </div>
    <button type="submit" class="btn btn-block">Submit</button>
</form>
