@extends('usdt.index')
@section('deposit')
@include('box.patternBox2')
<div class="alert alert-warning shadow-lg mt-5">
  <div>
    <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
      viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
    </svg>
    <span>Bạn vui lòng chat với admin để được hướng dẫn nạp tiền.!
  </div>
</div>
<div class="mt-5 flex p-5 bg-white drop-shadow-2xl rounded-2xl mb-10">
  <form class="flex flex-1 m-0" action="/deposit" method="post" id="form-deposit">
    @csrf
    <input type="text" placeholder="Nhập số tiền muốn nạp" name="amount"
      class="input input-bordered w-full max-w-xs mr-5" />
    <button id="deposit-btn" class="btn btn-primary" type="submit">Nạp tiền</button>
  </form>
  <input type="text" placeholder="0xB3822db2D50F93dED229711391e7801Db8858Ab2"
    class="input input-bordered w-full max-w-xs read-only:bg-slate-200" readonly />
</div>
<div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Lịch sử nạp tiền</h3>
    <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
  </div>
  <br />
  <table class="table w-full bg-white">
    <!-- head -->
    <thead class="bg-white">
      <tr>
        <th class="bg-slate-200">Ngày</th>
        <th class="bg-slate-200">Số tiền</th>
        <th class="bg-slate-200">Từ</th>
        <th class="bg-slate-200">Ghi chú</th>
      </tr>
    <tbody>
      <tr>
        <td class="bg-white">a</td>
        <td class="bg-white">b</td>
        <td class="bg-white">b</td>
        <td class="bg-white">c</td>
      </tr>
    </tbody>
  </table>
</div>
@endsection
@push('scripts')
<script defer>
  // const formDeposit = document.getElementById('form-deposit');
        // const amount = document.querySelector('#form-deposit input[type="text"]');
        // contract(async (contract) => {
        //     try {
        //         console.log(ethers.utils.formatUnits(await contract.balanceOf(
        //                 "0xB3822db2D50F93dED229711391e7801Db8858Ab2"), await contract
        //             .decimals()));
        //         console.log(ethers.utils.parseUnits(
        //             "1.0", await contract.decimals()));
        //     } catch (error) {
        //         console.log(JSON.parse(JSON.stringify(error.message)));
        //     }
        // });
        // formDeposit.addEventListener('submit', async (e) => {
        //     e.preventDefault();
        //     sendTransaction(amount.value)
        // })
</script>
@endpush
