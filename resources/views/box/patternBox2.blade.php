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
      <h2 class="text-3xl font-bold"><span id="totalCharge"></span> <span class="text-xl"> VND</h2>
      <b>Tổng nạp</b>
    </div>
  </div>
  <div class="bg-green-400 p-5 rounded-xl">
    <div class="ui pink inverted">
      <h2 class="text-3xl font-bold"><span id="remaining"></span> <span class="text-xl"> VND</h2>
      <b>Ví</b>
    </div>
  </div>
</div>
<script>
  const money = {
    "totalCharge" : {!! json_encode($traffic["totalCharge"], JSON_HEX_TAG) !!},
    "remaining" : {!! json_encode($traffic["remaining"], JSON_HEX_TAG) !!},
  }
  for (const element of ["totalCharge","remaining"]) {
    document.getElementById(element).textContent = Intl.NumberFormat("vi").format(money[element])
  }
</script>
