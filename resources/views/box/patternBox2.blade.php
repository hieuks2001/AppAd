<div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-center md:text-start">
    <div class="bg-blue-500 p-5 rounded-xl">
        <div class="ui inverted">
            <h2 class="text-3xl font-bold">{{$traffic["sum"]}}</h2>
            <b>Đã mua</b>
        </div>
    </div>
    <div class="bg-red-500 p-5 rounded-xl">
        <div class="ui purple inverted">
            <h2 class="text-3xl font-bold">{{number_format($traffic["totalCharge"],3)}} <span class="text-xl">USDT</span></h2>
            <b>Tổng tiền traffic đang trả</b>
        </div>
    </div>
    <div class="bg-green-400 p-5 rounded-xl">
        <div class="ui pink inverted">
            <h2 class="text-3xl font-bold">{{number_format($traffic["remaining"],3)}} <span class="text-xl">USDT</span></h2>
            <b>Còn lại</b>
        </div>
    </div>
</div>
