<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-center md:text-start">
    <div class="bg-blue-500 p-5 rounded-xl">
        <div class="ui inverted">
            <h2 class="text-3xl font-bold">{{$money["income"]}} USDT</h2>
            <b>Thu nhập</b>
        </div>
    </div>
    {{-- <div class="bg-yellow-400 p-5 rounded-xl">
        <div class="ui yellow inverted">
            <h2 class="text-3xl font-bold">{{$money["commission"]}} USDT</h2>
            <b>Hoa hồng</b>
        </div>
    </div> --}}
    <div class="bg-yellow-400 p-5 rounded-xl">
        <div class="ui purple inverted">
            <h2 class="text-3xl font-bold">{{$money["sum"]}} USDT</h2>
            <b>Tổng cộng</b>
        </div>
    </div>
    <div class="bg-green-400 p-5 rounded-xl">
        <div class="ui pink inverted">
            <h2 class="text-3xl font-bold">{{$money["balance"]}} USDT</h2>
            <b>Số dư</b>
        </div>
    </div>
</div>
