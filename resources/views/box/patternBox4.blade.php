<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-center md:text-start">
    <div class="bg-blue-500 p-5 rounded-xl">
        <div class="ui inverted">
            <h2 class="text-3xl font-bold">{{$money["withdrawing"]}} <span class="text-xl">USDT</span></h2>
            <b>Đang nạp</b>
        </div>
    </div>
    <div class="bg-yellow-400 p-5 rounded-xl">
        <div class="ui yellow inverted">
            <h2 class="text-3xl font-bold">{{$money["withdrawed"]}} <span class="text-xl">USDT</span></h2>
            <b>Đã nạp</b>
        </div>
    </div>
    <div class="bg-green-400 p-5 rounded-xl">
        <div class="ui pink inverted">
            <h2 class="text-3xl font-bold">{{$money["balance"]}} <span class="text-xl">USDT</span></h2>
            <b>Số dư</b>
        </div>
    </div>
</div>
