@extends('main')
@section('ref')
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Danh sách cấp dưới của bạn</h3>
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <!--<th>Username</th>-->
        <th>SĐT</th>
        <th>Ngày được mời</th>
        <th>Trạng thái</th>
      </tr>
    <tbody>
      @foreach ($users as $key => $value)
        <tr>
          <!--<td>{{  substr($value->phone_number,0,6) }}</td>-->
          <td>{{ substr($value->phone_number,0,6) }}</td>
          <td>{{ $value->created_at }}</td>
          <td>{{
            $value->status == 1 ? "Đang hoạt động" : "Khoá"
          }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  <div class="btn-group flex justify-center mt-5">
    <a class="btn btn-outline btn-sm {{$users->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$users->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$users->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$users->nextPageUrl()}}">Next</a>
  </div>
</div>
@endsection
