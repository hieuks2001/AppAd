<div class="grid grid-cols-1 sm:grid-cols-3 gap-5 text-center md:text-start">
  <div class="bg-blue-500 p-5 rounded-xl">
    <div class="ui inverted">
      <h2 class="text-3xl font-bold"><span id="withdrawing"></span> <span class="text-xl">VND</span></h2>
      <b>Đang nạp</b>
    </div>
  </div>
  <div class="bg-yellow-400 p-5 rounded-xl">
    <div class="ui yellow inverted">
      <h2 class="text-3xl font-bold"><span id="withdrawed"></span> <span class="text-xl">VND</span></h2>
      <b>Đã nạp</b>
    </div>
  </div>
  <div class="bg-green-400 p-5 rounded-xl">
    <div class="ui pink inverted">
      <h2 class="text-3xl font-bold"><span id="balance"></span> <span class="text-xl">VND</span></h2>
      <b>Ví</b>
    </div>
  </div>
</div>
<script>
  const money = {
    "withdrawing" : {!! json_encode($money["withdrawing"], JSON_HEX_TAG) !!},
    "withdrawed" : {!! json_encode($money["withdrawed"], JSON_HEX_TAG) !!},
    "balance" : {!! json_encode($money["balance"], JSON_HEX_TAG) !!},
  }
  for (const element of ["withdrawing","withdrawed","balance"]) {
    document.getElementById(element).textContent = Intl.NumberFormat("vi").format(money[element])
  }
</script>
