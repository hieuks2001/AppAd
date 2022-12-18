@extends('admin')
@section('management-users')

@php
function customQueryStringForSort(String $sortType,$sortNew)
{
if (isset($_SERVER['QUERY_STRING'])) {
$temp = [];
parse_str($_SERVER['QUERY_STRING'],$temp);
if ($sortType == 'sort') {
$temp['sort'] = $sortNew;
}
return http_build_query($temp);
} else {
return "$sortType=$sortNew";
}

}
@endphp
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <div>
      {{-- <h3 class="text-2xl font-bold text-slate-800">Lịch sử giao dịch {{$type === 'traffic' ? 'memtraffic' : 'nhiemvu'}} --}}
      <h3 class="text-2xl font-bold text-slate-800">Lịch sử giao dịch
      </h3>
      {{-- <a href="{{url('management/user/transactions')}}?type={{$type === 'traffic' ? 'mission' : 'traffic'}}"
        class="btn">Chuyển sang {{$type === 'traffic' ? 'nhiemvu' : 'memtraffic'}}</a> --}}
    </div>
    <div class="form-control">
      <form action="{{action('DashboardController@showUsersTransactions')}}" method="get">
        <div class="form-control">
          <label class="input-group">
            <span>Username</span>
            <input type="text" name="username" placeholder="Tìm kiếm username" class="input input-ghost w-full max-w-xs"
              value="{{\Request::has('username') ? \Request::get('username') : ''}}">
          </label>
        </div>
        <div class="form-control">
          <label class="input-group">
            <span>Từ</span>
            <input type="date" name="from" placeholder="Từ" class="input input-ghost w-full max-w-xs"
              value="{{\Request::has('from') ? \Request::get('from') : ''}}">
          </label>
        </div>
        <div class="form-control">
          <label class="input-group">
            <span>Tới</span>
            <input type="date" name="to" placeholder="Tới" class="input input-ghost w-full max-w-xs"
              value="{{\Request::has('to') ? \Request::get('to') : ''}}">
          </label>
          <input type="text" name="type" hidden value={{\Request::get('type')==='traffic' ? 'traffic' : 'mission' }}>
          <button type="submit" class="btn">Tìm</button>
        </div>
      </form>
    </div>
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>Username</th>
        <th>
          <span>Mức chi</span>
        </th>
        <th>
          <span>Mức thu</span>
        </th>
        <th>
          <div class="flex items-center">
            <span>
              Ngày thực hiện
            </span>
            <span class="ml-2 flex-col inline-flex">
              <a href="{{url('management/user/transactions')}}?{{customQueryStringForSort('sort','desc')}}"><svg
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
                </svg>
              </a>
              <a href="{{url('management/user/transactions')}}?{{customQueryStringForSort('sort','asc')}}"><svg
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                  stroke="currentColor" class="w-6 h-6">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                </svg>
              </a>
            </span>
          </div>
        </th>
      </tr>
    <tbody>
      @foreach ($data as $key => $value)
      <tr>
        <td>{{ $value->username }}</td>
        <td>{{ $value->total_outcome > 0 ? '-'.$value->total_outcome : $value->total_outcome}}</td>
        <td>{{ $value->total_income}}</td>
        <td>{{ $value->created_at}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="btn-group flex justify-center mt-5">
    <a class="btn btn-outline btn-sm {{$data->onFirstPage() ? 'btn-disabled' : ''}}"
      href="{{$data->appends(request()->query->all())->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$data->hasMorePages() ? 'btn-disabled' : ''}}"
      href="{{$data->appends(request()->query->all())->nextPageUrl()}}">Next</a>
  </div>
</div>
@endsection
