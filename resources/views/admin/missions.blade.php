@extends('admin')
@section('management-missions')
  <div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-slate-800">Nhiệm vụ đã hoàn thành</h3>
      <form action="{{action('DashboardController@searchMission')}}" method="post">
        @csrf
        <input
          type="text"
          name="data"
          placeholder="Tìm kiếm theo url traffic, trang nhận"
          class="input w-full max-w-xs"
        />
      </form>
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
    <div class="btn-group flex justify-center mt-5">
      <a class="btn btn-outline btn-sm {{$missions->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$missions->previousPageUrl()}}">Previous</a>
      <a class="btn btn-outline btn-sm {{!$missions->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$missions->nextPageUrl()}}">Next</a>
    </div>
  </div>
@endsection