<div class="grid grid-cols-1 md:grid-cols-4 gap-5 text-center md:text-start">
  <div class="bg-blue-500 p-5 rounded-xl">
    <div class="ui inverted">
      <h2 class="text-3xl font-bold">{{$traffic["sum"]}}</h2>
      <b>Đã mua</b>
    </div>
  </div>
  <div class="bg-red-500 p-5 rounded-xl">
    <div class="ui purple inverted">
      <h2 class="text-3xl font-bold">{{$traffic['totalCharge']}}</h2>
      <b>Tổng Traffic đã mua</b>
    </div>
  </div>
  <div class="bg-green-400 p-5 rounded-xl">
    <div class="ui pink inverted">
      {{-- <h2 class="text-3xl font-bold"><span id="remaining"></span> <span class="text-xl">VND</span></h2> --}}
      <h2 class="text-3xl font-bold">{{$traffic["remaining"]}}</h2>
      <b>Traffic còn lại</b>
    </div>
  </div>
  <div class="bg-yellow-400 p-5 rounded-xl">
    <div class="ui pink inverted">
      <h2 class="text-3xl font-bold"><span id="balance"></span> <span class="text-xl">VND</span></h2>
      <b>Ví</b>
    </div>
  </div>
</div>
<script>
  const money = {
    "balance" : {!! json_encode($traffic["balance"], JSON_HEX_TAG) !!},
  }
  for (const element of ["balance"]) {
    document.getElementById(element).textContent = Intl.NumberFormat("vi").format(money[element])
  }
</script>
