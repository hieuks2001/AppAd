@extends('regispage.index')
@section('tab1-blade')
    <table class="table w-full bg-white">
        <!-- head -->
        <thead class="bg-white">
            <tr>
                <th class="bg-slate-200">Trang traffic</th>
                <th class="bg-slate-200">IP người làm</th>
                <th class="bg-slate-200">Phí</th>
                <th class="bg-slate-200">Ngày hoàn thành</th>
            </tr>
        </thead>
        <tbody>
          @foreach($missions as $key => $value)
            <tr>
                <td class="bg-white">{{$value->url}}</td>
                <td class="bg-white">{{$value->ip}}</td>
                <td class="bg-white">{{number_format(((float)$value->price_per_traffic), 3)}}</td>
                <td class="bg-white">{{$value->updated_at}}</td>
            </tr>
          @endforeach
        </tbody>
    </table>
    <div class="btn-group flex justify-center mt-5">
      <a class="btn btn-outline btn-sm {{$missions->onFirstPage() ? 'btn-disabled' : ''}}" href="{{$missions->previousPageUrl()}}">Previous</a>
      <a class="btn btn-outline btn-sm {{!$missions->hasMorePages() ? 'btn-disabled' : ''}}" href="{{$missions->nextPageUrl()}}">Next</a>
    </div>
@endsection

