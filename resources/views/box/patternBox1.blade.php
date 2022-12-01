<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5 text-center md:text-start">
  <div class="bg-blue-500 p-5 rounded-xl">
    <div class="ui inverted">
      <h2 class="text-3xl font-bold"><span id="income"></span> VND</h2>
      <b>Thu nhập</b>
    </div>
  </div>
  <div class="bg-yellow-400 p-5 rounded-xl">
    <div class="ui yellow inverted">
      <h2 class="text-3xl font-bold"><span id="commission"></span> VND</h2>
      <b>Hoa hồng</b>
    </div>
  </div>
  <div class="bg-red-500 p-5 rounded-xl"></div>
  <div class="ui purple inverted">
    <h2 class="text-3xl font-bold"><span id="sum"></span> VND</h2>
    <b>Tổng cộng</b>
  </div>
</div>
<div class="bg-green-400 p-5 rounded-xl">
  <div class="ui pink inverted">
    <h2 class="text-3xl font-bold"><span id="balance"></span> VND</h2>
    <b>Số dư</b>
  </div>
</div>
</div>
<script>
  const money = {
    "income" : {!! json_encode($money["income"], JSON_HEX_TAG) !!},
    "commission" : {!! json_encode($money["commission"], JSON_HEX_TAG) !!},
    "sum" : {!! json_encode($money["sum"], JSON_HEX_TAG) !!},
    "balance" : {!! json_encode($money["balance"], JSON_HEX_TAG) !!},
  }
  for (const element of ["income","commission","sum","balance"]) {
    document.getElementById(element).textContent = Intl.NumberFormat("vi").format(money[element])
  }
</script>
