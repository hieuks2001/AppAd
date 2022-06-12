@extends('main')
@section('mission')
    @include('box.patternBox1')
    <br />
    @if (!isset($mission))
        @include('mission.startmission')
    @else
        <div class="ui left aligned segment">
            <h2 style="text-align:center !important">Nhiệm vụ của bạn</h2>
            <div class="text-slate-800">
                <div class="item">
                    <p class="ui huge red text"><b>Bước 1:</b> truy cập vào <a href="/test">{{ $mission->ms_name }}</a></p>
                </div>
                <div class="item">
                    <b>Bước 2:</b> <img class="ui larger image" src="./images/{{ $page->page_image }}"
                        style="max-width: 450px" />
                </div>
                <div class="item">
                    <p><b>Bước 3:</b> Click nhận mã và chờ {{ $mission->ms_countdown }}s</p>
                </div>
                <div class="item">
                    <p><b>Bước 4:</b> Copy mã và nhập vào ô ở phía dưới!</p>
                </div>
            </div>
            <hr>
            @if (session()->has('loi'))
                <div class="ui error message">
                    @php
                        echo Session::get('loi');
                    @endphp
                </div>
            @endif
            <form action="/paste-key" method="post">
                @csrf
                <div class="ui fluid input">
                    <input type="text" name="key" placeholder="Nhập mã của bạn vào đây...">
                </div>
                <br>
                <div class="" style="text-align:center !important">
                    <button class="ui inverted green button">Hoàn thành nhiệm vụ</button>
                    <a class="ui inverted red button" href="/cancel-mission">Bỏ qua nhiệm vụ</a>
                </div>
            </form>
        </div>
    @endif
    <br />
    <br />
    <div class="overflow-x-auto bg-white drop-shadow-2xl p-5 rounded-2xl">
        <div class="flex items-center justify-between">
            <h3 class="text-2xl font-bold text-slate-800">Nhiệm vụ đã thực hiện</h3>
            <input type="text" placeholder="Search..." class="input input-ghost w-full max-w-xs" />
        </div>
        <br />
        <table class="table w-full bg-white">
            <!-- head -->
            <thead class="bg-white">
                <tr>
                    <th class="bg-slate-200">Nhiệm vụ</th>
                    <th class="bg-slate-200">Bắt đầu nhiệm vụ</th>
                    <th class="bg-slate-200">Hoàn thành lúc</th>
                    <th class="bg-slate-200">Số tiền</th>
                    <th class="bg-slate-200">Trạng thái</th>
                </tr>
            <tbody>
                @foreach ($missions as $key => $value)
                    <tr>
                        <td class="bg-white">{{ $value->ms_name }}</td>
                        <td class="bg-white">{{ $value->created_at }}</td>
                        <td class="bg-white">{{ $value->updated_at }}</td>
                        <td class="bg-white">{{ $value->ms_price }}</td>
                        <td class="bg-white">{{ $value->ms_status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
