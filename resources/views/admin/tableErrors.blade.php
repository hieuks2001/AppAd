@extends('admin')
@section('management-missions')
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>Code</th>
        <th>Mô tả</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>#1</td>
        <td>Traffic của site chưa sẵn sàng (chưa được duyệt) hoặc đã chạy hết</td>
      </tr>
      <tr>
        <td>#2</td>
        <td>Mã nhúng không đúng site</td>
      </tr>
      <tr>
        <td>#3</td>
        <td>Lỗi ở server</td>
      </tr>
      <tr>
        <td>#4</td>
        <td>Lỗi thao tác không được tìm kiếm từ google search</td>
      </tr>
    </tbody>
  </table>
</div>
@endsection
