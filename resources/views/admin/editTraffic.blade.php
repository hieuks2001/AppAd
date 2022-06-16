<form action="{{ action('DashboardController@postApproveTraffic', $page->id) }}" enctype="multipart/form-data"
    method="POST" class="mb-0">
    @csrf
    <p>URL: {{ $page->url }}</p>
    <p>Username: {{ $page->user->username }}</p>
    <p>Tổng số lượng traffic: {{ $page->traffic_sum }}</p>
    <p>Số lượng traffic trong ngày: {{ $page->traffic_per_day }}</p>
    <p>Time onsite: {{ $page->onsite }}</p>
    <p>Đã trả (USDT): {{ $page->price }}</p>

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

    <p>
        Chọn mức ưu tiên
    </p>
    <select class="select w-full max-w-xs" name="priority" id="" required>
        @foreach ($priority as $key => $value)
            <option value="{{ $value }}">{{ $key }}</option>
        @endforeach
    </select>
    <div class="avatar mb-5">
        <div class="max-w-xs rounded">
            <img id="output" class="object-contain" />
        </div>
    </div>

    <button type="submit" class="btn btn-block">Submit</button>
</form>

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
</script>
