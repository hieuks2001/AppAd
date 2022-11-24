@extends('admin')
@section('management-users')

@php
@endphp
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <div>
      <h3 class="text-2xl font-bold text-slate-800">Lịch sử giao dịch {{$type === 'traffic' ? 'memtraffic' : 'nhiemvu'}}</h3>
      <a href="{{url('management/user/transactions')}}?type={{$type === 'traffic' ? 'mission' : 'traffic'}}" class="btn">Chuyển sang {{$type === 'traffic' ? 'nhiemvu' : 'memtraffic'}}</a>
    </div>
    <div class="form-control">
      <form action="{{action('DashboardController@showUsersTransactions')}}" method="get">
        <div class="form-control">
          <label class="input-group">
            <span>Username</span>
            <input type="text" name="username" placeholder="Tìm kiếm username" class="input input-ghost w-full max-w-xs" value = "{{\Request::has('username') ? \Request::get('username') : ''}}">
          </label>
        </div>
        <div class="form-control">
          <label class="input-group">
            <span>Từ</span>
            <input type="date" name="from" placeholder="Từ" class="input input-ghost w-full max-w-xs" value = "{{\Request::has('from') ? \Request::get('from') : ''}}">
          </label>
        </div>
        <div class="form-control">
          <label class="input-group">
            <span>Tới</span>
            <input type="date" name="to" placeholder="Tới" class="input input-ghost w-full max-w-xs" value = "{{\Request::has('to') ? \Request::get('to') : ''}}">
          </label>
        <div class="form-control">
          <label class="label cursor-pointer">
            <span class="label-text">Tăng dần theo ngày</span>
            <input type="checkbox" name="sort" {{\Request::get('sort') === "on" ? "checked" : ""}} class="checkbox" />
          </label>
        </div>
        <input type="text" name="type" hidden value={{\Request::get('type') === 'traffic' ? 'traffic' : 'mission' }}>
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
        <th>Mức chi</th>
        <th>Mức thu</th>
        <th>Ngày thực hiện</th>
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
    <a class="btn btn-outline btn-sm {{$data->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$data->appends(request()->query->all())->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$data->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$data->appends(request()->query->all())->nextPageUrl()}}">Next</a>
  </div>
</div>
@endsection
