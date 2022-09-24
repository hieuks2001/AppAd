@extends('usdt.index')
@section('deposit')
    @include('box.patternBox4')
    @if ($errors->all())
        <div class="alert alert-error shadow-lg my-5">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                @foreach ($errors->all() as $error)
                    <span>
                        {{ $error }}
                    </span>
                @endforeach
            </div>
        </div>
    @endif
    <div class="mt-5 flex items-center p-5 bg-white drop-shadow-2xl rounded-2xl mb-10">
        <form class="flex flex-1 m-0" action="/deposit" method="post" id="form-deposit">
            @csrf
            <input type="text" placeholder="Nhập số tiền muốn nạp" name="amount"
              class="input input-bordered w-full max-w-xs mr-5" required id="amount"/>
            <button id="deposit-btn" class="btn btn-primary" type="submit">Nạp tiền</button>
          </form>
        <div>
          <p class="text-xl" id="convert-money">~ 0 VND</p>
        </div>
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
                    <th class="bg-slate-200">Trạng thái</th>
                </tr>
            <tbody>
              @if (isset($data))
              @foreach ($data as $key)
              <tr>
                  <td class="bg-white">{{$key->updated_at}}</td>
                  <td class="bg-white">{{$key->amount}}</td>
                  <td class="bg-white">momo</td>
                  <td class="bg-white">
                    @if ($key->status == 0)
                        Đang duyệt
                    @else
                        @if ($key->status == 1)
                            Đã duyệt
                        @else
                            Đã huỷ
                        @endif
                    @endif
                  </td>
              </tr>
              @endforeach
              @endif
            </tbody>
        </table>
        <div class="btn-group flex justify-center mt-5">
          <a class="btn btn-outline btn-sm {{$data->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$data->previousPageUrl()}}">Previous</a>
          <a class="btn btn-outline btn-sm {{!$data->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$data->nextPageUrl()}}">Next</a>
        </div>
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
