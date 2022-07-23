<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5 text-center md:text-start">
    <div class="bg-blue-500 p-5 rounded-xl">
        <div class="ui inverted">
            <h2 class="text-3xl font-bold">{{$traffic["bought"]}}</h2>
            <b>Đã mua</b>
        </div>
    </div>
    <div class="bg-yellow-400 p-5 rounded-xl">
        <div class="ui yellow inverted">
            <h2 class="text-3xl font-bold">{{$traffic["sum"]}}</h2>
            <b>Tổng Traffic</b>
        </div>
    </div>
    <div class="bg-red-500 p-5 rounded-xl">
        <div class="ui purple inverted">
            <h2 class="text-3xl font-bold">{{$traffic["totalCharge"]}} USDT</h2>
            <b>Tổng nạp</b>
        </div>
    </div>
    <div class="bg-green-400 p-5 rounded-xl">
        <div class="ui pink inverted">
            <h2 class="text-3xl font-bold">{{$traffic["remaining"]}} USDT</h2>
            <b>Số dư</b>
        </div>
    </div>
</div>
