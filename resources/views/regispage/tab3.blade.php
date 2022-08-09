@extends('regispage.index')
@section('tab1-blade')
    <table class="table w-full bg-white">
        <!-- head -->
        <thead class="bg-white">
            <tr>
                <th class="bg-slate-200">Trang traffic</th>
                <th class="bg-slate-200">IP người làm</th>
                <th class="bg-slate-200">Thưởng</th>
                <th class="bg-slate-200">Ngày hoàn thành</th>
            </tr>
        </thead>
        <tbody>
          @foreach($missions as $key => $value)
            <tr>
                <td class="bg-white">{{$value->url}}</td>
                <td class="bg-white">{{$value->ip}}</td>
                <td class="bg-white">{{number_format(((float)$value->price_per_traffic) * (100 - (float)$value->hold_percentage) / 100, 3)}}</td>
                <td class="bg-white">{{$value->updated_at}}</td>
            </tr>
          @endforeach
        </tbody>
    </table>
@endsection
