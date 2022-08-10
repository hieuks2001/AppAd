@extends('admin')
@section('management-missions')
  <div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-slate-800">Nhiệm vụ đã hoàn thành</h3>
      <input
        type="text"
        placeholder="Search..."
        class="input w-full max-w-xs"
      />
    </div>
    <br />
    <table class="table w-full">
      <!-- head -->
      <thead >
        <tr>
            <th>Trang traffic</th>
            <th>IP người làm</th>
            <th>Nhận từ</th>
            <th>Thưởng</th>
            <th>Ngày hoàn thành</th>
        </tr>
    </thead>
    <tbody>
      @foreach($missions as $key => $value)
        <tr>
            <td>{{$value->url}}</td>
            <td>{{$value->ip}}</td>
            <td>{{$value->origin_url}}</td>
            <td>{{number_format($value->reward, 3)}}</td>
            <td>{{$value->updated_at}}</td>
        </tr>
      @endforeach
    </tbody>
    </table>
  </div>
@endsection