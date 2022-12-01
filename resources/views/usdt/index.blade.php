@extends('main')
@section('usdt')
@yield('deposit')
@yield('withdraw')
@push('scripts')
{{-- <script>
  const convertMoneyEle = document.getElementById("convert-money")
        const amountEle = document.getElementById("amount")
        amountEle.addEventListener('input',(e)=>{
          convertMoneyEle.textContent = "~ "+Intl.NumberFormat("vi").format(e.target.value)+ " VND"
        })
</script> --}}
@endpush
@endsection
