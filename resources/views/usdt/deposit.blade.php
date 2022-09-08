@extends('usdt.index')
@section('deposit')
    @include('box.patternBox2')
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
              @if (isset($data))
              @foreach ($data as $key)
              <tr>
                  <td class="bg-white">{{$key->updated_at}}</td>
                  <td class="bg-white">{{$key->amount}}</td>
                  <td class="bg-white">momo</td>
                  <td class="bg-white"></td>
              </tr>
              @endforeach
              @endif
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
