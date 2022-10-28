@extends('admin')
@section('management-page-types')
@if ($errors->any())
<div class="alert alert-danger">
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
<div class="mb-10 overflow-x-auto rounded-2xl bg-white p-5 drop-shadow-2xl">
  <div class="flex items-center justify-between">
    <h3 class="text-2xl font-bold text-slate-800">Loại trang</h3>
    <div class="flex">
      <label for="modal-create--page__type" class="btn modal-button btn-accent gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
          stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Tạo
      </label>
    </div>
  </div>
  <br />
  <table class="table w-full">
    <!-- head -->
    <thead>
      <tr>
        <th>Id</th>
        <th>Tên loại</th>
        <th>Onsite</th>
        <th>Ngày tạo</th>
        <th>Cập nhập</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach($pageTypes as $key => $value)
      <tr>
        <td>{{$value->id}}</td>
        <td>{{$value->name}}</td>
        <td>
          <ul>
            @foreach ($value->onsite as $onsite_key => $onsite_value)
            <li>{{$onsite_key. "s - " . $onsite_value . "$/traffic"}}</li>
            @endforeach
          </ul>
        </td>
        <td>{{$value->created_at}}</td>
        <td>{{$value->updated_at}}</td>
        <td>
          <label for="modal-edit--page__type" class="btn btn-square btn-outline btn-sm"
            onclick="onClickPageType('{{ $value->id }}')">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </label>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{-- <div class="btn-group flex justify-center mt-5">
    <a class="btn btn-outline btn-sm {{$missions->onFirstPage() ? 'btn-disabled' : ''}}"
      href="{{$missions->previousPageUrl()}}">Previous</a>
    <a class="btn btn-outline btn-sm {{!$missions->hasMorePages() ? 'btn-disabled' : ''}}"
      href="{{$missions->nextPageUrl()}}">Next</a>
  </div> --}}
</div>
<!-- Modal create user type -->
<input type="checkbox" id="modal-create--page__type" class="modal-toggle">
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-create--page__type" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    <h3 class="mb-3 text-lg font-bold">Tạo loại trang mới</h3>
    <form id="form-create" action="{{action('DashboardController@createPageType')}}" method="post" class="mb-0">
      @csrf
      @foreach ($pageTypeInit as $key => $value)
      <label class="input-group mb-3">
        <div class="flex items-center w-full bg-slate-200">
          <span class="font-bold">
            {{$key}}s
          </span>
        </div>
        <input type="text" name="{{$key}}s" placeholder="Giá 1 Traffic: 0"
          class="page_weight input input-bordered w-52">
      </label>
      @endforeach
      <button type="submit" class="btn btn-block">Tạo</button>
    </form>
  </div>
</div>
<!-- Modal create user type -->
<input type="checkbox" id="modal-edit--page__type" class="modal-toggle">
<div class="modal modal-bottom sm:modal-middle">
  <div class="modal-box relative">
    <label for="modal-edit--page__type" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
    <h3 class="mb-3 text-lg font-bold">Tạo loại trang mới</h3>
    <form id="form-edit" method="post" class="mb-0">
      @csrf
      @foreach ($pageTypeInit as $key => $value)
      <label class="input-group mb-3">
        <div class="flex items-center w-full bg-slate-200">
          <span class="font-bold">
            {{$key}}s
          </span>
        </div>
        <input type="text" name="{{$key}}s" placeholder="Giá 1 Traffic: 0" class="input input-bordered w-52">
      </label>
      @endforeach
      <button type="submit" class="btn btn-block">Tạo</button>
    </form>
  </div>
</div>
<script>
  const page_types = {!! json_encode($pageTypes->toArray(), JSON_HEX_TAG) !!}
  const page_types_init = {!! json_encode($pageTypeInit, JSON_HEX_TAG) !!}

  const formEdit = document.getElementById("form-edit");
  function onClickPageType(pid) {
      const pageType = page_types.find(page => page.id == pid)
      /* Setting the action attribute of the form to the url of the user. */
      formEdit.action = `/management/pages/${pid}`;
      for (const onsite in pageType.onsite) {
        const ele = document.querySelector(`#form-edit input[name="${onsite}s"]`);
        ele.value = pageType.onsite[onsite];
      }
    }
</script>
@endsection
